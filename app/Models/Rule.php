<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $primaryKey = 'rid';   // Define the primary key
    protected $fillable = ['rid', 'name', 'status'];

     /** Boot method to set default values when creating a new rule. */
     protected static function boot()
     {
        parent::boot();
        static::creating(function ($rule) {
            $rule->rid = generate_snowflake_id();
        });
     }

    public function webhooks(): BelongsToMany
    {
        return $this->belongsToMany(Webhook::class, 'webhook_rules', 'webhook_id', 'rule_id');
    }

}
