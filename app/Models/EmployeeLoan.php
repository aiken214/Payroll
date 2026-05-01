<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeLoan extends Model
{
    protected $fillable = [
        'employee_id', 'loan_type', 'description',
        'total_amount', 'monthly_amortization', 'balance',
        'start_date', 'end_date', 'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getSemiMonthlyAmortizationAttribute(): float
    {
        return $this->monthly_amortization / 2;
    }
}
