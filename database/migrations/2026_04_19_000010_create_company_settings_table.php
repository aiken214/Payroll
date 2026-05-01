<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->default('Company');
            $table->string('company_address')->nullable();
            $table->string('company_logo')->nullable();
            $table->integer('working_days_per_month')->default(26);
            $table->decimal('minimum_daily_wage', 10, 2)->default(610.00);
            $table->decimal('ot_rate_regular', 6, 4)->default(1.25);
            $table->decimal('ot_rate_rest_day', 6, 4)->default(1.30);
            $table->decimal('ot_rate_rest_day_ot', 6, 4)->default(1.69);
            $table->decimal('ot_rate_special_holiday', 6, 4)->default(1.30);
            $table->decimal('ot_rate_special_holiday_ot', 6, 4)->default(1.69);
            $table->decimal('ot_rate_legal_holiday', 6, 4)->default(2.00);
            $table->decimal('ot_rate_legal_holiday_ot', 6, 4)->default(2.60);
            $table->decimal('night_diff_rate', 6, 4)->default(0.10);
            $table->decimal('hdmf_employee_rate', 6, 4)->default(0.02);
            $table->decimal('hdmf_employer_rate', 6, 4)->default(0.02);
            $table->decimal('hdmf_max_compensation', 12, 2)->default(5000.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
