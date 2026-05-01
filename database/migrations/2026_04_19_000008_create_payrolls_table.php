<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_period_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();

            // Basic Pay
            $table->decimal('monthly_salary', 12, 2)->default(0);
            $table->decimal('semi_monthly_salary', 12, 2)->default(0);
            $table->decimal('daily_rate', 10, 2)->default(0);
            $table->decimal('hourly_rate', 10, 4)->default(0);
            $table->decimal('days_worked', 6, 2)->default(0);
            $table->decimal('hours_worked', 6, 2)->default(0);
            $table->decimal('basic_pay', 12, 2)->default(0);

            // Overtime
            $table->decimal('regular_ot_hrs', 6, 2)->default(0);
            $table->decimal('regular_ot_amount', 12, 2)->default(0);

            // Rest Day
            $table->decimal('rest_day_hrs', 6, 2)->default(0);
            $table->decimal('rest_day_amount', 12, 2)->default(0);
            $table->decimal('rest_day_ot_hrs', 6, 2)->default(0);
            $table->decimal('rest_day_ot_amount', 12, 2)->default(0);

            // Holiday
            $table->decimal('special_holiday_hrs', 6, 2)->default(0);
            $table->decimal('special_holiday_amount', 12, 2)->default(0);
            $table->decimal('special_holiday_ot_hrs', 6, 2)->default(0);
            $table->decimal('special_holiday_ot_amount', 12, 2)->default(0);
            $table->decimal('legal_holiday_hrs', 6, 2)->default(0);
            $table->decimal('legal_holiday_amount', 12, 2)->default(0);
            $table->decimal('legal_holiday_ot_hrs', 6, 2)->default(0);
            $table->decimal('legal_holiday_ot_amount', 12, 2)->default(0);

            // Night Differential
            $table->decimal('night_diff_hrs', 6, 2)->default(0);
            $table->decimal('night_diff_amount', 12, 2)->default(0);

            // Leave
            $table->decimal('leave_sick_taken', 6, 2)->default(0);
            $table->decimal('leave_vacation_taken', 6, 2)->default(0);
            $table->decimal('leave_amount', 12, 2)->default(0);

            // Allowances & Additions
            $table->decimal('cola_amount', 12, 2)->default(0);
            $table->decimal('incentives', 12, 2)->default(0);
            $table->decimal('hazard_pay', 12, 2)->default(0);
            $table->decimal('commission', 12, 2)->default(0);
            $table->decimal('de_minimis', 12, 2)->default(0);
            $table->decimal('allowances', 12, 2)->default(0);
            $table->decimal('bonus', 12, 2)->default(0);
            $table->decimal('other_taxable', 12, 2)->default(0);
            $table->decimal('adjustment', 12, 2)->default(0);

            // Gross
            $table->decimal('total_gross_pay', 12, 2)->default(0);

            // Tardiness
            $table->decimal('late_hours', 6, 2)->default(0);
            $table->decimal('late_amount', 12, 2)->default(0);
            $table->decimal('absent_days', 6, 2)->default(0);
            $table->decimal('absent_amount', 12, 2)->default(0);
            $table->decimal('total_tardiness', 12, 2)->default(0);

            // Government Contributions (employee share)
            $table->decimal('sss_contribution', 10, 2)->default(0);
            $table->decimal('phic_contribution', 10, 2)->default(0);
            $table->decimal('hdmf_contribution', 10, 2)->default(0);
            $table->decimal('total_contributions', 10, 2)->default(0);

            // Government Loans
            $table->decimal('sss_loan', 10, 2)->default(0);
            $table->decimal('sss_calamity_loan', 10, 2)->default(0);
            $table->decimal('hdmf_loan', 10, 2)->default(0);
            $table->decimal('hdmf_calamity_loan', 10, 2)->default(0);
            $table->decimal('total_gov_loans', 10, 2)->default(0);

            // Other Loans & Deductions
            $table->decimal('company_loan', 10, 2)->default(0);
            $table->decimal('other_loans', 10, 2)->default(0);
            $table->decimal('other_deductions', 10, 2)->default(0);

            // Tax & Net
            $table->decimal('total_deductions', 12, 2)->default(0);
            $table->decimal('amount_due', 12, 2)->default(0);
            $table->decimal('taxable_income', 12, 2)->default(0);
            $table->decimal('withholding_tax', 12, 2)->default(0);
            $table->decimal('net_pay', 12, 2)->default(0);

            // 13th Month
            $table->decimal('thirteenth_month', 12, 2)->default(0);

            $table->unique(['payroll_period_id', 'employee_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
