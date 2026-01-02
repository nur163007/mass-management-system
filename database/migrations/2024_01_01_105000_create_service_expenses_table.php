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
        Schema::create('service_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_type'); // Type of expense from service charge
            $table->decimal('amount', 10, 2);
            $table->date('expense_date');
            $table->string('month'); // MMM format
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_expenses');
    }
};

