<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Strategy extends Model
{
    use HasFactory;

    protected $primaryKey = 'strgrid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['strgrid', 'name', 'pineScript', 'createdBy', 'status'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($strategy) {
            $strategy->strgrid = (string) Str::uuid();
        });
    }

    public function attributes()
    {
        return $this->hasMany(StrategyAttribute::class, 'strgrid', 'strgrid');
    }
    

    public function user()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }
}
