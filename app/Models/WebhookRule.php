<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookRule extends Model
{
    protected $fillable = ['rule_id', 'webhook_id'];


}
