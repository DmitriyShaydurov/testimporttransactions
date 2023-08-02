<?php

namespace App\Services\Importer;

use App\Models\Merchant;
use App\Models\Batch;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class ImporterService implements ImporterServiceInterface
{
    private $batchSize = 1000;  // размер буфера

    protected $fileService;
    protected $mappingService;

    public function __construct(FileServiceInterface $fileService, MappingServiceInterface $mappingService)
    {
        $this->fileService = $fileService;
        $this->mappingService = $mappingService;
    }

    public function process(string $filename, array $mapping): Result
    {
        $csv = Reader::createFromPath($filename, 'r');
        $csv->setHeaderOffset(0); //set the CSV header offset
        $result = new Result();

        $merchants = [];
        $batches = [];
        $transactions = [];

        DB::transaction(function () use ($csv, $mapping, $result, &$merchants, &$batches, &$transactions) {
            foreach ($csv as $index => $record) {
                // Prepare merchant data
                $merchantData = [
                    'id' => $record[$mapping[Report::MERCHANT_ID]],
                    'name' => $record[$mapping[Report::MERCHANT_NAME]],
                ];
                $merchants[$merchantData['id']] = $merchantData;
                $result->incrementMerchantCount();

                // Prepare batch data
                $batchData = [
                    'merchant_id' => $record[$mapping[Report::MERCHANT_ID]],
                    'batch_date' => $record[$mapping[Report::BATCH_DATE]],
                    'batch_ref_num' => $record[$mapping[Report::BATCH_REF_NUM]],
                ];
                $batchKey = implode('-', $batchData);
                $batches[$batchKey] = $batchData;
                $result->incrementBatchCount();

                // Prepare transaction data
                $transactionData = [
                    'batch_id' => $batchKey,
                    'transaction_date' => $record[$mapping[Report::TRANSACTION_DATE]],
                    'transaction_type' => $record[$mapping[Report::TRANSACTION_TYPE]],
                    'transaction_card_type' => $record[$mapping[Report::TRANSACTION_CARD_TYPE]],
                    'transaction_card_number' => $record[$mapping[Report::TRANSACTION_CARD_NUMBER]],
                    'transaction_amount' => $record[$mapping[Report::TRANSACTION_AMOUNT]],
                ];
                $transactions[] = $transactionData;
                $result->incrementTransactionCount();

                // If buffer is full, insert data and clean buffers
                if (($index + 1) % $this->batchSize == 0) {
                    $this->insertData($merchants, $batches, $transactions);
                    $merchants = [];
                    $batches = [];
                    $transactions = [];
                }
            }
            // Insert remaining data
            if ($merchants || $batches || $transactions) {
                $this->insertData($merchants, $batches, $transactions);
            }
        });

        return $result;
    }

    private function insertData(array $merchants, array $batches, array $transactions)
    {
        // Insert merchants
        Merchant::upsert(array_values($merchants), ['id'], ['name']);

        // Insert batches with getting their actual IDs
        foreach ($batches as $batchKey => &$batchData) {
            $batch = Batch::updateOrCreate($batchData);
            $batchData['id'] = $batch->id;
        }

        // Replace batch keys in transactions with actual IDs
        foreach ($transactions as &$transactionData) {
            $transactionData['batch_id'] = $batches[$transactionData['batch_id']]['id'];
        }

        // Insert transactions
        Transaction::insert($transactions);
    }

    public function getFileService(): FileServiceInterface
    {
        return $this->fileService;
    }

    public function getMappingService(): MappingServiceInterface
    {
        return $this->mappingService;
    }
}
