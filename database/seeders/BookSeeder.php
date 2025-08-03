<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        $books = [
            [
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'description' => 'A classic American novel set in the Jazz Age',
                'price' => 12.99,
                'stock_quantity' => 50,
                'sold_count' => 25,  // Add this line
                'isbn' => '9780743273565',
                'category_id' => $categories->where('name', 'Fiction')->first()->id,
                'publication_date' => '1925-04-10'
            ],
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'description' => 'A gripping tale of racial injustice and childhood innocence',
                'price' => 14.99,
                'stock_quantity' => 30,
                'sold_count' => 18,  // Add this line
                'isbn' => '9780061120084',
                'category_id' => $categories->where('name', 'Fiction')->first()->id,
                'publication_date' => '1960-07-11'
            ],
            // Add sold_count to all other books...
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'description' => 'A handbook of agile software craftsmanship',
                'price' => 45.99,
                'stock_quantity' => 20,
                'sold_count' => 35,  // Add this line
                'isbn' => '9780132350884',
                'category_id' => $categories->where('name', 'Science & Technology')->first()->id,
                'publication_date' => '2008-08-01'
            ],
            [
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen',
                'description' => 'A romantic novel of manners',
                'price' => 11.99,
                'stock_quantity' => 45,
                'sold_count' => 12,  // Add this line
                'isbn' => '9780141439518',
                'category_id' => $categories->where('name', 'Romance')->first()->id,
                'publication_date' => '1813-01-28'
            ],
            [
                'title' => 'Gone Girl',
                'author' => 'Gillian Flynn',
                'description' => 'A psychological thriller about a marriage gone wrong',
                'price' => 13.99,
                'stock_quantity' => 28,
                'sold_count' => 22,  // Add this line
                'isbn' => '9780307588371',
                'category_id' => $categories->where('name', 'Mystery & Thriller')->first()->id,
                'publication_date' => '2012-06-05'
            ]
        ];

        foreach ($books as $book) {
            Book::create($book);
        }
    }
}