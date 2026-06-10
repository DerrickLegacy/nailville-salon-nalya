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
        // Add composite index for faster session cleanup and queries
        Schema::table('sessions', function (Blueprint $table) {
            // Drop existing single column index if it exists
            $table->dropIndex(['last_activity']);
            
            // Add composite index for better performance
            $table->index(['last_activity', 'user_id'], 'sessions_last_activity_user_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropIndex('sessions_last_activity_user_id_index');
            $table->index('last_activity');
        });
    }
};
