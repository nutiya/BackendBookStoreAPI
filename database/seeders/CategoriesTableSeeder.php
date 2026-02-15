<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            ['name' => 'Fiction'],
            ['name' => 'Science'],
            ['name' => 'Biography'],
            // Add more categories here
        ]);
    }
}

