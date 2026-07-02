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
        Schema::create('advance_recoveries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('salary_advance_id');
            $table->unsignedBigInteger('payroll_run_id')->nullable();
            $table->decimal('amount_recovered', 12, 2);
            $table->date('recovery_date');
            $table->unsignedBigInteger('recovered_by');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('salary_advance_id')->references('id')->on('salary_advances')->onDelete('cascade');
            $table->foreign('payroll_run_id')->references('id')->on('payroll_runs')->onDelete('set null');
            $table->foreign('recovered_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advance_recoveries');
    }
};
