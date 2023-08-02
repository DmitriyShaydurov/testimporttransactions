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

        return [
            Report::MERCHANT_ID => 'mid',
            Report::MERCHANT_NAME => 'dba',
            Report::BATCH_DATE => 'batch_date',
            Report::BATCH_REF_NUM => 'batch_ref_num',
            Report::TRANSACTION_DATE => 'trans_date',
            Report::TRANSACTION_TYPE => 'trans_type',
            Report::TRANSACTION_CARD_TYPE => 'trans_card_type',
            Report::TRANSACTION_CARD_NUMBER => 'trans_card_num',
            Report::TRANSACTION_AMOUNT => 'trans_amount',
        ];
    }
}
