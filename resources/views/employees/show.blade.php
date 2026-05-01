@extends('layouts.app')
@section('title', $employee->full_name)

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;font-size:2rem;">
                    {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                </div>
                <h5>{{ $employee->full_name }}</h5>
                <p class="text-muted mb-1">{{ $employee->position->title ?? '-' }}</p>
                <p class="text-muted mb-2">{{ $employee->department->name ?? '-' }}</p>
                <span class="badge bg-{{ $employee->is_active ? 'success' : 'secondary' }}">{{ $employee->is_active ? 'Active' : 'Inactive' }}</span>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header"><h6 class="mb-0">Details</h6></div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr><td class="text-muted">Employee #</td><td class="text-end fw-bold">{{ $employee->employee_number }}</td></tr>
                    <tr><td class="text-muted">Status</td><td class="text-end">{{ $employee->employment_status }}</td></tr>
                    <tr><td class="text-muted">Monthly Salary</td><td class="text-end fw-bold">{{ number_format($employee->monthly_salary, 2) }}</td></tr>
                    <tr><td class="text-muted">Semi-Monthly</td><td class="text-end">{{ number_format($employee->semi_monthly_salary, 2) }}</td></tr>
                    <tr><td class="text-muted">Daily Rate</td><td class="text-end">{{ number_format($employee->daily_rate, 2) }}</td></tr>
                    <tr><td class="text-muted">Hourly Rate</td><td class="text-end">{{ number_format($employee->hourly_rate, 2) }}</td></tr>
                    <tr><td class="text-muted">Salary Type</td><td class="text-end">{{ $employee->salary_type }}</td></tr>
                    <tr><td class="text-muted">Date Hired</td><td class="text-end">{{ $employee->date_hired?->format('M d, Y') ?? '-' }}</td></tr>
                </table>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header"><h6 class="mb-0">Government IDs</h6></div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr><td class="text-muted">SSS</td><td class="text-end">{{ $employee->sss_number ?? '-' }}</td></tr>
                    <tr><td class="text-muted">PhilHealth</td><td class="text-end">{{ $employee->phic_number ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Pag-IBIG</td><td class="text-end">{{ $employee->hdmf_number ?? '-' }}</td></tr>
                    <tr><td class="text-muted">TIN</td><td class="text-end">{{ $employee->tin_number ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6 class="mb-0">Payroll History</h6>
                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i> Edit</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr><th>Period</th><th class="text-end">Gross Pay</th><th class="text-end">Deductions</th><th class="text-end">Net Pay</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($employee->payrolls->sortByDesc('created_at')->take(20) as $payroll)
                        <tr>
                            <td>{{ $payroll->payrollPeriod->period_label }}</td>
                            <td class="text-end">{{ number_format($payroll->total_gross_pay, 2) }}</td>
                            <td class="text-end text-danger">{{ number_format($payroll->total_deductions + $payroll->withholding_tax, 2) }}</td>
                            <td class="text-end fw-bold">{{ number_format($payroll->net_pay, 2) }}</td>
                            <td><a href="{{ route('payroll.payslip', $payroll) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-printer"></i></a></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">No payroll records.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($employee->activeLoans->count())
        <div class="card mt-3">
            <div class="card-header"><h6 class="mb-0">Active Loans</h6></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr><th>Type</th><th class="text-end">Total</th><th class="text-end">Monthly</th><th class="text-end">Balance</th></tr>
                    </thead>
                    <tbody>
                        @foreach($employee->activeLoans as $loan)
                        <tr>
                            <td>{{ $loan->loan_type }}</td>
                            <td class="text-end">{{ number_format($loan->total_amount, 2) }}</td>
                            <td class="text-end">{{ number_format($loan->monthly_amortization, 2) }}</td>
                            <td class="text-end fw-bold">{{ number_format($loan->balance, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
