<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scenario extends Model
{
    use HasFactory;

    protected $primaryKey = 'scnid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['scnid', 'name', 'ratio', 'auto_exit', 'stop_loss', 'target_profit'];

    public function wallet()
    {
        return $this->hasOne(ExchangeWallet::class, 'scnid', 'scnid');
    }
}
