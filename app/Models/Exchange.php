<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use App\Services\Snowflake;

class Exchange extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'exid';
    public $incrementing = false;

    protected $fillable = ['exid', 'name', 'createdBy', 'lastUpdatedBy', 'status'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($exchange) {
            $snowflake = new Snowflake(1); // Machine ID = 1
            $exchange->exid = $snowflake->generateId();
        });
    }

    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(Currency::class, 'exchange_currency', 'exid', 'curid');
    }
}

