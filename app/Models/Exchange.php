<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Exchange extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'exid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['name', 'createdBy', 'status'];

    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(Currency::class, 'exchange_currency', 'exchange_id', 'currency_id');
    }
}

