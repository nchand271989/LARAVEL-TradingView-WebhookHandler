<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    use HasFactory;

    protected $table = 'trades';
    protected $primaryKey = 'id';
    public $incrementing = false; // Since id is manually assigned
    protected $keyType = 'unsignedBigInteger';

    protected $fillable = [
        'id',
        'webhook_id',
        'strategy_id',
        'rule_id',
        'wallet_id',
        'positionType',
        'quantity',
        'timeframe',
        'openingPrice',
        'openingTime',
        'closingPrice',
        'closingTime',
        'comments',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($trade) {
            $trade->id = generate_snowflake_id();
        });
    }

    protected $casts = [
        'id' => 'integer',
        'webhook_id' => 'integer',
        'strategy_id' => 'integer',
        'scenario_id' => 'integer',
        'wallet_id' => 'integer',
        'quantity' => 'float',
        'openingPrice' => 'float',
        'closingPrice' => 'float',
        'openingTime' => 'datetime',
        'closingTime' => 'datetime',
        'status' => 'string',
        'positionType' => 'string',
    ];
}
