<?php

namespace App\Services\Importer;

class DataProcessor implements DataProcessorInterface
{
    /**
     * Process a single record from the CSV file.
     *
     * @param array $record The CSV record
     * @param array $mapping The mapping of CSV columns to database fields
     * @param array $merchants The current merchants data
     * @param array $batches The current batches data
     * @param array $transactions The current transactions data
     */
    public function processRecord(array $record, array $mapping, &$merchants, &$batches, &$transactions)
    {
        // Prepare merchant data
        $merchantData = [
            'id' => $record[$mapping[Report::MERCHANT_ID]],
            'name' => $record[$mapping[Report::MERCHANT_NAME]],
        ];
        $merchants[$merchantData['id']] = $merchantData;

        // Prepare batch data
        $batchData = [
            'merchant_id' => $record[$mapping[Report::MERCHANT_ID]],
            'batch_date' => $record[$mapping[Report::BATCH_DATE]],
            'batch_ref_num' => $record[$mapping[Report::BATCH_REF_NUM]],
        ];
        $batchKey = implode('-', $batchData);
        $batches[$batchKey] = $batchData;

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
    }
}
