<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Currency extends Model
{
    use HasFactory;

    protected $primaryKey = 'curid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['curid', 'name', 'shortcode', 'createdBy', 'lastUpdatedBy', 'status'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($currency) {
            $currency->curid = (string) Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }

    public function exchanges(): BelongsToMany
    {
        return $this->belongsToMany(Exchange::class, 'exchange_currency', 'curid', 'exid');
    }
}
