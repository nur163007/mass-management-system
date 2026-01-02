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
        // First add new columns
        Schema::table('meals', function (Blueprint $table) {
            $table->decimal('lunch_only_curry', 3, 2)->default(0)->after('dinner'); // 0.75 meal count
            $table->decimal('dinner_only_curry', 3, 2)->default(0)->after('lunch_only_curry'); // 0.75 meal count
            $table->decimal('total_meal_count', 5, 2)->nullable()->after('dinner_only_curry'); // Calculated total meal count
        });
        
        // Note: breakfast column type change needs to be handled carefully
        // For now, we'll keep it as integer but handle 0.5 in application logic
        // Or we can add a migration to change it if needed
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->dropColumn(['lunch_only_curry', 'dinner_only_curry', 'total_meal_count']);
        });
    }
};

