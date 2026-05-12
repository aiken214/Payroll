<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payroll Register - {{ $payroll->period_label }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 9px; color: #333; padding: 10mm; }
        .header { text-align: center; margin-bottom: 8px; border-bottom: 2px solid #1e3a5f; padding-bottom: 8px; }
        .header h1 { font-size: 14px; color: #1e3a5f; margin-bottom: 2px; }
        .header h2 { font-size: 11px; font-weight: normal; }
        .header p { font-size: 9px; color: #666; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 2px 4px; }
        th { background: #1e3a5f; color: #fff; font-size: 7px; text-transform: uppercase; text-align: center; }
        td { font-size: 8px; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        .totals td { background: #f0f2f5; font-weight: bold; border-top: 2px solid #1e3a5f; }
        .signatures { margin-top: 30px; display: flex; justify-content: space-between; }
        .sig-box { text-align: center; width: 30%; display: inline-block; }
        .sig-line { border-top: 1px solid #333; margin-top: 40px; padding-top: 4px; font-size: 9px; }
        @media print {
            body { padding: 5mm; }
            @page { size: landscape; margin: 5mm; }
        }
    </style>
</head>
<body>
    <div class="header">
        @if($settings->company_logo)
        <img src="{{ asset('storage/' . $settings->company_logo) }}" style="height:35px;margin-bottom:4px">
        @endif
        <h1>{{ $settings->company_name ?? 'Company' }}</h1>
        @if($settings->company_address)<p>{{ $settings->company_address }}</p>@endif
        @if($settings->dti_permit_number)<p style="font-size:8px;color:#888">DTI Permit No.: {{ $settings->dti_permit_number }}</p>@endif
        <h2>PAYROLL REGISTER</h2>
        <p>{{ $payroll->period_label }} | {{ $payroll->start_date->format('F d') }} - {{ $payroll->end_date->format('F d, Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Employee Name</th>
                <th>Department</th>
                <th>Position</th>
                <th>Monthly Salary</th>
                <th>Basic Pay</th>
                <th>OT</th>
                <th>Holiday</th>
                <th>Night Diff</th>
                <th>Allowances</th>
                <th>Gross Pay</th>
                <th>Tardiness</th>
                <th>SSS</th>
                <th>PHIC</th>
                <th>HDMF</th>
                <th>Loans</th>
                <th>Other Ded.</th>
                <th>W-Tax</th>
                <th>Total Ded.</th>
                <th>Net Pay</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payroll->payrolls->sortBy('employee.last_name') as $i => $entry)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $entry->employee->full_name }}</td>
                <td>{{ $entry->employee->department->name ?? '' }}</td>
                <td>{{ $entry->employee->position->title ?? '' }}</td>
                <td class="text-end">{{ number_format($entry->monthly_salary, 2) }}</td>
                <td class="text-end">{{ number_format($entry->basic_pay, 2) }}</td>
                <td class="text-end">{{ number_format($entry->regular_ot_amount, 2) }}</td>
                <td class="text-end">{{ number_format($entry->special_holiday_amount + $entry->legal_holiday_amount + $entry->rest_day_amount, 2) }}</td>
                <td class="text-end">{{ number_format($entry->night_diff_amount, 2) }}</td>
                <td class="text-end">{{ number_format($entry->cola_amount + $entry->allowances + $entry->incentives + $entry->hazard_pay, 2) }}</td>
                <td class="text-end fw-bold">{{ number_format($entry->total_gross_pay, 2) }}</td>
                <td class="text-end">{{ number_format($entry->total_tardiness, 2) }}</td>
                <td class="text-end">{{ number_format($entry->sss_contribution, 2) }}</td>
                <td class="text-end">{{ number_format($entry->phic_contribution, 2) }}</td>
                <td class="text-end">{{ number_format($entry->hdmf_contribution, 2) }}</td>
                <td class="text-end">{{ number_format($entry->total_gov_loans + $entry->company_loan + $entry->other_loans, 2) }}</td>
                <td class="text-end">{{ number_format($entry->other_deductions, 2) }}</td>
                <td class="text-end">{{ number_format($entry->withholding_tax, 2) }}</td>
                <td class="text-end">{{ number_format($entry->total_deductions + $entry->withholding_tax, 2) }}</td>
                <td class="text-end fw-bold">{{ number_format($entry->net_pay, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="totals">
                <td colspan="5" class="text-end">TOTALS</td>
                <td class="text-end">{{ number_format($payroll->payrolls->sum('basic_pay'), 2) }}</td>
                <td class="text-end">{{ number_format($payroll->payrolls->sum('regular_ot_amount'), 2) }}</td>
                <td class="text-end">{{ number_format($payroll->payrolls->sum('special_holiday_amount') + $payroll->payrolls->sum('legal_holiday_amount') + $payroll->payrolls->sum('rest_day_amount'), 2) }}</td>
                <td class="text-end">{{ number_format($payroll->payrolls->sum('night_diff_amount'), 2) }}</td>
                <td class="text-end">{{ number_format($payroll->payrolls->sum('cola_amount') + $payroll->payrolls->sum('allowances') + $payroll->payrolls->sum('incentives') + $payroll->payrolls->sum('hazard_pay'), 2) }}</td>
                <td class="text-end">{{ number_format($payroll->payrolls->sum('total_gross_pay'), 2) }}</td>
                <td class="text-end">{{ number_format($payroll->payrolls->sum('total_tardiness'), 2) }}</td>
                <td class="text-end">{{ number_format($payroll->payrolls->sum('sss_contribution'), 2) }}</td>
                <td class="text-end">{{ number_format($payroll->payrolls->sum('phic_contribution'), 2) }}</td>
                <td class="text-end">{{ number_format($payroll->payrolls->sum('hdmf_contribution'), 2) }}</td>
                <td class="text-end">{{ number_format($payroll->payrolls->sum('total_gov_loans') + $payroll->payrolls->sum('company_loan') + $payroll->payrolls->sum('other_loans'), 2) }}</td>
                <td class="text-end">{{ number_format($payroll->payrolls->sum('other_deductions'), 2) }}</td>
                <td class="text-end">{{ number_format($payroll->payrolls->sum('withholding_tax'), 2) }}</td>
                <td class="text-end">{{ number_format($payroll->payrolls->sum('total_deductions') + $payroll->payrolls->sum('withholding_tax'), 2) }}</td>
                <td class="text-end">{{ number_format($payroll->payrolls->sum('net_pay'), 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="signatures" style="margin-top:40px">
        <div class="sig-box">
            <div class="sig-line">Prepared By</div>
        </div>
        <div class="sig-box">
            <div class="sig-line">Reviewed By</div>
        </div>
        <div class="sig-box">
            <div class="sig-line">Approved By</div>
        </div>
    </div>

    <script>window.onload = function() { window.print(); }</script>
</body>
</html>
