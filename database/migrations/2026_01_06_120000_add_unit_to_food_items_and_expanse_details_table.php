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
        if (Schema::hasTable('food_items') && !Schema::hasColumn('food_items', 'unit')) {
            Schema::table('food_items', function (Blueprint $table) {
                $table->string('unit', 50)->nullable()->after('item_name');
            });

            // Backfill unit from item_description if looks like a unit
            DB::statement("UPDATE food_items SET unit = item_description WHERE unit IS NULL AND item_description IS NOT NULL AND item_description != '' AND LENGTH(item_description) <= 20");
        }

        if (Schema::hasTable('expanse_details') && !Schema::hasColumn('expanse_details', 'unit')) {
            Schema::table('expanse_details', function (Blueprint $table) {
                $table->string('unit', 50)->nullable()->after('weight');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('food_items') && Schema::hasColumn('food_items', 'unit')) {
            Schema::table('food_items', function (Blueprint $table) {
                $table->dropColumn('unit');
            });
        }

        if (Schema::hasTable('expanse_details') && Schema::hasColumn('expanse_details', 'unit')) {
            Schema::table('expanse_details', function (Blueprint $table) {
                $table->dropColumn('unit');
            });
        }
    }
};
