<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'batch_id',
        'transaction_date',
        'transaction_type',
        'transaction_card_type',
        'transaction_card_number',
        'transaction_amount'
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
