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
        Schema::create('room_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade');
            $table->decimal('advance_paid', 10, 2)->default(0); // Room advance paid
            $table->decimal('monthly_rent', 10, 2); // Actual monthly rent for this member
            $table->date('assigned_date');
            $table->date('left_date')->nullable(); // When member left the room
            $table->integer('status')->default(1); // 1 = active, 0 = left
            $table->timestamps();
            
            $table->unique(['room_id', 'member_id', 'status'], 'unique_active_room_member');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_members');
    }
};

