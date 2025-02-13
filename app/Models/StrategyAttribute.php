<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Strategy;

class StrategyAttribute extends Model
{
    use HasFactory;

    protected $fillable = ['strategy_id', 'attribute_name', 'attribute_value'];

    public function strategy()
    {
        return $this->belongsTo(Strategy::class, 'strategy_id', 'stratid');
    }
}
