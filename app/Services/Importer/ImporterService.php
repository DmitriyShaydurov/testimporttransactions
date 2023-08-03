<?php

namespace App\Services\Importer;

use App\Models\Merchant;
use App\Models\Batch;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ImporterService implements ImporterServiceInterface
{
    private $batchSize;
    protected $fileService;
    protected $csvReader;
    protected $dataInserter;
    protected $dataProcessor;

    public function __construct(CSVReaderInterface $csvReader, DataInserterInterface $dataInserter, DataProcessorInterface $dataProcessor)
    {
        $this->csvReader = $csvReader;
        $this->dataInserter = $dataInserter;
        $this->dataProcessor = $dataProcessor;
        $this->batchSize = config('csvImporter.size');
    }

    public function process(string $filename, array $mapping): Result
    {
        $csv = $this->csvReader->read($filename);
        $result = new Result();

        $merchants = [];
        $batches = [];
        $transactions = [];

        DB::transaction(function () use ($csv, $mapping, $result, &$merchants, &$batches, &$transactions) {
            foreach ($csv as $index => $record) {

                // Despite the lack of cleanliness, we are attempting to conserve memory here.
                $this->dataProcessor->processRecord($record, $mapping, $merchants, $batches, $transactions);

                $result->incrementTransactionCount();

                // If buffer is full, insert data and clean buffers
                if (($index + 1) % $this->batchSize == 0) {
                    $this->dataInserter->insertData($merchants, $batches, $transactions);
                    $merchants = [];
                    $batches = [];
                    $transactions = [];
                }
            }
            // Insert remaining data
            if ($merchants || $batches || $transactions) {
                $this->dataInserter->insertData($merchants, $batches, $transactions);
            }
        });

        return $result;
    }

}
