@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card primary p-3">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="text-muted small">Active Employees</div>
                    <div class="fs-3 fw-bold">{{ number_format($totalEmployees) }}</div>
                </div>
                <div class="text-primary fs-1 opacity-25"><i class="bi bi-people"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card success p-3">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="text-muted small">Payroll Periods</div>
                    <div class="fs-3 fw-bold">{{ number_format($totalPayrollPeriods) }}</div>
                </div>
                <div class="text-success fs-1 opacity-25"><i class="bi bi-calendar-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card warning p-3">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="text-muted small">Latest Net Pay Total</div>
                    <div class="fs-3 fw-bold">{{ number_format($totalNetPay, 2) }}</div>
                </div>
                <div class="text-warning fs-1 opacity-25"><i class="bi bi-cash"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card info p-3">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="text-muted small">Latest Period</div>
                    <div class="fs-5 fw-bold">{{ $latestPeriod ? $latestPeriod->period_label : 'None' }}</div>
                </div>
                <div class="text-info fs-1 opacity-25"><i class="bi bi-clock-history"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Recent Payroll Periods</h6>
                <a href="{{ route('payroll.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus"></i> New Period
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Period</th>
                            <th>Date Range</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPeriods as $period)
                        <tr>
                            <td>{{ $period->period_label }}</td>
                            <td>{{ $period->start_date->format('M d') }} - {{ $period->end_date->format('M d, Y') }}</td>
                            <td>
                                @php
                                    $badges = ['draft' => 'secondary', 'processing' => 'warning', 'approved' => 'success', 'paid' => 'primary'];
                                @endphp
                                <span class="badge bg-{{ $badges[$period->status] ?? 'secondary' }}">{{ ucfirst($period->status) }}</span>
                            </td>
                            <td><a href="{{ route('payroll.show', $period) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">No payroll periods yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Quick Actions</h6></div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('payroll.create') }}" class="btn btn-outline-primary text-start">
                    <i class="bi bi-plus-circle"></i> Create Payroll Period
                </a>
                <a href="{{ route('employees.create') }}" class="btn btn-outline-primary text-start">
                    <i class="bi bi-person-plus"></i> Add Employee
                </a>
                <a href="{{ route('loans.create') }}" class="btn btn-outline-primary text-start">
                    <i class="bi bi-credit-card"></i> Add Loan
                </a>
                @role('Admin')
                <a href="{{ route('settings.contributions') }}" class="btn btn-outline-primary text-start">
                    <i class="bi bi-table"></i> Contribution Tables
                </a>
                @endrole
            </div>
        </div>
    </div>
</div>
@endsection
