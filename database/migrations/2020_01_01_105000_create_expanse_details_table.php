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
        Schema::create('expanse_details', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no', 25);
            $table->tinyInteger('member_id');
            $table->tinyInteger('item_name_id');
            $table->string('weight', 100);
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expanse_details');
    }
};

