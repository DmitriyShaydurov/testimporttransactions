<?php

namespace App\Services\Importer;

class MappingService implements MappingServiceInterface
{
    /**
     * Generate the mapping based on the structure of the CSV file
     *
     * @return array
     */
    public function generateMapping(): array
    {
        // Logic to analyze the CSV file structure and dynamically generate mapping
        // Or simply get array from somewhere

        return [
            Report::MERCHANT_ID => Report::MERCHANT_ID,
            Report::MERCHANT_NAME => Report::MERCHANT_NAME,
            Report::BATCH_DATE => Report::BATCH_DATE,
            Report::BATCH_REF_NUM => Report::BATCH_REF_NUM,
            Report::TRANSACTION_DATE => Report::TRANSACTION_DATE,
            Report::TRANSACTION_TYPE => Report::TRANSACTION_TYPE,
            Report::TRANSACTION_CARD_TYPE => Report::TRANSACTION_CARD_TYPE,
            Report::TRANSACTION_CARD_NUMBER => Report::TRANSACTION_CARD_NUMBER,
            Report::TRANSACTION_AMOUNT => Report::TRANSACTION_AMOUNT,
        ];
    }
}
