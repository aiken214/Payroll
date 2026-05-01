@extends('layouts.app')
@section('title', 'Create Payroll Period')

@section('content')
<div class="card" style="max-width: 500px;">
    <div class="card-header"><h6 class="mb-0">New Payroll Period</h6></div>
    <div class="card-body">
        <form method="POST" action="{{ route('payroll.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Year <span class="text-danger">*</span></label>
                <input type="number" name="year" class="form-control" value="{{ old('year', date('Y')) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Month <span class="text-danger">*</span></label>
                <select name="month" class="form-select" required>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ old('month', date('n')) == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                    @endfor
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Cut-off <span class="text-danger">*</span></label>
                <select name="cut_off" class="form-select" required>
                    <option value="1st" {{ old('cut_off') == '1st' ? 'selected' : '' }}>1st Cut-off (1st - 15th)</option>
                    <option value="2nd" {{ old('cut_off') == '2nd' ? 'selected' : '' }}>2nd Cut-off (16th - End)</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Create</button>
            <a href="{{ route('payroll.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
