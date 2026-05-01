<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->foreignId('department_id')->constrained()->nullOnDelete();
            $table->foreignId('position_id')->constrained()->nullOnDelete();
            $table->enum('employment_status', ['Permanent/Regular', 'Probationary', 'Contractual', 'Part-time'])->default('Permanent/Regular');
            $table->decimal('monthly_salary', 12, 2);
            $table->enum('salary_type', ['ATM', 'CASH'])->default('CASH');
            $table->string('atm_number')->nullable();
            $table->string('sss_number')->nullable();
            $table->string('phic_number')->nullable();
            $table->string('hdmf_number')->nullable();
            $table->string('tin_number')->nullable();
            $table->decimal('leave_sick_balance', 6, 2)->default(0);
            $table->decimal('leave_vacation_balance', 6, 2)->default(0);
            $table->boolean('is_minimum_wage_earner')->default(false);
            $table->boolean('is_active')->default(true);
            $table->date('date_hired')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
