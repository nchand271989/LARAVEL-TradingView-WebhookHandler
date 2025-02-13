<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Webhook extends Model
{
    use HasFactory;

    protected $primaryKey = 'webhid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['webhid', 'name', 'strategy_id', 'createdBy', 'lastUpdatedBy', 'status'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($webhook) {
            $webhook->webhid = generate_snowflake_id();
        });
    }

    public function strategy()
    {
        return $this->belongsTo(Strategy::class, 'strategy_id', 'stratid');
    }

    public function attributes()
    {
        return $this->hasMany(WebhookAttribute::class, 'webhook_id', 'webhid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }
}


