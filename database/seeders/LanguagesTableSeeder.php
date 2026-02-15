<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguagesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('languages')->insert([
            ['name' => 'English'],
            ['name' => 'French'],
            ['name' => 'Spanish'],
            // Add more languages here
        ]);
    }
}
