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
        // Update existing Users (role_id = 0) to role_id = 3
        DB::table('members')
            ->where('role_id', 0)
            ->where('role_name', 'User')
            ->update([
                'role_id' => 3,
                'role_name' => 'User'
            ]);
        
        // Update existing Admins (role_id = 1) to Managers (role_id = 2)
        DB::table('members')
            ->where('role_id', 1)
            ->where('role_name', 'Admin')
            ->update([
                'role_id' => 2,
                'role_name' => 'Manager'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse: Update Managers (role_id = 2) back to Admins (role_id = 1)
        DB::table('members')
            ->where('role_id', 2)
            ->where('role_name', 'Manager')
            ->update([
                'role_id' => 1,
                'role_name' => 'Admin'
            ]);
        
        // Reverse: Update Users (role_id = 3) back to role_id = 0
        DB::table('members')
            ->where('role_id', 3)
            ->where('role_name', 'User')
            ->update([
                'role_id' => 0,
                'role_name' => 'User'
            ]);
    }
};
