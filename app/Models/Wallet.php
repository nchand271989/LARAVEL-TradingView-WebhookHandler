<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $primaryKey = 'wltid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['wltid', 'webhook_id', 'rule_id', 'status'];    

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($wallet) {
            $wallet->wltid = generate_snowflake_id();
        });
    }

    public function scenario()
    {
        return $this->belongsTo(Scenario::class, 'scenario_id');
    }

    public function ledger()
    {
        return $this->hasMany(Ledger::class, 'wallet_id', 'wltid');
    }    

    public function balance()
    {
        return $this->hasOne(Ledger::class, 'wallet_id', 'wltid')
            ->selectRaw("SUM(CASE WHEN transaction_type = 'Credit' THEN amount ELSE -amount END) as balance, wallet_id")
            ->groupBy('wallet_id');
    }


}
