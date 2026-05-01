@extends('layouts.app')
@section('title', 'Payroll Periods')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Payroll Periods</h6>
        <a href="{{ route('payroll.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus"></i> New Period</a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <select name="year" class="form-select form-select-sm">
                    <option value="">All Years</option>
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="month" class="form-select form-select-sm">
                    <option value="">All Months</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                    @endfor
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
                        <th>Period</th>
                        <th>Date Range</th>
                        <th>Employees</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($periods as $period)
                    <tr>
                        <td><strong>{{ $period->period_label }}</strong></td>
                        <td>{{ $period->start_date->format('M d') }} - {{ $period->end_date->format('M d, Y') }}</td>
                        <td>{{ $period->payrolls_count }}</td>
                        <td>
                            @php $badges = ['draft'=>'secondary','processing'=>'warning','approved'=>'success','paid'=>'primary']; @endphp
                            <span class="badge bg-{{ $badges[$period->status] ?? 'secondary' }}">{{ ucfirst($period->status) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('payroll.show', $period) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('payroll.print', $period) }}" class="btn btn-sm btn-outline-secondary" target="_blank"><i class="bi bi-printer"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">No payroll periods found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $periods->withQueryString()->links() }}
    </div>
</div>
@endsection
