<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SssContribution extends Model
{
    protected $fillable = [
        'year', 'range_from', 'range_to',
        'er_ss', 'ee_ss', 'er_ec', 'ee_ec',
        'er_mpf', 'ee_mpf',
        'total_er', 'total_ee', 'total_contribution',
    ];
}
