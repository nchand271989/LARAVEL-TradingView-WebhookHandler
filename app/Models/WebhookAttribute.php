<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookAttribute extends Model
{
    use HasFactory;
    protected $fillable = ['webhook_id', 'attribute_name', 'attribute_value'];

    public function webhook()
    {
        return $this->belongsTo(Webhook::class, 'webhook_id', 'webhid');
    }
}
