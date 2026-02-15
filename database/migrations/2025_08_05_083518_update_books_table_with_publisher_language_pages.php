<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Make author a foreign key (rename if needed)
            $table->unsignedBigInteger('author_id')->nullable()->after('title');
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('set null');

            // Add publisher_id
            $table->unsignedBigInteger('publisher_id')->nullable()->after('author_id');
            $table->foreign('publisher_id')->references('id')->on('publishers')->onDelete('set null');

            // Add language_id
            $table->unsignedBigInteger('language_id')->nullable()->after('publisher_id');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('set null');

            // Add pages
            $table->integer('pages')->nullable()->after('language_id');

            // Optional: Remove old author column if named 'author'
            if (Schema::hasColumn('books', 'author')) {
                $table->dropColumn('author');
            }
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropForeign(['publisher_id']);
            $table->dropForeign(['language_id']);

            $table->dropColumn(['author_id', 'publisher_id', 'language_id', 'pages']);

            // Optional: Add back old 'author' column if needed
            $table->string('author')->nullable();
        });
    }
};

