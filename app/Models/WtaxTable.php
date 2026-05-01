<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WtaxTable extends Model
{
    protected $fillable = [
        'frequency', 'min_range', 'max_range',
        'base_tax', 'tax_rate', 'excess_over',
    ];
}
