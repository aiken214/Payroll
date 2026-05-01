<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transmittal - {{ $payroll->period_label }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 10px; color: #333; padding: 15mm; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #1e3a5f; padding-bottom: 10px; }
        .header h1 { font-size: 14px; color: #1e3a5f; }
        .header h2 { font-size: 12px; font-weight: normal; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ccc; padding: 3px 6px; }
        th { background: #1e3a5f; color: #fff; font-size: 8px; text-transform: uppercase; }
        td { font-size: 9px; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .totals td { background: #f0f2f5; font-weight: bold; }
        .sig-area { margin-top: 40px; display: flex; justify-content: space-between; }
        .sig-box { width: 30%; text-align: center; }
        .sig-line { border-top: 1px solid #333; margin-top: 40px; padding-top: 4px; font-size: 9px; }
        @media print { @page { margin: 10mm; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $settings->company_name ?? 'Company' }}</h1>
        <h2>PAYROLL TRANSMITTAL</h2>
        <p>{{ $payroll->period_label }} | {{ $payroll->start_date->format('M d') }} - {{ $payroll->end_date->format('M d, Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Employee Name</th>
                <th>Department</th>
                <th>Net Pay</th>
                <th>Pay Type</th>
                <th>ATM #</th>
                <th>Signature</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payroll->payrolls->sortBy('employee.last_name') as $i => $entry)
            <tr>
                <td style="text-align:center">{{ $i + 1 }}</td>
                <td>{{ $entry->employee->full_name }}</td>
                <td>{{ $entry->employee->department->name ?? '' }}</td>
                <td class="text-end fw-bold">{{ number_format($entry->net_pay, 2) }}</td>
                <td style="text-align:center">{{ $entry->employee->salary_type }}</td>
                <td>{{ $entry->employee->atm_number ?? '' }}</td>
                <td style="width:120px"></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="totals">
                <td colspan="3" class="text-end">TOTAL</td>
                <td class="text-end">{{ number_format($payroll->payrolls->sum('net_pay'), 2) }}</td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>

    <div class="sig-area">
        <div class="sig-box"><div class="sig-line">Prepared By</div></div>
        <div class="sig-box"><div class="sig-line">Reviewed By</div></div>
        <div class="sig-box"><div class="sig-line">Approved By</div></div>
    </div>

    <script>window.onload = function() { window.print(); }</script>
</body>
</html>
