<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wtax_tables', function (Blueprint $table) {
            $table->id();
            $table->enum('frequency', ['daily', 'weekly', 'semi_monthly', 'monthly', 'yearly']);
            $table->decimal('min_range', 12, 2);
            $table->decimal('max_range', 12, 2)->nullable();
            $table->decimal('base_tax', 12, 2)->default(0);
            $table->decimal('tax_rate', 8, 4)->default(0);
            $table->decimal('excess_over', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wtax_tables');
    }
};
