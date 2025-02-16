<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scenario extends Model
{
    protected $fillable = ['scnrid', 'name', 'webhook_id', 'transaction_time'];

     /** Boot method to set default values when creating a new user. */
     protected static function boot()
     {
        parent::boot();
        static::creating(function ($scenario) {
            $scenario->scnrid = generate_snowflake_id();
        });
     }

    public function webhook()
    {
        return $this->belongsTo(Webhook::class, 'webhook_id', 'webhid');
    }

    public function wallet()
    {
        return $this->hasMany(Wallet::class, 'scenario_id');
    }
}
