<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeWallet extends Model
{
    protected $primaryKey = 'wltid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['wltid','exchange_id', 'status'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($wallet) {
            $wallet->wltid = generate_snowflake_id();
        });
    }

    public function exchange()
    {
        return $this->belongsTo(Exchange::class, 'exchange_id', 'exid');
    }

    public function ledger()
    {
        return $this->hasMany(Ledger::class, 'wallet_id', 'wltid');
    }

}
