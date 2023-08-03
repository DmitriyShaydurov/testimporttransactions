<?php

namespace App\Services\Importer;

use App\Models\Merchant;
use App\Models\Batch;
use App\Models\Transaction;

class DataInserter implements DataInserterInterface
{
    /**
     * Insert the processed data into the database.
     *
     * @param array $merchants The merchants data
     * @param array $batches The batches data
     * @param array $transactions The transactions data
     */
    public function insertData(array $merchants, array $batches, array $transactions)
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
}
