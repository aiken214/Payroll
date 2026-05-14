<?php

namespace App\Services;

use App\Models\CompanySetting;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollPeriod;

class PayrollService
{
    protected ContributionService $contributionService;
    protected CompanySetting $settings;

    public function __construct(ContributionService $contributionService)
    {
        $this->contributionService = $contributionService;
        $this->settings = CompanySetting::firstOrCreate([], ['company_name' => 'Company']);
    }

    public function processPayroll(PayrollPeriod $period, array $employeeData = []): void
    {
        $employees = Employee::where('is_active', true)->get();
        $activeIds = $employees->pluck('id');

        Payroll::where('payroll_period_id', $period->id)
            ->whereNotIn('employee_id', $activeIds)
            ->delete();

        foreach ($employees as $employee) {
            $data = $employeeData[$employee->id] ?? [];
            $this->processEmployeePayroll($period, $employee, $data);
        }

        $period->update(['status' => 'processing']);
    }

    public function processEmployeePayroll(PayrollPeriod $period, Employee $employee, array $data = []): Payroll
    {
        $workingDays = $this->settings->working_days_per_month;
        $monthlySalary = $employee->monthly_salary;
        $semiMonthlySalary = $monthlySalary / 2;
        $dailyRate = $monthlySalary / $workingDays;
        $hourlyRate = $dailyRate / 8;

        $daysWorked = $data['days_worked'] ?? ($workingDays / 2);
        $hoursWorked = $data['hours_worked'] ?? ($daysWorked * 8);
        $basicPay = $data['basic_pay'] ?? $semiMonthlySalary;

        // Overtime
        $regularOtHrs = $data['regular_ot_hrs'] ?? 0;
        $regularOtAmount = $regularOtHrs * $hourlyRate * $this->settings->ot_rate_regular;

        // Rest Day
        $restDayHrs = $data['rest_day_hrs'] ?? 0;
        $restDayAmount = $restDayHrs * $hourlyRate * $this->settings->ot_rate_rest_day;
        $restDayOtHrs = $data['rest_day_ot_hrs'] ?? 0;
        $restDayOtAmount = $restDayOtHrs * $hourlyRate * $this->settings->ot_rate_rest_day_ot;

        // Holidays
        $specialHolidayHrs = $data['special_holiday_hrs'] ?? 0;
        $specialHolidayAmount = $specialHolidayHrs * $hourlyRate * $this->settings->ot_rate_special_holiday;
        $specialHolidayOtHrs = $data['special_holiday_ot_hrs'] ?? 0;
        $specialHolidayOtAmount = $specialHolidayOtHrs * $hourlyRate * $this->settings->ot_rate_special_holiday_ot;
        $legalHolidayHrs = $data['legal_holiday_hrs'] ?? 0;
        $legalHolidayAmount = $legalHolidayHrs * $hourlyRate * $this->settings->ot_rate_legal_holiday;
        $legalHolidayOtHrs = $data['legal_holiday_ot_hrs'] ?? 0;
        $legalHolidayOtAmount = $legalHolidayOtHrs * $hourlyRate * $this->settings->ot_rate_legal_holiday_ot;

        // Night Differential
        $nightDiffHrs = $data['night_diff_hrs'] ?? 0;
        $nightDiffAmount = $nightDiffHrs * $hourlyRate * $this->settings->night_diff_rate;

        // Leave
        $leaveSickTaken = $data['leave_sick_taken'] ?? 0;
        $leaveVacationTaken = $data['leave_vacation_taken'] ?? 0;
        $leaveAmount = ($leaveSickTaken + $leaveVacationTaken) * $dailyRate;

        // Allowances & Additions
        $colaAmount = $data['cola_amount'] ?? 0;
        $incentives = $data['incentives'] ?? 0;
        $hazardPay = $data['hazard_pay'] ?? 0;
        $commission = $data['commission'] ?? 0;
        $deMinimis = $data['de_minimis'] ?? 0;
        $allowances = $data['allowances'] ?? 0;
        $bonus = $data['bonus'] ?? 0;
        $otherTaxable = $data['other_taxable'] ?? 0;
        $adjustment = $data['adjustment'] ?? 0;

        // Total Gross
        $totalGrossPay = $basicPay
            + $regularOtAmount + $restDayAmount + $restDayOtAmount
            + $specialHolidayAmount + $specialHolidayOtAmount
            + $legalHolidayAmount + $legalHolidayOtAmount
            + $nightDiffAmount + $leaveAmount
            + $colaAmount + $incentives + $hazardPay + $commission
            + $deMinimis + $allowances + $bonus + $otherTaxable + $adjustment;

        // Tardiness
        $lateMinutes = $data['late_minutes'] ?? 0;
        $lateHours = $lateMinutes / 60;
        $lateAmount = $lateHours * $hourlyRate;
        $absentDays = $data['absent_days'] ?? 0;
        $absentAmount = $absentDays * $dailyRate;
        $totalTardiness = $lateAmount + $absentAmount;

        // Government Contributions (half of monthly - per cut-off)
        $sssContribution = $this->contributionService->computeSss($monthlySalary) / 2;
        $phicContribution = $this->contributionService->computePhic($monthlySalary) / 2;
        $hdmfContribution = $this->contributionService->computeHdmf($monthlySalary) / 2;
        $totalContributions = $sssContribution + $phicContribution + $hdmfContribution;

        // Loans (semi-monthly amortization)
        $sssLoan = $data['sss_loan'] ?? $this->getActiveLoanAmount($employee, 'SSS Salary');
        $sssCalamityLoan = $data['sss_calamity_loan'] ?? $this->getActiveLoanAmount($employee, 'SSS Calamity');
        $hdmfLoan = $data['hdmf_loan'] ?? $this->getActiveLoanAmount($employee, 'HDMF Salary');
        $hdmfCalamityLoan = $data['hdmf_calamity_loan'] ?? $this->getActiveLoanAmount($employee, 'HDMF Calamity');
        $totalGovLoans = $sssLoan + $sssCalamityLoan + $hdmfLoan + $hdmfCalamityLoan;

        $companyLoan = $data['company_loan'] ?? $this->getActiveLoanAmount($employee, 'Company');
        $otherLoans = $data['other_loans'] ?? $this->getActiveLoanAmount($employee, 'Other');
        $otherDeductions = $data['other_deductions'] ?? 0;
        $otherDeductionsRemarks = $data['other_deductions_remarks'] ?? null;
        $otherDeductionsDate = $data['other_deductions_date'] ?? null;

        $totalDeductions = $totalTardiness + $totalContributions + $totalGovLoans
            + $companyLoan + $otherLoans + $otherDeductions;

        $amountDue = $totalGrossPay - $totalDeductions;

        // Taxable Income: gross minus non-taxable items and contributions
        $nonTaxable = $deMinimis + $colaAmount + $allowances;
        $taxableIncome = $totalGrossPay - $totalTardiness - $totalContributions - $nonTaxable;

        // Withholding Tax
        $withholdingTax = 0;
        if (!$employee->is_minimum_wage_earner && $taxableIncome > 0) {
            $withholdingTax = $this->contributionService->computeWtax($taxableIncome, 'semi_monthly');
        }

        $netPay = $amountDue - $withholdingTax;

        // 13th Month (accumulated per cut-off)
        $thirteenthMonth = $basicPay / 12;

        return Payroll::updateOrCreate(
            [
                'payroll_period_id' => $period->id,
                'employee_id' => $employee->id,
            ],
            [
                'monthly_salary' => $monthlySalary,
                'semi_monthly_salary' => $semiMonthlySalary,
                'daily_rate' => round($dailyRate, 2),
                'hourly_rate' => round($hourlyRate, 4),
                'days_worked' => $daysWorked,
                'hours_worked' => $hoursWorked,
                'basic_pay' => round($basicPay, 2),
                'regular_ot_hrs' => $regularOtHrs,
                'regular_ot_amount' => round($regularOtAmount, 2),
                'rest_day_hrs' => $restDayHrs,
                'rest_day_amount' => round($restDayAmount, 2),
                'rest_day_ot_hrs' => $restDayOtHrs,
                'rest_day_ot_amount' => round($restDayOtAmount, 2),
                'special_holiday_hrs' => $specialHolidayHrs,
                'special_holiday_amount' => round($specialHolidayAmount, 2),
                'special_holiday_ot_hrs' => $specialHolidayOtHrs,
                'special_holiday_ot_amount' => round($specialHolidayOtAmount, 2),
                'legal_holiday_hrs' => $legalHolidayHrs,
                'legal_holiday_amount' => round($legalHolidayAmount, 2),
                'legal_holiday_ot_hrs' => $legalHolidayOtHrs,
                'legal_holiday_ot_amount' => round($legalHolidayOtAmount, 2),
                'night_diff_hrs' => $nightDiffHrs,
                'night_diff_amount' => round($nightDiffAmount, 2),
                'leave_sick_taken' => $leaveSickTaken,
                'leave_vacation_taken' => $leaveVacationTaken,
                'leave_amount' => round($leaveAmount, 2),
                'cola_amount' => round($colaAmount, 2),
                'incentives' => round($incentives, 2),
                'hazard_pay' => round($hazardPay, 2),
                'commission' => round($commission, 2),
                'de_minimis' => round($deMinimis, 2),
                'allowances' => round($allowances, 2),
                'bonus' => round($bonus, 2),
                'other_taxable' => round($otherTaxable, 2),
                'adjustment' => round($adjustment, 2),
                'total_gross_pay' => round($totalGrossPay, 2),
                'late_minutes' => $lateMinutes,
                'late_hours' => round($lateHours, 4),
                'late_amount' => round($lateAmount, 2),
                'absent_days' => $absentDays,
                'absent_amount' => round($absentAmount, 2),
                'total_tardiness' => round($totalTardiness, 2),
                'sss_contribution' => round($sssContribution, 2),
                'phic_contribution' => round($phicContribution, 2),
                'hdmf_contribution' => round($hdmfContribution, 2),
                'total_contributions' => round($totalContributions, 2),
                'sss_loan' => round($sssLoan, 2),
                'sss_calamity_loan' => round($sssCalamityLoan, 2),
                'hdmf_loan' => round($hdmfLoan, 2),
                'hdmf_calamity_loan' => round($hdmfCalamityLoan, 2),
                'total_gov_loans' => round($totalGovLoans, 2),
                'company_loan' => round($companyLoan, 2),
                'other_loans' => round($otherLoans, 2),
                'other_deductions' => round($otherDeductions, 2),
                'other_deductions_remarks' => $otherDeductionsRemarks,
                'other_deductions_date' => $otherDeductionsDate,
                'total_deductions' => round($totalDeductions, 2),
                'amount_due' => round($amountDue, 2),
                'taxable_income' => round($taxableIncome, 2),
                'withholding_tax' => round($withholdingTax, 2),
                'net_pay' => round($netPay, 2),
                'thirteenth_month' => round($thirteenthMonth, 2),
            ]
        );
    }

    protected function getActiveLoanAmount(Employee $employee, string $loanType): float
    {
        $loan = $employee->activeLoans()->where('loan_type', $loanType)->first();
        if (!$loan) {
            return 0;
        }
        return min($loan->semi_monthly_amortization, $loan->balance);
    }
}
