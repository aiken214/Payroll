<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->enum('loan_type', ['SSS Salary', 'SSS Calamity', 'HDMF Salary', 'HDMF Calamity', 'Company', 'Other']);
            $table->string('description')->nullable();
            $table->decimal('total_amount', 12, 2);
            $table->decimal('monthly_amortization', 10, 2);
            $table->decimal('balance', 12, 2);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_loans');
    }
};
