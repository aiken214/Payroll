<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['department', 'position']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $employees = $query->orderBy('last_name')->paginate(15);
        $departments = Department::orderBy('name')->get();

        return view('employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $positions = Position::orderBy('title')->get();
        return view('employees.create', compact('departments', 'positions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_number' => 'required|unique:employees',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'employment_status' => 'required|in:Permanent/Regular,Probationary,Contractual,Part-time',
            'monthly_salary' => 'required|numeric|min:0',
            'salary_type' => 'required|in:ATM,CASH',
            'atm_number' => 'nullable|string',
            'sss_number' => 'nullable|string',
            'phic_number' => 'nullable|string',
            'hdmf_number' => 'nullable|string',
            'tin_number' => 'nullable|string',
            'date_hired' => 'nullable|date',
        ]);

        $settings = \App\Models\CompanySetting::first();
        $minWage = $settings ? $settings->minimum_daily_wage : 610;
        $workingDays = $settings ? $settings->working_days_per_month : 26;
        $validated['is_minimum_wage_earner'] = ($validated['monthly_salary'] / $workingDays) <= $minWage;

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        $employee->load(['department', 'position', 'payrolls.payrollPeriod', 'activeLoans']);
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $departments = Department::orderBy('name')->get();
        $positions = Position::orderBy('title')->get();
        return view('employees.edit', compact('employee', 'departments', 'positions'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'employee_number' => 'required|unique:employees,employee_number,' . $employee->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'employment_status' => 'required|in:Permanent/Regular,Probationary,Contractual,Part-time',
            'monthly_salary' => 'required|numeric|min:0',
            'salary_type' => 'required|in:ATM,CASH',
            'atm_number' => 'nullable|string',
            'sss_number' => 'nullable|string',
            'phic_number' => 'nullable|string',
            'hdmf_number' => 'nullable|string',
            'tin_number' => 'nullable|string',
            'is_active' => 'boolean',
            'date_hired' => 'nullable|date',
        ]);

        $settings = \App\Models\CompanySetting::first();
        $minWage = $settings ? $settings->minimum_daily_wage : 610;
        $workingDays = $settings ? $settings->working_days_per_month : 26;
        $validated['is_minimum_wage_earner'] = ($validated['monthly_salary'] / $workingDays) <= $minWage;
        $validated['is_active'] = $request->has('is_active');

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
