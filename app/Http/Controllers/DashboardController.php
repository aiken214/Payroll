<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PayrollPeriod;
use App\Models\Payroll;

class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = Employee::where('is_active', true)->count();
        $totalPayrollPeriods = PayrollPeriod::count();
        $latestPeriod = PayrollPeriod::latest()->first();
        $totalNetPay = $latestPeriod
            ? Payroll::where('payroll_period_id', $latestPeriod->id)->sum('net_pay')
            : 0;
        $recentPeriods = PayrollPeriod::latest()->take(5)->get();

        return view('dashboard', compact(
            'totalEmployees', 'totalPayrollPeriods', 'totalNetPay', 'latestPeriod', 'recentPeriods'
        ));
    }
}
