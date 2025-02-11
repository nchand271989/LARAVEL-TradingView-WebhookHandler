<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\Snowflake;

class ExchangeWallet extends Model
{
    protected $table = 'exchange_wallets';
    protected $primaryKey = 'wltid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['wltid', 'exid',  'scnid', 'status'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($wallet) {
            $snowflake = new Snowflake(1); // Machine ID = 1
            $wallet->wltid = $snowflake->generateId();
        });
    }

    public function exchange()
    {
        return $this->belongsTo(Exchange::class, 'exid', 'exid');
    }

    public function ledger()
    {
        return $this->hasMany(Ledger::class, 'wltid', 'wltid');
    }

    public function scenario()
    {
        return $this->belongsTo(Scenario::class, 'scnid', 'scnid');
    }
}
