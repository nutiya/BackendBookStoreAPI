<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Fiction',
                'description' => 'Fictional stories and novels'
            ],
            [
                'name' => 'Non-Fiction',
                'description' => 'Real-life stories and factual books'
            ],
            [
                'name' => 'Science & Technology',
                'description' => 'Books about science, technology, and innovation'
            ],
            [
                'name' => 'Romance',
                'description' => 'Love stories and romantic novels'
            ],
            [
                'name' => 'Mystery & Thriller',
                'description' => 'Suspenseful and mysterious stories'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}