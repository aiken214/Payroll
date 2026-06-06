@extends('layouts.app')
@section('title', 'Edit Payroll - ' . $payroll->employee->full_name)

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="mb-0">{{ $payroll->employee->full_name }} - {{ $payroll->payrollPeriod->period_label }}</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('payroll.entry.update', $payroll) }}">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="bg-light p-2 rounded text-center">
                        <small class="text-muted">Monthly Salary</small>
                        <div class="fw-bold">{{ number_format($payroll->monthly_salary, 2) }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-light p-2 rounded text-center">
                        <small class="text-muted">Daily Rate</small>
                        <div class="fw-bold">{{ number_format($payroll->daily_rate, 2) }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="bg-light p-2 rounded text-center">
                        <small class="text-muted">Hourly Rate</small>
                        <div class="fw-bold">{{ number_format($payroll->hourly_rate, 4) }}</div>
                    </div>
                </div>
            </div>

            <h6 class="border-bottom pb-2 mb-3">Basic & Attendance</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">Days Worked</label>
                    <input type="number" step="0.5" name="days_worked" class="form-control" value="{{ $payroll->days_worked }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Basic Pay</label>
                    <input type="number" step="0.01" name="basic_pay" class="form-control" value="{{ $payroll->basic_pay }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Late (Minutes)</label>
                    <input type="number" step="1" name="late_minutes" class="form-control" value="{{ $payroll->late_minutes }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Absent Days</label>
                    <input type="number" step="0.5" name="absent_days" class="form-control" value="{{ $payroll->absent_days }}">
                </div>
            </div>

            <h6 class="border-bottom pb-2 mb-3">Overtime & Premium Pay</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">Regular OT Hrs</label>
                    <input type="number" step="0.01" name="regular_ot_hrs" class="form-control" value="{{ $payroll->regular_ot_hrs }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Rest Day Hrs</label>
                    <input type="number" step="0.01" name="rest_day_hrs" class="form-control" value="{{ $payroll->rest_day_hrs }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Rest Day OT Hrs</label>
                    <input type="number" step="0.01" name="rest_day_ot_hrs" class="form-control" value="{{ $payroll->rest_day_ot_hrs }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Night Diff Hrs</label>
                    <input type="number" step="0.01" name="night_diff_hrs" class="form-control" value="{{ $payroll->night_diff_hrs }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Special Holiday Hrs</label>
                    <input type="number" step="0.01" name="special_holiday_hrs" class="form-control" value="{{ $payroll->special_holiday_hrs }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Special Holiday OT</label>
                    <input type="number" step="0.01" name="special_holiday_ot_hrs" class="form-control" value="{{ $payroll->special_holiday_ot_hrs }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Legal Holiday Hrs</label>
                    <input type="number" step="0.01" name="legal_holiday_hrs" class="form-control" value="{{ $payroll->legal_holiday_hrs }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Legal Holiday OT</label>
                    <input type="number" step="0.01" name="legal_holiday_ot_hrs" class="form-control" value="{{ $payroll->legal_holiday_ot_hrs }}">
                </div>
            </div>

            <h6 class="border-bottom pb-2 mb-3">Leave</h6>
            @php
                $yearlyEntitlement = \App\Models\CompanySetting::first()->sick_leave_per_year ?? 3;
                $usedThisYear = \App\Models\Payroll::where('employee_id', $payroll->employee_id)
                    ->where('id', '!=', $payroll->id)
                    ->whereHas('payrollPeriod', fn($q) => $q->where('year', $payroll->payrollPeriod->year))
                    ->sum('leave_sick_taken');
                $remainingSickLeave = max(0, $yearlyEntitlement - $usedThisYear);
            @endphp
            <div class="alert alert-info py-2 mb-3" style="font-size:.85rem">
                Sick Leave Balance: <strong>{{ $remainingSickLeave }}</strong> of {{ $yearlyEntitlement }} days remaining this year
                ({{ $usedThisYear }} used in other periods)
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">Sick Leave Taken</label>
                    <input type="number" step="0.5" name="leave_sick_taken" class="form-control" value="{{ $payroll->leave_sick_taken }}">
                    <small class="text-muted">Paid: up to {{ $remainingSickLeave }} days. Excess will be deducted.</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Vacation Leave Taken</label>
                    <input type="number" step="0.5" name="leave_vacation_taken" class="form-control" value="{{ $payroll->leave_vacation_taken }}">
                </div>
            </div>

            <h6 class="border-bottom pb-2 mb-3">Allowances & Additions</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">COLA</label>
                    <input type="number" step="0.01" name="cola_amount" class="form-control" value="{{ $payroll->cola_amount }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Incentives</label>
                    <input type="number" step="0.01" name="incentives" class="form-control" value="{{ $payroll->incentives }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Hazard Pay</label>
                    <input type="number" step="0.01" name="hazard_pay" class="form-control" value="{{ $payroll->hazard_pay }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Commission</label>
                    <input type="number" step="0.01" name="commission" class="form-control" value="{{ $payroll->commission }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">De Minimis</label>
                    <input type="number" step="0.01" name="de_minimis" class="form-control" value="{{ $payroll->de_minimis }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Allowances</label>
                    <input type="number" step="0.01" name="allowances" class="form-control" value="{{ $payroll->allowances }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Bonus</label>
                    <input type="number" step="0.01" name="bonus" class="form-control" value="{{ $payroll->bonus }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Other Taxable</label>
                    <input type="number" step="0.01" name="other_taxable" class="form-control" value="{{ $payroll->other_taxable }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Adjustment</label>
                    <input type="number" step="0.01" name="adjustment" class="form-control" value="{{ $payroll->adjustment }}">
                </div>
            </div>

            <h6 class="border-bottom pb-2 mb-3">Loans & Other Deductions</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="form-label">SSS Loan</label>
                    <input type="number" step="0.01" name="sss_loan" class="form-control" value="{{ $payroll->sss_loan }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">SSS Calamity Loan</label>
                    <input type="number" step="0.01" name="sss_calamity_loan" class="form-control" value="{{ $payroll->sss_calamity_loan }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">HDMF Loan</label>
                    <input type="number" step="0.01" name="hdmf_loan" class="form-control" value="{{ $payroll->hdmf_loan }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">HDMF Calamity Loan</label>
                    <input type="number" step="0.01" name="hdmf_calamity_loan" class="form-control" value="{{ $payroll->hdmf_calamity_loan }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Company Loan</label>
                    <input type="number" step="0.01" name="company_loan" class="form-control" value="{{ $payroll->company_loan }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Other Loans</label>
                    <input type="number" step="0.01" name="other_loans" class="form-control" value="{{ $payroll->other_loans }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Other Deductions</label>
                    <input type="number" step="0.01" name="other_deductions" class="form-control" value="{{ $payroll->other_deductions }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Deduction Date</label>
                    <input type="date" name="other_deductions_date" class="form-control" value="{{ $payroll->other_deductions_date?->format('Y-m-d') ?? $payroll->other_deductions_date }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Deduction Remarks</label>
                    <input type="text" name="other_deductions_remarks" class="form-control" value="{{ $payroll->other_deductions_remarks }}" placeholder="Reason for deduction...">
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-calculator"></i> Recalculate & Save</button>
            <a href="{{ route('payroll.show', $payroll->payroll_period_id) }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
