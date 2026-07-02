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
        Schema::table('employees', function (Blueprint $table) {
            $table->enum('payroll_type', ['commission', 'fixed', 'hybrid'])->default('commission')->after('work_location');
            $table->decimal('commission_rate', 5, 2)->default(60.00)->after('payroll_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['payroll_type', 'commission_rate']);
        });
    }
};
