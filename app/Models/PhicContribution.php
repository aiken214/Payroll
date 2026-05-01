<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhicContribution extends Model
{
    protected $fillable = [
        'year', 'range_from', 'range_to',
        'premium_rate', 'monthly_premium',
    ];
}
