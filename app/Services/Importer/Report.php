<?php

namespace App\Services\Importer;

/**
 * Class Report
 * @package Importer
 */
class Report
{
    public const MERCHANT_ID             = 'mid';             // digits only, up to 18 digits
    public const MERCHANT_NAME           = 'dba';             // string, max length - 100
    public const BATCH_DATE              = 'batch_date';      // YYYY-MM-DD
    public const BATCH_REF_NUM           = 'batch_ref_num';   // digits only, up to 24 digits
    public const TRANSACTION_DATE        = 'trans_date';      // YYYY-MM-DD
    public const TRANSACTION_TYPE        = 'trans_type';      // string, max length - 20
    public const TRANSACTION_CARD_TYPE   = 'trans_card_type'; // string, max length - 2, possible values - VI/MC/AX and so on
    public const TRANSACTION_CARD_NUMBER = 'trans_card_num';  // string, max length - 20
    public const TRANSACTION_AMOUNT      = 'trans_amount';    // amount, negative values are possible
}
