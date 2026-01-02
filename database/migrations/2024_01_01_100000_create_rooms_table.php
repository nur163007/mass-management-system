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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_name'); // Room 1, Room 2, Room 3, Dining
            $table->string('room_type')->default('room'); // room or dining
            $table->decimal('monthly_rent', 10, 2); // 6700, 5800, 2900
            $table->integer('capacity')->default(2); // 2 for rooms, 1 for dining
            $table->integer('advance_amount_per_person_1')->default(4700); // First person advance
            $table->integer('advance_amount_per_person_2')->nullable(); // Second person advance (for room3)
            $table->integer('status')->default(1); // 1 = active
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};

