<?php

namespace App\Services\Importer;

/**
 * Class Result
 */
class Result
{
    /** @var int Number of imported merchants */
    private int $merchants = 0;

    /** @var int Number of imported batches */
    private int $batches = 0;

    /** @var int Number of imported transactions */
    private int $transactions = 0;

    /**
     * Gets a number of imported merchants
     *
     * @return int Number of imported merchants
     */
    public function getMerchantCount(): int
    {
        return $this->merchants;
    }

    /**
     * Increment the count of imported merchants
     */
    public function incrementMerchantCount(): void
    {
        $this->merchants++;
    }

    /**
     * Gets a number of imported batches
     *
     * @return int Number of imported batches
     */
    public function getBatchCount(): int
    {
        return $this->batches;
    }

    /**
     * Increment the count of imported batches
     */
    public function incrementBatchCount(): void
    {
        $this->batches++;
    }

    /**
     * Gets a number of imported transactions
     *
     * @return int Number of imported transactions
     */
    public function getTransactionCount(): int
    {
        return $this->transactions;
    }

    /**
     * Increment the count of imported transactions
     */
    public function incrementTransactionCount(): void
    {
        $this->transactions++;
    }
}
