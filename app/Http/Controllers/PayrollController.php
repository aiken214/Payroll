<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollPeriod;
use App\Services\PayrollService;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = PayrollPeriod::withCount('payrolls');

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }

        $periods = $query->orderByDesc('year')->orderByDesc('month')->orderByDesc('cut_off')->paginate(15);

        $years = PayrollPeriod::selectRaw('DISTINCT year')->orderByDesc('year')->pluck('year');
        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }

        return view('payroll.index', compact('periods', 'years'));
    }

    public function create()
    {
        return view('payroll.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'cut_off' => 'required|in:1st,2nd',
        ]);

        $existing = PayrollPeriod::where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->where('cut_off', $validated['cut_off'])
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Payroll period already exists.')->withInput();
        }

        if ($validated['cut_off'] === '1st') {
            $startDate = "{$validated['year']}-" . str_pad($validated['month'], 2, '0', STR_PAD_LEFT) . "-01";
            $endDate = "{$validated['year']}-" . str_pad($validated['month'], 2, '0', STR_PAD_LEFT) . "-15";
        } else {
            $startDate = "{$validated['year']}-" . str_pad($validated['month'], 2, '0', STR_PAD_LEFT) . "-16";
            $endDate = date('Y-m-t', strtotime("{$validated['year']}-{$validated['month']}-01"));
        }

        $period = PayrollPeriod::create([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'cut_off' => $validated['cut_off'],
            'month' => $validated['month'],
            'year' => $validated['year'],
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('payroll.show', $period)->with('success', 'Payroll period created.');
    }

    public function show(PayrollPeriod $payroll)
    {
        $payroll->load(['payrolls.employee.department', 'payrolls.employee.position']);
        $employees = Employee::where('is_active', true)
            ->whereNotIn('id', $payroll->payrolls->pluck('employee_id'))
            ->get();

        return view('payroll.show', compact('payroll', 'employees'));
    }

    public function process(Request $request, PayrollPeriod $payroll)
    {
        $service = app(PayrollService::class);

        $employeeData = [];
        if ($request->has('employees')) {
            foreach ($request->employees as $empId => $data) {
                $employeeData[$empId] = array_map(function ($val) {
                    return is_numeric($val) ? (float) $val : $val;
                }, $data);
            }
        }

        $service->processPayroll($payroll, $employeeData);

        return redirect()->route('payroll.show', $payroll)->with('success', 'Payroll processed successfully.');
    }

    public function editEntry(Payroll $payroll)
    {
        $payroll->load(['employee', 'payrollPeriod']);
        return view('payroll.edit-entry', compact('payroll'));
    }

    public function updateEntry(Request $request, Payroll $payroll)
    {
        $service = app(PayrollService::class);

        $data = $request->validate([
            'days_worked' => 'nullable|numeric',
            'basic_pay' => 'nullable|numeric',
            'regular_ot_hrs' => 'nullable|numeric',
            'rest_day_hrs' => 'nullable|numeric',
            'rest_day_ot_hrs' => 'nullable|numeric',
            'special_holiday_hrs' => 'nullable|numeric',
            'special_holiday_ot_hrs' => 'nullable|numeric',
            'legal_holiday_hrs' => 'nullable|numeric',
            'legal_holiday_ot_hrs' => 'nullable|numeric',
            'night_diff_hrs' => 'nullable|numeric',
            'leave_sick_taken' => 'nullable|numeric',
            'leave_vacation_taken' => 'nullable|numeric',
            'cola_amount' => 'nullable|numeric',
            'incentives' => 'nullable|numeric',
            'hazard_pay' => 'nullable|numeric',
            'commission' => 'nullable|numeric',
            'de_minimis' => 'nullable|numeric',
            'allowances' => 'nullable|numeric',
            'bonus' => 'nullable|numeric',
            'other_taxable' => 'nullable|numeric',
            'adjustment' => 'nullable|numeric',
            'late_minutes' => 'nullable|numeric',
            'absent_days' => 'nullable|numeric',
            'sss_loan' => 'nullable|numeric',
            'sss_calamity_loan' => 'nullable|numeric',
            'hdmf_loan' => 'nullable|numeric',
            'hdmf_calamity_loan' => 'nullable|numeric',
            'company_loan' => 'nullable|numeric',
            'other_loans' => 'nullable|numeric',
            'other_deductions' => 'nullable|numeric',
            'other_deductions_remarks' => 'nullable|string|max:500',
            'other_deductions_date' => 'nullable|date',
        ]);

        $data = array_filter($data, fn($val) => $val !== null);
        foreach ($data as $key => $val) {
            if (is_numeric($val) && !in_array($key, ['other_deductions_remarks', 'other_deductions_date'])) {
                $data[$key] = (float) $val;
            }
        }

        $service->processEmployeePayroll($payroll->payrollPeriod, $payroll->employee, $data);

        return redirect()->route('payroll.show', $payroll->payroll_period_id)
            ->with('success', 'Payroll entry updated.');
    }

    public function approve(PayrollPeriod $payroll)
    {
        $payroll->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        return redirect()->route('payroll.show', $payroll)->with('success', 'Payroll approved.');
    }

    public function print(PayrollPeriod $payroll)
    {
        $payroll->load(['payrolls.employee.department', 'payrolls.employee.position']);
        $settings = \App\Models\CompanySetting::first();

        return view('payroll.print', compact('payroll', 'settings'));
    }

    public function payslip(Payroll $payroll)
    {
        $payroll->load(['employee.department', 'employee.position', 'payrollPeriod']);
        $settings = \App\Models\CompanySetting::first();

        return view('payroll.payslip', compact('payroll', 'settings'));
    }

    public function transmittal(PayrollPeriod $payroll)
    {
        $payroll->load(['payrolls.employee.department']);
        $settings = \App\Models\CompanySetting::first();

        return view('payroll.transmittal', compact('payroll', 'settings'));
    }
}
