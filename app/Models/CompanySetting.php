<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $fillable = [
        'company_name', 'company_address', 'company_logo',
        'working_days_per_month', 'minimum_daily_wage',
        'ot_rate_regular', 'ot_rate_rest_day', 'ot_rate_rest_day_ot',
        'ot_rate_special_holiday', 'ot_rate_special_holiday_ot',
        'ot_rate_legal_holiday', 'ot_rate_legal_holiday_ot',
        'night_diff_rate',
        'hdmf_employee_rate', 'hdmf_employer_rate', 'hdmf_max_compensation',
    ];
}
