<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Webhook extends Model
{
    use HasFactory;

    protected $primaryKey = 'webhid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['webhid', 'name', 'strategy_id', 'exchange_id', 'currency_id', 'createdBy', 'lastUpdatedBy', 'status'];

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

    public function exchange()
    {
        return $this->belongsTo(Exchange::class, 'exchange_id', 'exid');
    }

    public function attributes()
    {
        return $this->hasMany(WebhookAttribute::class, 'webhook_id', 'webhid');
    }

    public function scenario(): BelongsToMany
    {
        return $this->belongsToMany(Rule::class, 'scenarios', 'scenario_id', 'rule_id');
    }

    public function rules(): BelongsToMany
    {
        return $this->belongsToMany(Rule::class, 'webhook_rules', 'webhook_id', 'rule_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }
}


