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
        Schema::create('tasks_assignment', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('task', 255);
            $table->string('assign_to', 100);
            $table->string('product', 100);
            $table->enum('priority', ['High', 'Medium', 'Low']);
            $table->enum('status', ['Not Yet Started', 'In Progress', 'Completed']);
            $table->string('eta', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks_assignment');
    }
};
