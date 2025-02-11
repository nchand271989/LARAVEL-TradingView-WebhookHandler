<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;

    protected $table = 'ledger';
    protected $fillable = ['wltid', 'amount', 'transaction_type', 'description', 'transaction_time'];

    public function wallet()
    {
        return $this->belongsTo(ExchangeWallet::class, 'wltid', 'wltid');
    }
}
