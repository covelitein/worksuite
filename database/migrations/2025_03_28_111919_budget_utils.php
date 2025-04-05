<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('budget_utils', function (Blueprint $table) {
            $table->id();
            $table->string('project_id', 50);
            $table->string('project_name', 100);
            $table->decimal('budget_approved_usd', 15, 2);
            $table->string('category', 100);
            $table->decimal('planned_cost_usd', 15, 2);
            $table->decimal('actual_cost_usd', 15, 2);
            $table->decimal('variance_usd', 15, 2);
            $table->decimal('remaining_budget_usd', 15, 2);
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_utils');
    }
};
