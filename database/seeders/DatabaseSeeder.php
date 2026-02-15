<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AuthorsTableSeeder::class,
            PublishersTableSeeder::class,
            LanguagesTableSeeder::class,
            CategoriesTableSeeder::class,
            BooksTableSeeder::class,
            SlideSeeder::class,

        ]);
    }
}