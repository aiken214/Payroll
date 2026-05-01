@extends('layouts.app')
@section('title', 'Add Loan')

@section('content')
<div class="card" style="max-width: 600px;">
    <div class="card-header"><h6 class="mb-0">New Loan</h6></div>
    <div class="card-body">
        <form method="POST" action="{{ route('loans.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Employee <span class="text-danger">*</span></label>
                <select name="employee_id" class="form-select" required>
                    <option value="">Select employee...</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->full_name }} ({{ $emp->employee_number }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Loan Type <span class="text-danger">*</span></label>
                <select name="loan_type" class="form-select" required>
                    @foreach(['SSS Salary','SSS Calamity','HDMF Salary','HDMF Calamity','Company','Other'] as $type)
                        <option value="{{ $type }}" {{ old('loan_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <input type="text" name="description" class="form-control" value="{{ old('description') }}">
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Total Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="total_amount" class="form-control" value="{{ old('total_amount') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Monthly Amortization <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="monthly_amortization" class="form-control" value="{{ old('monthly_amortization') }}" required>
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save</button>
            <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
