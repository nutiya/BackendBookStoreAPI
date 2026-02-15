<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();                 // Primary key
            $table->string('name');       // Language name (e.g., English, French)
            $table->timestamps();         // created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
