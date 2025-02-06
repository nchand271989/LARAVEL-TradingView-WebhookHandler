<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermsAndConditions extends Model
{
    use HasFactory;

    protected $table = 'terms_and_conditions'; // Ensure this matches your database table

    protected $fillable = ['tid', 'content', 'version'];
}
