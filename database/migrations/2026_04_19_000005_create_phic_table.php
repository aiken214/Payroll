<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phic_contributions', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->decimal('range_from', 12, 2);
            $table->decimal('range_to', 12, 2);
            $table->decimal('premium_rate', 8, 4)->nullable();
            $table->decimal('monthly_premium', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phic_contributions');
    }
};
