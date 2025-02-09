<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookAttribute extends Model
{
    use HasFactory;
    protected $fillable = ['webhid', 'attribute_name', 'attribute_value'];

    public function webhook()
    {
        return $this->belongsTo(Webhook::class, 'webhid', 'webhid');
    }
}
