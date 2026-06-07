<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payslip - {{ $payroll->employee->full_name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 10px; color: #333; padding: 15mm; }
        .payslip { max-width: 700px; margin: 0 auto; border: 2px solid #1e3a5f; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #1e3a5f; padding-bottom: 10px; margin-bottom: 12px; }
        .header h1 { font-size: 16px; color: #1e3a5f; }
        .header h2 { font-size: 12px; font-weight: normal; margin-top: 2px; }
        .header .period { font-size: 11px; color: #666; margin-top: 4px; }
        .emp-info { display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid #ddd; }
        .emp-info div { width: 48%; }
        .emp-info table { width: 100%; }
        .emp-info td { padding: 1px 4px; font-size: 9px; }
        .emp-info .label { color: #888; width: 40%; }
        .emp-info .value { font-weight: bold; }
        .section-title { font-size: 10px; font-weight: bold; color: #1e3a5f; padding: 4px 8px; background: #f0f4f8; margin: 8px 0 4px; border-left: 3px solid #1e3a5f; }
        .detail-table { width: 100%; }
        .detail-table td { padding: 2px 8px; font-size: 9px; }
        .detail-table .amount { text-align: right; font-weight: bold; }
        .detail-table .sub { color: #888; font-size: 8px; }
        .total-row { border-top: 2px solid #1e3a5f; margin-top: 8px; padding-top: 8px; }
        .total-row table td { font-size: 12px; font-weight: bold; padding: 4px 8px; }
        .net-pay { font-size: 16px; color: #1e3a5f; }
        .two-col { display: flex; gap: 20px; }
        .two-col > div { flex: 1; }
        .footer { margin-top: 20px; text-align: center; font-size: 8px; color: #999; border-top: 1px solid #ddd; padding-top: 8px; }
        .sig-area { margin-top: 30px; display: flex; justify-content: space-between; }
        .sig-box { width: 45%; text-align: center; }
        .sig-line { border-top: 1px solid #333; margin-top: 30px; padding-top: 4px; font-size: 9px; }
        .watermark { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.06; z-index: -1; pointer-events: none; }
        .header-logo { height: 40px; margin-bottom: 4px; }
        @media print { @page { margin: 10mm; } }
    </style>
</head>
<body>
    @if($settings->watermark_logo)
    <div class="watermark"><img src="{{ asset('storage/' . $settings->watermark_logo) }}" style="width:300px"></div>
    @endif

    <div class="payslip">
        <div class="header">
            @if($settings->company_logo)
            <img src="{{ asset('storage/' . $settings->company_logo) }}" class="header-logo">
            @endif
            <h1>{{ $settings->company_name ?? 'Company' }}</h1>
            @if($settings->company_address)<p style="font-size:9px;color:#666">{{ $settings->company_address }}</p>@endif
            @if($settings->dti_permit_number)<p style="font-size:8px;color:#888">DTI Permit No.: {{ $settings->dti_permit_number }}</p>@endif
            <h2>PAYSLIP</h2>
            <div class="period">{{ $payroll->payrollPeriod->period_label }} | {{ $payroll->payrollPeriod->start_date->format('M d') }} - {{ $payroll->payrollPeriod->end_date->format('M d, Y') }}</div>
        </div>

        <div class="emp-info">
            <div>
                <table>
                    <tr><td class="label">Employee Name</td><td class="value">{{ $payroll->employee->full_name }}</td></tr>
                    <tr><td class="label">Employee #</td><td class="value">{{ $payroll->employee->employee_number }}</td></tr>
                    <tr><td class="label">Department</td><td class="value">{{ $payroll->employee->department->name ?? '-' }}</td></tr>
                    <tr><td class="label">Position</td><td class="value">{{ $payroll->employee->position->title ?? '-' }}</td></tr>
                </table>
            </div>
            <div>
                <table>
                    <tr><td class="label">Monthly Salary</td><td class="value">{{ number_format($payroll->monthly_salary, 2) }}</td></tr>
                    <tr><td class="label">Daily Rate</td><td class="value">{{ number_format($payroll->daily_rate, 2) }}</td></tr>
                    <tr><td class="label">Status</td><td class="value">{{ $payroll->employee->employment_status }}</td></tr>
                    <tr><td class="label">Pay Type</td><td class="value">{{ $payroll->employee->salary_type }}</td></tr>
                </table>
            </div>
        </div>

        <div class="two-col">
            <div>
                <div class="section-title">EARNINGS</div>
                <table class="detail-table">
                    <tr><td>Basic Pay</td><td class="amount">{{ number_format($payroll->basic_pay, 2) }}</td></tr>
                    @if($payroll->regular_ot_amount > 0)
                    <tr><td>Regular Overtime <span class="sub">({{ $payroll->regular_ot_hrs }} hrs)</span></td><td class="amount">{{ number_format($payroll->regular_ot_amount, 2) }}</td></tr>
                    @endif
                    @if($payroll->rest_day_amount > 0)
                    <tr><td>Rest Day <span class="sub">({{ $payroll->rest_day_hrs }} hrs)</span></td><td class="amount">{{ number_format($payroll->rest_day_amount, 2) }}</td></tr>
                    @endif
                    @if($payroll->special_holiday_amount > 0)
                    <tr><td>Special Holiday <span class="sub">({{ $payroll->special_holiday_hrs }} hrs)</span></td><td class="amount">{{ number_format($payroll->special_holiday_amount, 2) }}</td></tr>
                    @endif
                    @if($payroll->legal_holiday_amount > 0)
                    <tr><td>Legal Holiday <span class="sub">({{ $payroll->legal_holiday_hrs }} hrs)</span></td><td class="amount">{{ number_format($payroll->legal_holiday_amount, 2) }}</td></tr>
                    @endif
                    @if($payroll->night_diff_amount > 0)
                    <tr><td>Night Differential <span class="sub">({{ $payroll->night_diff_hrs }} hrs)</span></td><td class="amount">{{ number_format($payroll->night_diff_amount, 2) }}</td></tr>
                    @endif
                    @if($payroll->leave_amount > 0)
                    <tr><td>Leave With Pay</td><td class="amount">{{ number_format($payroll->leave_amount, 2) }}</td></tr>
                    @endif
                    @if($payroll->cola_amount > 0)
                    <tr><td>COLA</td><td class="amount">{{ number_format($payroll->cola_amount, 2) }}</td></tr>
                    @endif
                    @if($payroll->incentives > 0)
                    <tr><td>Incentives</td><td class="amount">{{ number_format($payroll->incentives, 2) }}</td></tr>
                    @endif
                    @if($payroll->hazard_pay > 0)
                    <tr><td>Hazard Pay</td><td class="amount">{{ number_format($payroll->hazard_pay, 2) }}</td></tr>
                    @endif
                    @if($payroll->commission > 0)
                    <tr><td>Commission</td><td class="amount">{{ number_format($payroll->commission, 2) }}</td></tr>
                    @endif
                    @if($payroll->allowances > 0)
                    <tr><td>Allowances</td><td class="amount">{{ number_format($payroll->allowances, 2) }}</td></tr>
                    @endif
                    @if($payroll->bonus > 0)
                    <tr><td>Bonus</td><td class="amount">{{ number_format($payroll->bonus, 2) }}</td></tr>
                    @endif
                    @if($payroll->adjustment != 0)
                    <tr><td>Adjustment</td><td class="amount">{{ number_format($payroll->adjustment, 2) }}</td></tr>
                    @endif
                    <tr style="border-top:1px solid #ccc"><td><strong>Total Gross Pay</strong></td><td class="amount" style="font-size:11px">{{ number_format($payroll->total_gross_pay, 2) }}</td></tr>
                </table>
            </div>

            <div>
                <div class="section-title">DEDUCTIONS</div>
                <table class="detail-table">
                    @if($payroll->late_amount > 0 || $payroll->absent_amount > 0)
                    <tr><td>Late/Absent</td><td class="amount">{{ number_format($payroll->late_amount + $payroll->absent_amount, 2) }}</td></tr>
                    @endif
                    @if($payroll->leave_sick_unpaid_amount > 0)
                    <tr><td>Unpaid Sick Leave <span class="sub">({{ $payroll->leave_sick_unpaid }} days)</span></td><td class="amount">{{ number_format($payroll->leave_sick_unpaid_amount, 2) }}</td></tr>
                    @endif
                    <tr><td>SSS</td><td class="amount">{{ number_format($payroll->sss_contribution, 2) }}</td></tr>
                    <tr><td>PhilHealth</td><td class="amount">{{ number_format($payroll->phic_contribution, 2) }}</td></tr>
                    <tr><td>Pag-IBIG</td><td class="amount">{{ number_format($payroll->hdmf_contribution, 2) }}</td></tr>
                    @if($payroll->sss_loan > 0)
                    <tr><td>SSS Loan</td><td class="amount">{{ number_format($payroll->sss_loan, 2) }}</td></tr>
                    @endif
                    @if($payroll->sss_calamity_loan > 0)
                    <tr><td>SSS Calamity Loan</td><td class="amount">{{ number_format($payroll->sss_calamity_loan, 2) }}</td></tr>
                    @endif
                    @if($payroll->hdmf_loan > 0)
                    <tr><td>HDMF Loan</td><td class="amount">{{ number_format($payroll->hdmf_loan, 2) }}</td></tr>
                    @endif
                    @if($payroll->hdmf_calamity_loan > 0)
                    <tr><td>HDMF Calamity Loan</td><td class="amount">{{ number_format($payroll->hdmf_calamity_loan, 2) }}</td></tr>
                    @endif
                    @if($payroll->company_loan > 0)
                    <tr><td>Company Loan</td><td class="amount">{{ number_format($payroll->company_loan, 2) }}</td></tr>
                    @endif
                    @if($payroll->other_loans > 0)
                    <tr><td>Other Loans</td><td class="amount">{{ number_format($payroll->other_loans, 2) }}</td></tr>
                    @endif
                    @if($payroll->other_deductions > 0)
                    <tr><td>Other Deductions</td><td class="amount">{{ number_format($payroll->other_deductions, 2) }}</td></tr>
                    @endif
                    <tr><td>Withholding Tax</td><td class="amount">{{ number_format($payroll->withholding_tax, 2) }}</td></tr>
                    <tr style="border-top:1px solid #ccc"><td><strong>Total Deductions</strong></td><td class="amount" style="font-size:11px">{{ number_format($payroll->total_deductions + $payroll->withholding_tax, 2) }}</td></tr>
                </table>
            </div>
        </div>

        <div class="total-row">
            <table style="width:100%">
                <tr>
                    <td style="width:60%">NET PAY</td>
                    <td class="net-pay" style="text-align:right">PHP {{ number_format($payroll->net_pay, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="sig-area">
            <div class="sig-box">
                <div class="sig-line">Employee Signature</div>
            </div>
            <div class="sig-box">
                <div class="sig-line">Authorized Signature</div>
            </div>
        </div>

        <div class="footer">
            This is a system-generated payslip. | {{ $settings->company_name ?? 'Company' }} Payroll System
        </div>
    </div>

    <script>window.onload = function() { window.print(); }</script>
</body>
</html>
