<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('feedback', function (Blueprint $table) {
        $table->id(); // Primary key, auto-increment
        $table->unsignedBigInteger('user_id'); // Optional user reference
        $table->string('title')->nullable(); // Optional title of feedback
        $table->text('message'); // The actual feedback message
        $table->timestamps(); // created_at and updated_at timestamps

        // Foreign key constraint linking user_id to users table (optional)
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
