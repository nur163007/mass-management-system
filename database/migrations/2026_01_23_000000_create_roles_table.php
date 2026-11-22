<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('description')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });

            // Seed default roles
            DB::table('roles')->insert([
                [
                    'id' => 1,
                    'name' => 'Super Admin',
                    'slug' => 'super_admin',
                    'description' => 'Full system administrator with complete access',
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 2,
                    'name' => 'Manager',
                    'slug' => 'manager',
                    'description' => 'Mess manager responsible for operations, meals and expenses',
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => 3,
                    'name' => 'User',
                    'slug' => 'user',
                    'description' => 'Regular mess border / member',
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
