@extends('layouts.app')
@section('title', 'Employees')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Employee List</h6>
        @can('create employees')
        <a href="{{ route('employees.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus"></i> Add Employee</a>
        @endcan
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name or ID..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="department_id" class="form-select form-select-sm">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
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
                        <th>Employee #</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Monthly Salary</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $emp)
                    <tr>
                        <td>{{ $emp->employee_number }}</td>
                        <td><strong>{{ $emp->full_name }}</strong></td>
                        <td>{{ $emp->department->name ?? '-' }}</td>
                        <td>{{ $emp->position->title ?? '-' }}</td>
                        <td class="text-end">{{ number_format($emp->monthly_salary, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $emp->is_active ? 'success' : 'secondary' }}">
                                {{ $emp->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('employees.show', $emp) }}" class="btn btn-sm btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('employees.edit', $emp) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-3">No employees found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $employees->withQueryString()->links() }}
    </div>
</div>
@endsection
