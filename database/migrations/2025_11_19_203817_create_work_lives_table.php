<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('work_lives', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('company_name');
            $table->string('position');
            $table->year('start_year');
            $table->year('end_year')->nullable();
            $table->boolean('is_ongoing')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_lives');
    }
};
