<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'employee_number', 'first_name', 'last_name', 'email',
        'department_id', 'position_id', 'employment_status',
        'monthly_salary', 'salary_type', 'atm_number',
        'sss_number', 'phic_number', 'hdmf_number', 'tin_number',
        'leave_sick_balance', 'leave_vacation_balance',
        'is_minimum_wage_earner', 'is_active', 'date_hired',
    ];

    protected $casts = [
        'monthly_salary' => 'decimal:2',
        'is_minimum_wage_earner' => 'boolean',
        'is_active' => 'boolean',
        'date_hired' => 'date',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(EmployeeLoan::class);
    }

    public function activeLoans(): HasMany
    {
        return $this->hasMany(EmployeeLoan::class)->where('is_active', true)->where('balance', '>', 0);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getSemiMonthlySalaryAttribute(): float
    {
        return $this->monthly_salary / 2;
    }

    public function getDailyRateAttribute(): float
    {
        $settings = CompanySetting::first();
        $workingDays = $settings ? $settings->working_days_per_month : 26;
        return $this->monthly_salary / $workingDays;
    }

    public function getHourlyRateAttribute(): float
    {
        return $this->daily_rate / 8;
    }
}
