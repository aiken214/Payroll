@extends('layouts.app')
@section('title', 'Loans')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Employee Loans</h6>
        <a href="{{ route('loans.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus"></i> Add Loan</a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <select name="employee_id" class="form-select form-select-sm">
                    <option value="">All Employees</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="loan_type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    @foreach(['SSS Salary','SSS Calamity','HDMF Salary','HDMF Calamity','Company','Other'] as $type)
                        <option value="{{ $type }}" {{ request('loan_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-outline-primary w-100">Filter</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Employee</th>
                        <th>Type</th>
                        <th class="text-end">Total Amount</th>
                        <th class="text-end">Monthly</th>
                        <th class="text-end">Balance</th>
                        <th>Start Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                    <tr>
                        <td><strong>{{ $loan->employee->full_name }}</strong></td>
                        <td>{{ $loan->loan_type }}</td>
                        <td class="text-end">{{ number_format($loan->total_amount, 2) }}</td>
                        <td class="text-end">{{ number_format($loan->monthly_amortization, 2) }}</td>
                        <td class="text-end fw-bold">{{ number_format($loan->balance, 2) }}</td>
                        <td>{{ $loan->start_date->format('M d, Y') }}</td>
                        <td><span class="badge bg-{{ $loan->is_active ? 'success' : 'secondary' }}">{{ $loan->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td>
                            <a href="{{ route('loans.edit', $loan) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('loans.destroy', $loan) }}" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this loan?')"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-3">No loans found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $loans->withQueryString()->links() }}
    </div>
</div>
@endsection
