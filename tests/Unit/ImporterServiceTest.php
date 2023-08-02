<?php

namespace Tests\Unit;

use App\Services\Importer\ImporterService;
use App\Services\Importer\ImporterServiceInterface;
use App\Services\Importer\Report;
use PHPUnit\Framework\TestCase;

class ImporterServiceTest extends TestCase
{
    public function test_can_import_transactions(): void
    {
        $importer = $this->createImporter();
        $result   = $importer->process($this->getFile(), $this->getMapping());

        // 2 merchants
        $this->assertEquals(2, $result->getMerchantCount());

        // with 3 batches
        $this->assertEquals(3, $result->getBatchCount());

        // with 5 transactions
        $this->assertEquals(5, $result->getTransactionCount());
    }

    /**
     * Creates an importer instance for testing purposes
     */
    private function createImporter(): ImporterServiceInterface
    {
        // TODO: implement me

        return new ImporterService();
    }

    /**
     * Gets a sample report
     *
     * @return string Full path to a sample report
     */
    private function getFile(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . 'samples' . DIRECTORY_SEPARATOR . 'small.csv';
    }

    /**
     * Gets a sample mapping
     *
     * @return string[] Sample mapping
     */
    private function getMapping(): array
    {
        return [
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
    }
}
