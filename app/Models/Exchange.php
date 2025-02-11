<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Services\Snowflake;

class Exchange extends Model
{
    use HasFactory;

    protected $primaryKey = 'exid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'exid', 
        'name', 
        'createdBy', 
        'lastUpdatedBy', 
        'status'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($exchange) {
            $exchange->exid = generate_snowflake_id();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }

    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(Currency::class, 'exchange_currency', 'exid', 'curid');
    }
}

