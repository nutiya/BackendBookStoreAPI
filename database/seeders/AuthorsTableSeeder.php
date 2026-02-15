<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthorsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('authors')->insert([
            ['name' => 'J.K. Rowling'],
            ['name' => 'George Orwell'],
            ['name' => 'Jane Austen'],
            // Add more authors here
        ]);
    }
}
