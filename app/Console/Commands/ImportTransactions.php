<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Importer\ImporterServiceInterface;
use App\Services\Importer\Report;
use Illuminate\Console\Command;

class ImportTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-transactions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports transactions.';

    /**
     * Execute the console command.
     */
    public function handle(ImporterServiceInterface $service): int
    {
        $filename = storage_path('reports' . DIRECTORY_SEPARATOR . 'report.csv');
        $mapping  = [
            Report::TRANSACTION_DATE        => 'Transaction Date',
            Report::TRANSACTION_TYPE        => 'Transaction Type',
            Report::TRANSACTION_CARD_TYPE   => 'Transaction Card Type',
            Report::TRANSACTION_CARD_NUMBER => 'Transaction Card Number',
            Report::TRANSACTION_AMOUNT      => 'Transaction Amount',
            Report::BATCH_DATE              => 'Batch Date',
            Report::BATCH_REF_NUM           => 'Batch Reference Number',
            Report::MERCHANT_ID             => 'Merchant ID',
            Report::MERCHANT_NAME           => 'Merchant Name',
        ];

        $result = $service->process($filename, $mapping);

        $this->info(
            sprintf(
                'Imported %d merchants, %d batches, and %d transactions',
                $result->getMerchantCount(),
                $result->getBatchCount(),
                $result->getTransactionCount()
            )
        );

        return static::SUCCESS;
    }
}
