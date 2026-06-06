<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeLoan;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $query = EmployeeLoan::with('employee');

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        if ($request->filled('loan_type')) {
            $query->where('loan_type', $request->loan_type);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $loans = $query->orderByDesc('created_at')->paginate(15);
        $employees = Employee::where('is_active', true)->orderBy('last_name')->get();

        return view('loans.index', compact('loans', 'employees'));
    }

    public function create()
    {
        $employees = Employee::where('is_active', true)->orderBy('last_name')->get();
        return view('loans.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'loan_type' => 'required|in:SSS Salary,SSS Calamity,HDMF Salary,HDMF Calamity,Company,Other',
            'description' => 'nullable|string',
            'total_amount' => 'required|numeric|min:0',
            'monthly_amortization' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $validated['balance'] = $validated['total_amount'];
        $validated['is_active'] = true;

        EmployeeLoan::create($validated);

        return redirect()->route('loans.index')->with('success', 'Loan created successfully.');
    }

    public function edit(EmployeeLoan $loan)
    {
        $employees = Employee::where('is_active', true)->orderBy('last_name')->get();
        return view('loans.edit', compact('loan', 'employees'));
    }

    public function update(Request $request, EmployeeLoan $loan)
    {
        $validated = $request->validate([
            'monthly_amortization' => 'required|numeric|min:0',
            'balance' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'end_date' => 'nullable|date',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $loan->update($validated);

        return redirect()->route('loans.index')->with('success', 'Loan updated.');
    }

    public function destroy(EmployeeLoan $loan)
    {
        $loan->delete();
        return redirect()->route('loans.index')->with('success', 'Loan deleted.');
    }
}
