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
        Schema::table('food_items', function (Blueprint $table) {
            // Use raw SQL to modify column to nullable
            DB::statement('ALTER TABLE food_items MODIFY food_category_id INT NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('food_items', function (Blueprint $table) {
            // Use raw SQL to modify column back to NOT NULL
            DB::statement('ALTER TABLE food_items MODIFY food_category_id INT NOT NULL');
        });
    }
};
