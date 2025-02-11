<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use App\Services\Snowflake;

class Strategy extends Model
{
    use HasFactory;

    protected $primaryKey = 'stratid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['stratid', 'name', 'pineScript', 'auto_reverse_order', 'createdBy', 'lastUpdatedBy', 'status'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($strategy) {
            $snowflake = new Snowflake(1); // Machine ID = 1
            $strategy->stratid = $snowflake->generateId();
        });
    }

    public function attributes()
    {
        return $this->hasMany(StrategyAttribute::class, 'stratid', 'stratid');
    }
    

    public function user()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }
}
