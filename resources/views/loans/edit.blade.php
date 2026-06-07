@extends('layouts.app')
@section('title', 'Edit Loan')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header"><h6 class="mb-0">Edit Loan - {{ $loan->employee->full_name }}</h6></div>
    <div class="card-body">
        <div class="bg-light p-3 rounded mb-3">
            <div class="row text-center">
                <div class="col"><small class="text-muted">Type</small><div class="fw-bold">{{ $loan->loan_type }}</div></div>
                <div class="col"><small class="text-muted">Start Date</small><div class="fw-bold">{{ $loan->start_date->format('M d, Y') }}</div></div>
            </div>
        </div>
        <form method="POST" action="{{ route('loans.update', $loan) }}">
            @csrf @method('PUT')
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Total Amount</label>
                    <input type="number" step="0.01" name="total_amount" class="form-control" value="{{ old('total_amount', $loan->total_amount) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Monthly Amortization</label>
                    <input type="number" step="0.01" name="monthly_amortization" class="form-control" value="{{ old('monthly_amortization', $loan->monthly_amortization) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Balance</label>
                    <input type="number" step="0.01" name="balance" class="form-control" value="{{ old('balance', $loan->balance) }}" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">End Date</label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $loan->end_date?->format('Y-m-d')) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="is_active" class="form-select" required>
                    <option value="1" {{ old('is_active', $loan->is_active) == true ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active', $loan->is_active) == false ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Update</button>
            <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
