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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_type'); // water, internet, electricity, gas, bua, moyla
            $table->decimal('total_amount', 10, 2);
            $table->integer('applicable_members'); // 7 for most, 6 for internet
            $table->string('month'); // MMM format (Jan, Feb, etc)
            $table->date('bill_date');
            $table->date('due_date')->nullable();
            
            // For gas bills
            $table->integer('cylinder_count')->nullable(); // Number of cylinders
            $table->decimal('cylinder_cost', 10, 2)->nullable(); // 1500 per cylinder
            $table->text('extra_gas_users')->nullable(); // JSON: member_ids who pay extra 100tk
            
            // For electricity (variable)
            $table->decimal('minimum_per_person', 10, 2)->default(200); // Minimum 200tk per person
            
            $table->text('notes')->nullable();
            $table->integer('status')->default(1); // 1 = active
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};

