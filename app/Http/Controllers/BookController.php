<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    // Get paginated list of books with search, filter and sort


public function index()
{
    $books = Book::with(['author', 'category', 'publisher', 'language'])->get();

    $result = $books->map(function ($book) {
        return [
            'id' => $book->id,
            'title' => $book->title,
            'author_id' => $book->author_id,
            'publisher_id' => $book->publisher_id,
            'language_id' => $book->language_id,
            'pages' => $book->pages,
            'description' => $book->description,
            'price' => $book->price,
            'stock_quantity' => $book->stock_quantity,
            'sold_count' => $book->sold_count,
            'image_url' => $book->image_url,
            'isbn' => $book->isbn,
            'category_id' => $book->category_id,
            'publication_date' => $book->publication_date,
            'created_at' => $book->created_at,
            'updated_at' => $book->updated_at,

            // Related names
            'author_name' => $book->author->name ?? null,
            'category_name' => $book->category->name ?? null,
            'publisher_name' => $book->publisher->name ?? null,
            'language_name' => $book->language->name ?? null,
        ];
    });

    return response()->json($result);
}







    // Show single book
    public function show($id)
    {
        $book = Book::with('category')->find($id);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $book
        ]);
    }

    // Store a new book
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'            => 'required|string|max:255',
            'author_id'        => 'required|exists:authors,id',
            'publisher_id'     => 'required|exists:publishers,id',
            'language_id'      => 'required|exists:languages,id',
            'pages'            => 'nullable|integer|min:1',
            'description'      => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'stock_quantity'   => 'required|integer|min:0',
            'sold_count'       => 'nullable|integer|min:0',
            'image_url'        => 'nullable|url',
            'isbn'             => 'nullable|string|max:20',
            'category_id'      => 'required|exists:categories,id',
            'publication_date' => 'nullable|date',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $book = Book::create($request->all());
        $book->load('category');

        return response()->json([
            'success' => true,
            'message' => 'Book created successfully',
            'data' => $book
        ], 201);
    }

    // Get books sorted by sold count (best sellers)
    public function bestSellers(Request $request)
    {
        $limit = $request->get('limit', 10);

        $books = Book::with('category')
            ->orderBy('sold_count', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Best selling books retrieved successfully',
            'data' => $books
        ]);
    }

    // Get books with low stock
    public function lowStock(Request $request)
    {
        $threshold = $request->get('threshold', 10);

        $books = Book::with('category')
            ->where('stock_quantity', '<=', $threshold)
            ->orderBy('stock_quantity', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Low stock books retrieved successfully',
            'data' => $books
        ]);
    }

    // Analytics summary for dashboard
    public function salesAnalytics()
    {
        $totalBooks = Book::count();
        $totalSold = Book::sum('sold_count');
        $totalRevenue = Book::selectRaw('SUM(sold_count * price) as revenue')->value('revenue');
        $lowStockCount = Book::where('stock_quantity', '<=', 10)->count();

        $topCategories = Book::join('categories', 'books.category_id', '=', 'categories.id')
            ->selectRaw('categories.name, SUM(books.sold_count) as total_sold')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_books'      => $totalBooks,
                'total_sold'       => $totalSold,
                'total_revenue'    => round($totalRevenue, 2),
                'low_stock_count'  => $lowStockCount,
                'top_categories'   => $topCategories
            ]
        ]);
    }

    // New Releases (latest books)
public function newReleases()
{
    $books = Book::with(['author', 'category', 'publisher', 'language'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    $result = $books->map(function ($book) {
        $data = $book->attributesToArray();
        $data['author_name'] = $book->author->name ?? null;
        $data['category_name'] = $book->category->name ?? null;
        $data['publisher_name'] = $book->publisher->name ?? null;
        $data['language_name'] = $book->language->name ?? null;
        return $data;
    });

    return response()->json($result);
}


    // Trending (most sold)
public function trending()
{
    $books = Book::with(['author', 'category', 'publisher', 'language'])
        ->orderBy('sold_count', 'desc')
        ->limit(5)
        ->get();

    $result = $books->map(function ($book) {
        $data = $book->attributesToArray();
        $data['author_name'] = $book->author->name ?? null;
        $data['category_name'] = $book->category->name ?? null;
        $data['publisher_name'] = $book->publisher->name ?? null;
        $data['language_name'] = $book->language->name ?? null;
        return $data;
    });

    return response()->json($result);
}

}
