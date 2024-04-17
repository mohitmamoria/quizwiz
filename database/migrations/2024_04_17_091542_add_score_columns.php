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
        Schema::table('game_user', function (Blueprint $table) {
            $table->integer('score')->default(0)->after('user_id');
        });

        Schema::table('attempts', function (Blueprint $table) {
            $table->integer('score')->default(0)->after('is_correct');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_user', function (Blueprint $table) {
            $table->dropColumn('score');
        });

        Schema::table('attempts', function (Blueprint $table) {
            $table->dropColumn('score');
        });
    }
};
