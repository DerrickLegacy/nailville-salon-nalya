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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // daily, weekly, monthly, goal_achieved, goal_missed, alert
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Store additional data like amounts, percentages
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('category', ['income', 'expense', 'goal', 'alert', 'insight', 'system'])->default('insight');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index(['is_read', 'created_at']);
            $table->index('type');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
