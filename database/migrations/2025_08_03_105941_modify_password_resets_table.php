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
        // ❗ You must do column checks outside the closure
        if (Schema::hasColumn('password_resets', 'email')) {
            Schema::table('password_resets', function (Blueprint $table) {
                $table->dropColumn('email');
            });
        }

        if (!Schema::hasColumn('password_resets', 'phone')) {
            Schema::table('password_resets', function (Blueprint $table) {
                $table->string('phone')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('password_resets', 'phone')) {
            Schema::table('password_resets', function (Blueprint $table) {
                $table->dropColumn('phone');
            });
        }

        if (!Schema::hasColumn('password_resets', 'email')) {
            Schema::table('password_resets', function (Blueprint $table) {
                $table->string('email')->index();
            });
        }
    }
};
