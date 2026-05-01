@extends('layouts.app')
@section('title', $payroll->period_label)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <span class="badge bg-{{ ['draft'=>'secondary','processing'=>'warning','approved'=>'success','paid'=>'primary'][$payroll->status] }}">{{ ucfirst($payroll->status) }}</span>
        <span class="text-muted ms-2">{{ $payroll->start_date->format('M d') }} - {{ $payroll->end_date->format('M d, Y') }}</span>
    </div>
    <div class="d-flex gap-2">
        @if($payroll->status === 'draft')
            <form method="POST" action="{{ route('payroll.process', $payroll) }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Process payroll for all active employees?')">
                    <i class="bi bi-play-fill"></i> Process Payroll
                </button>
            </form>
        @endif
        @if($payroll->status === 'processing')
            <form method="POST" action="{{ route('payroll.process', $payroll) }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-warning"><i class="bi bi-arrow-repeat"></i> Re-process</button>
            </form>
            @can('approve payroll')
            <form method="POST" action="{{ route('payroll.approve', $payroll) }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this payroll?')">
                    <i class="bi bi-check-circle"></i> Approve
                </button>
            </form>
            @endcan
        @endif
        <a href="{{ route('payroll.print', $payroll) }}" class="btn btn-sm btn-outline-primary" target="_blank"><i class="bi bi-printer"></i> Print Payroll</a>
        <a href="{{ route('payroll.transmittal', $payroll) }}" class="btn btn-sm btn-outline-secondary" target="_blank"><i class="bi bi-file-text"></i> Transmittal</a>
    </div>
</div>

@if($payroll->payrolls->count())
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0" style="font-size:.85rem">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Department</th>
                        <th class="text-end">Basic Pay</th>
                        <th class="text-end">OT/Holiday</th>
                        <th class="text-end">Gross Pay</th>
                        <th class="text-end">Tardiness</th>
                        <th class="text-end">SSS</th>
                        <th class="text-end">PHIC</th>
                        <th class="text-end">HDMF</th>
                        <th class="text-end">Loans</th>
                        <th class="text-end">W-Tax</th>
                        <th class="text-end">Net Pay</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payroll->payrolls->sortBy('employee.last_name') as $i => $entry)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><strong>{{ $entry->employee->full_name }}</strong></td>
                        <td>{{ $entry->employee->department->name ?? '-' }}</td>
                        <td class="text-end">{{ number_format($entry->basic_pay, 2) }}</td>
                        <td class="text-end">{{ number_format($entry->regular_ot_amount + $entry->rest_day_amount + $entry->special_holiday_amount + $entry->legal_holiday_amount, 2) }}</td>
                        <td class="text-end">{{ number_format($entry->total_gross_pay, 2) }}</td>
                        <td class="text-end text-danger">{{ number_format($entry->total_tardiness, 2) }}</td>
                        <td class="text-end">{{ number_format($entry->sss_contribution, 2) }}</td>
                        <td class="text-end">{{ number_format($entry->phic_contribution, 2) }}</td>
                        <td class="text-end">{{ number_format($entry->hdmf_contribution, 2) }}</td>
                        <td class="text-end">{{ number_format($entry->total_gov_loans + $entry->company_loan + $entry->other_loans, 2) }}</td>
                        <td class="text-end">{{ number_format($entry->withholding_tax, 2) }}</td>
                        <td class="text-end fw-bold">{{ number_format($entry->net_pay, 2) }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('payroll.entry.edit', $entry) }}" class="btn btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                <a href="{{ route('payroll.payslip', $entry) }}" class="btn btn-outline-primary" title="Payslip" target="_blank"><i class="bi bi-file-earmark-text"></i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="3">TOTALS</td>
                        <td class="text-end">{{ number_format($payroll->payrolls->sum('basic_pay'), 2) }}</td>
                        <td class="text-end">{{ number_format($payroll->payrolls->sum('regular_ot_amount') + $payroll->payrolls->sum('rest_day_amount') + $payroll->payrolls->sum('special_holiday_amount') + $payroll->payrolls->sum('legal_holiday_amount'), 2) }}</td>
                        <td class="text-end">{{ number_format($payroll->payrolls->sum('total_gross_pay'), 2) }}</td>
                        <td class="text-end text-danger">{{ number_format($payroll->payrolls->sum('total_tardiness'), 2) }}</td>
                        <td class="text-end">{{ number_format($payroll->payrolls->sum('sss_contribution'), 2) }}</td>
                        <td class="text-end">{{ number_format($payroll->payrolls->sum('phic_contribution'), 2) }}</td>
                        <td class="text-end">{{ number_format($payroll->payrolls->sum('hdmf_contribution'), 2) }}</td>
                        <td class="text-end">{{ number_format($payroll->payrolls->sum('total_gov_loans') + $payroll->payrolls->sum('company_loan') + $payroll->payrolls->sum('other_loans'), 2) }}</td>
                        <td class="text-end">{{ number_format($payroll->payrolls->sum('withholding_tax'), 2) }}</td>
                        <td class="text-end">{{ number_format($payroll->payrolls->sum('net_pay'), 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@else
<div class="card">
    <div class="card-body text-center py-5">
        <i class="bi bi-inbox text-muted" style="font-size:3rem"></i>
        <p class="text-muted mt-2">No payroll entries yet. Click "Process Payroll" to generate entries for all active employees.</p>
    </div>
</div>
@endif
@endsection
