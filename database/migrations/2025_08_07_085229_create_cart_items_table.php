<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration
{
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            // Reference to users table, the owner of the cart item
            $table->unsignedBigInteger('user_id');

            // Reference to books table, the book added to cart
            $table->unsignedBigInteger('book_id');

            // Quantity of the book added
            $table->integer('quantity')->default(1);

            $table->timestamps();

            // Foreign keys for referential integrity
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');

            // To prevent duplicate cart entries for same user and book
            $table->unique(['user_id', 'book_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
}
