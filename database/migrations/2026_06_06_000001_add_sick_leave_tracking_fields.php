<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('leave_sick_paid', 6, 2)->default(0)->after('leave_vacation_taken');
            $table->decimal('leave_sick_unpaid', 6, 2)->default(0)->after('leave_sick_paid');
            $table->decimal('leave_sick_unpaid_amount', 12, 2)->default(0)->after('leave_sick_unpaid');
        });

        Schema::table('company_settings', function (Blueprint $table) {
            $table->integer('sick_leave_per_year')->default(3)->after('minimum_daily_wage');
        });

        // Set existing employees' sick leave balance to 3
        \App\Models\Employee::where('leave_sick_balance', 0)->update(['leave_sick_balance' => 3]);
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['leave_sick_paid', 'leave_sick_unpaid', 'leave_sick_unpaid_amount']);
        });

        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn('sick_leave_per_year');
        });
    }
};
