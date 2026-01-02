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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 200);
            $table->integer('phone_no');
            $table->string('address', 300);
            $table->string('email', 100);
            $table->string('password', 100);
            $table->string('photo', 100);
            $table->string('nid_photo', 100);
            $table->tinyInteger('role_id');
            $table->string('role_name', 255);
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};

