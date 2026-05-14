@extends('layouts.app')
@section('title', 'Contribution Tables')

@section('content')
<div class="mb-3">
    <form method="GET" class="d-inline-flex align-items-center gap-2">
        <label class="form-label mb-0">Year:</label>
        <select name="year" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
            @foreach($availableYears as $y)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
    </form>
</div>

<div class="card mb-4">
    <div class="card-header"><h6 class="mb-0">SSS Contribution Table {{ $year }}</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-striped mb-0" style="font-size:.85rem">
                <thead class="table-light">
                    <tr>
                        <th>Range From</th><th>Range To</th>
                        <th class="text-end">ER (SS)</th><th class="text-end">EE (SS)</th>
                        <th class="text-end">ER (EC)</th>
                        <th class="text-end">ER (MPF)</th><th class="text-end">EE (MPF)</th>
                        <th class="text-end">Total ER</th><th class="text-end">Total EE</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sssTable as $row)
                    <tr>
                        <td>{{ number_format($row->range_from, 2) }}</td>
                        <td>{{ number_format($row->range_to, 2) }}</td>
                        <td class="text-end">{{ number_format($row->er_ss, 2) }}</td>
                        <td class="text-end">{{ number_format($row->ee_ss, 2) }}</td>
                        <td class="text-end">{{ number_format($row->er_ec, 2) }}</td>
                        <td class="text-end">{{ number_format($row->er_mpf, 2) }}</td>
                        <td class="text-end">{{ number_format($row->ee_mpf, 2) }}</td>
                        <td class="text-end fw-bold">{{ number_format($row->total_er, 2) }}</td>
                        <td class="text-end fw-bold">{{ number_format($row->total_ee, 2) }}</td>
                        <td class="text-end fw-bold">{{ number_format($row->total_contribution, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center text-muted py-3">No SSS table data. Run the seeder to populate.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">PhilHealth Table {{ $year }}</h6></div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead class="table-light">
                        <tr><th>From</th><th>To</th><th class="text-end">Rate</th><th class="text-end">Premium (EE Share)</th></tr>
                    </thead>
                    <tbody>
                        @forelse($phicTable as $row)
                        <tr>
                            <td>{{ number_format($row->range_from, 2) }}</td>
                            <td>{{ number_format($row->range_to, 2) }}</td>
                            <td class="text-end">{{ $row->premium_rate ? ($row->premium_rate * 100) . '%' : '-' }}</td>
                            <td class="text-end fw-bold">{{ $row->monthly_premium ? number_format($row->monthly_premium, 2) : 'Rate-based' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No PhilHealth table data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Withholding Tax Table (Semi-Monthly)</h6></div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead class="table-light">
                        <tr><th>Min Range</th><th>Max Range</th><th class="text-end">Rate</th><th class="text-end">Base Tax</th></tr>
                    </thead>
                    <tbody>
                        @forelse($wtaxTable->where('frequency', 'semi_monthly') as $row)
                        <tr>
                            <td>{{ number_format($row->min_range, 2) }}</td>
                            <td>{{ $row->max_range ? number_format($row->max_range, 2) : 'Above' }}</td>
                            <td class="text-end">{{ ($row->tax_rate * 100) }}%</td>
                            <td class="text-end">{{ number_format($row->base_tax, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">No tax table data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
