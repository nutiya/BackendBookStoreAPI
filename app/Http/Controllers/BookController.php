<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    

    public function bestSellers(Request $request)
        {
            $limit = $request->get('limit', 10);
            
            $books = Book::with('category')
                        ->orderBy('sold_count', 'asc')
                        ->limit($limit)
                        ->get();

            return response()->json([
                'success' => true,
                'message' => 'Best selling books retrieved successfully',
                'data' => $books
            ]);
        }

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

    public function salesAnalytics()
    {
        $totalBooks = Book::count();
        $totalSold = Book::sum('sold_count');
        $totalRevenue = Book::selectRaw('SUM(sold_count * price) as revenue')->first()->revenue;
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
                'total_books' => $totalBooks,
                'total_sold' => $totalSold,
                'total_revenue' => round($totalRevenue, 2),
                'low_stock_count' => $lowStockCount,
                'top_categories' => $topCategories
            ]
        ]);
    }
    public function index(Request $request)
    {
        $query = Book::with('category');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                ->orWhere('author', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Validate sort fields
        $allowedSortFields = ['created_at', 'title', 'author', 'price', 'sold_count', 'stock_quantity'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $perPage = $request->get('per_page', 10);
        $books = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $books
        ]);
    }

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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
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
}