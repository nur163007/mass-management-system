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
        if (!Schema::hasTable('menus')) {
            Schema::create('menus', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('icon')->nullable();
                $table->string('route')->nullable();
                $table->string('url')->nullable();
                $table->string('active_pattern')->nullable();
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->integer('order')->default(0);
                $table->tinyInteger('status')->default(1);
                $table->string('permission_key')->nullable();
                $table->timestamps();

                $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('menu_roles')) {
            Schema::create('menu_roles', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('menu_id');
                $table->integer('role_id'); // 1 = Super Admin, 2 = Manager, 3 = User
                $table->timestamps();

                $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
                $table->index(['menu_id', 'role_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_roles');
        Schema::dropIfExists('menus');
    }
};
