<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use App\Services\Snowflake;

class Webhook extends Model
{
    use HasFactory;

    protected $primaryKey = 'webhid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['webhid', 'name', 'stratid', 'createdBy', 'lastUpdatedBy', 'status'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($webhook) {
            $snowflake = new Snowflake(1); // Machine ID = 1
            $webhook->webhid = $snowflake->generateId();
        });
    }

    public function strategy()
    {
        return $this->belongsTo(Strategy::class, 'stratid', 'stratid');
    }

    public function attributes()
    {
        return $this->hasMany(WebhookAttribute::class, 'webhid', 'webhid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }
}


