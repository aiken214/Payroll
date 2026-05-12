@extends('layouts.app')
@section('title', 'Settings')

@section('content')
<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6 class="mb-0">Company Settings</h6>
                <a href="{{ route('settings.contributions') }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-table"></i> Contribution Tables</a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <h6 class="text-muted border-bottom pb-2 mb-3">Company Info</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" name="company_name" class="form-control" value="{{ $settings->company_name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company Address</label>
                            <input type="text" name="company_address" class="form-control" value="{{ $settings->company_address }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">DTI Permit Number</label>
                            <input type="text" name="dti_permit_number" class="form-control" value="{{ $settings->dti_permit_number }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Header Logo</label>
                            <input type="file" name="company_logo" class="form-control" accept="image/png,image/jpeg">
                            @if($settings->company_logo)
                                <small class="text-muted">Current: <img src="{{ asset('storage/' . $settings->company_logo) }}" style="height:30px" class="ms-1"></small>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Watermark Logo</label>
                            <input type="file" name="watermark_logo" class="form-control" accept="image/png,image/jpeg">
                            @if($settings->watermark_logo)
                                <small class="text-muted">Current: <img src="{{ asset('storage/' . $settings->watermark_logo) }}" style="height:30px" class="ms-1"></small>
                            @endif
                        </div>
                    </div>

                    <h6 class="text-muted border-bottom pb-2 mb-3">Work Configuration</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Working Days/Month</label>
                            <input type="number" name="working_days_per_month" class="form-control" value="{{ $settings->working_days_per_month }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Minimum Daily Wage</label>
                            <input type="number" step="0.01" name="minimum_daily_wage" class="form-control" value="{{ $settings->minimum_daily_wage }}" required>
                        </div>
                    </div>

                    <h6 class="text-muted border-bottom pb-2 mb-3">Premium Pay Rates</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Regular OT</label>
                            <input type="number" step="0.0001" name="ot_rate_regular" class="form-control" value="{{ $settings->ot_rate_regular }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Rest Day</label>
                            <input type="number" step="0.0001" name="ot_rate_rest_day" class="form-control" value="{{ $settings->ot_rate_rest_day }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Rest Day OT</label>
                            <input type="number" step="0.0001" name="ot_rate_rest_day_ot" class="form-control" value="{{ $settings->ot_rate_rest_day_ot }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Night Diff Rate</label>
                            <input type="number" step="0.0001" name="night_diff_rate" class="form-control" value="{{ $settings->night_diff_rate }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Special Holiday</label>
                            <input type="number" step="0.0001" name="ot_rate_special_holiday" class="form-control" value="{{ $settings->ot_rate_special_holiday }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Special Holiday OT</label>
                            <input type="number" step="0.0001" name="ot_rate_special_holiday_ot" class="form-control" value="{{ $settings->ot_rate_special_holiday_ot }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Legal Holiday</label>
                            <input type="number" step="0.0001" name="ot_rate_legal_holiday" class="form-control" value="{{ $settings->ot_rate_legal_holiday }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Legal Holiday OT</label>
                            <input type="number" step="0.0001" name="ot_rate_legal_holiday_ot" class="form-control" value="{{ $settings->ot_rate_legal_holiday_ot }}" required>
                        </div>
                    </div>

                    <h6 class="text-muted border-bottom pb-2 mb-3">Pag-IBIG (HDMF)</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Employee Rate</label>
                            <input type="number" step="0.0001" name="hdmf_employee_rate" class="form-control" value="{{ $settings->hdmf_employee_rate }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Employer Rate</label>
                            <input type="number" step="0.0001" name="hdmf_employer_rate" class="form-control" value="{{ $settings->hdmf_employer_rate }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Max Compensation</label>
                            <input type="number" step="0.01" name="hdmf_max_compensation" class="form-control" value="{{ $settings->hdmf_max_compensation }}" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
