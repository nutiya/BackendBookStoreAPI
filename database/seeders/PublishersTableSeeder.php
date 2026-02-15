<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublishersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('publishers')->insert([
            ['name' => 'Penguin Random House'],
            ['name' => 'HarperCollins'],
            ['name' => 'Simon & Schuster'],
            // Add more publishers here
        ]);
    }
}

