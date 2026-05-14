<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->decimal('late_minutes', 8, 2)->default(0)->after('late_hours');
            $table->string('other_deductions_remarks', 500)->nullable()->after('other_deductions');
            $table->date('other_deductions_date')->nullable()->after('other_deductions_remarks');
        });

        Schema::table('company_settings', function (Blueprint $table) {
            $table->string('dti_permit_number')->nullable()->after('company_logo');
            $table->string('watermark_logo')->nullable()->after('dti_permit_number');
        });
    }

    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn(['late_minutes', 'other_deductions_remarks', 'other_deductions_date']);
        });

        Schema::table('company_settings', function (Blueprint $table) {
            $table->dropColumn(['dti_permit_number', 'watermark_logo']);
        });
    }
};
