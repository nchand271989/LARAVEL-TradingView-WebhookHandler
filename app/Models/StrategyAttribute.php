<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StrategyAttribute extends Model
{
    use HasFactory;

    protected $fillable = ['stratid', 'attribute_name', 'attribute_value'];

    public function strategy()
    {
        return $this->belongsTo(Strategy::class, 'stratid', 'stratid');
    }
}
