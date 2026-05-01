<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sss_contributions', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->decimal('range_from', 12, 2);
            $table->decimal('range_to', 12, 2);
            $table->decimal('er_ss', 10, 2);
            $table->decimal('ee_ss', 10, 2);
            $table->decimal('er_ec', 10, 2);
            $table->decimal('ee_ec', 10, 2)->default(0);
            $table->decimal('er_mpf', 10, 2)->default(0);
            $table->decimal('ee_mpf', 10, 2)->default(0);
            $table->decimal('total_er', 10, 2);
            $table->decimal('total_ee', 10, 2);
            $table->decimal('total_contribution', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sss_contributions');
    }
};
