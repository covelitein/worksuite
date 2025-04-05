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
        Schema::create('payment_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_num', 50);
            $table->string('project_id', 50);
            $table->string('project_name', 100);
            $table->decimal('amount_paid', 15, 2);
            $table->string('payment_method', 100);
            $table->date('payment_date')->nullable();
            $table->string('paid_by', 100);
            $table->string('received_by', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_receipts');
    }
};
