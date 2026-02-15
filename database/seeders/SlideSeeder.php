<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SlideSeeder extends Seeder
{
    public function run()
    {
        DB::table('slides')->insert([
            [
                'name' => 'Welcome to Book Haven',
                'image_url' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'New Arrivals in Stock',
                'image_url' => 'https://images.unsplash.com/photo-1519681393784-d120267933ba?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'name' => 'Top Picks for You',
                'image_url' => 'https://images.unsplash.com/photo-1528207776546-365bb710ee93?auto=format&fit=crop&w=800&q=80',
            ],
        ]);
    }
}
