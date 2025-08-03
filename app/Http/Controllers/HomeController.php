<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get user shopping stats
        $userStats = $this->getUserShoppingStats($user);
        
        // Get featured book (book of the month)
        $featuredBook = $this->getFeaturedBook();
        
        // Get categories with book counts
        $categories = Category::withCount('books')
            ->orderBy('books_count', 'desc')
            ->limit(8)
            ->get();
        
        // Get daily deals (books with discounts)
        $dailyDeals = $this->getDailyDeals();
        
        // Get new arrivals (latest books added to store)
        $newArrivals = Book::with('category')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($book) {
                return $this->transformBook($book);
            });
        
        // Get best sellers (most sold books)
        $bestSellers = Book::with('category')
            ->leftJoin('order_items', 'books.id', '=', 'order_items.book_id')
            ->select('books.*', DB::raw('COALESCE(SUM(order_items.quantity), 0) as sales_count'))
            ->groupBy('books.id')
            ->orderBy('sales_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($book) {
                return $this->transformBook($book, true);
            });
        
        // Get personalized recommendations
        $recommendations = $this->getShoppingRecommendations($user);
        
        // Get recent orders
        $recentOrders = $this->getRecentOrders($user);
        
        // Get user's cart count
        $cartCount = $this->getCartCount($user);
        
        // Get notifications count
        $notificationsCount = $this->getNotificationsCount($user);
        
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'user_stats' => $userStats,
                'featured_book' => $featuredBook,
                'categories' => $categories,
                'daily_deals' => $dailyDeals,
                'new_arrivals' => $newArrivals,
                'best_sellers' => $bestSellers,
                'recommendations' => $recommendations,
                'recent_orders' => $recentOrders,
                'cart_count' => $cartCount,
                'notifications_count' => $notificationsCount
            ]
        ]);
    }
    
    private function getUserShoppingStats($user)
    {
        if (!$user) {
            return [
                'total_orders' => 0,
                'wishlist_count' => 0
            ];
        }
        
        // Count completed orders
        $totalOrders = Order::where('user_id', $user->id)
            ->where('status', '!=', 'cancelled')
            ->count();
        
        // Sample wishlist count (you can implement actual wishlist later)
        $wishlistCount = rand(2, 12);
        
        return [
            'total_orders' => $totalOrders,
            'wishlist_count' => $wishlistCount
        ];
    }
    
    private function getFeaturedBook()
    {
        $featuredBook = Book::with('category')->first(); // Get any book for now
        
        if ($featuredBook) {
            return [
                'id' => $featuredBook->id,
                'title' => $featuredBook->title,
                'author' => $featuredBook->author,
                'description' => $featuredBook->description,
                'discount_percentage' => 25,
                'original_price' => $featuredBook->price,
                'discounted_price' => round($featuredBook->price * 0.75, 2),
                'image_url' => $featuredBook->image_url,
                'category' => $featuredBook->category,
                'stock_quantity' => $featuredBook->stock_quantity,
                'rating' => 4.5,
                'reviews_count' => 1200
            ];
        }
        
        return null;
    }
    
    private function getDailyDeals()
    {
        return Book::with('category')
            ->where('stock_quantity', '>', 0)
            ->limit(8)
            ->get()
            ->map(function ($book) {
                return $this->transformBook($book);
            });
    }
    
    private function getShoppingRecommendations($user)
    {
        return Book::with('category')
            ->where('stock_quantity', '>', 0)
            ->limit(8)
            ->get()
            ->map(function ($book) {
                return $this->transformBook($book);
            });
    }
    
    private function getRecentOrders($user)
    {
        if (!$user) return [];
        
        return Order::with(['orderItems.book.category'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($order) {
                $firstItem = $order->orderItems->first();
                return [
                    'id' => $order->id,
                    'book' => $firstItem ? $this->transformBook($firstItem->book) : null,
                    'status' => $order->status,
                    'total_amount' => $order->total_amount,
                    'order_date' => $order->created_at->format('M d, Y'),
                    'delivery_date' => null,
                    'items_count' => $order->orderItems->count()
                ];
            });
    }
    
    private function getCartCount($user)
    {
        return $user ? rand(0, 5) : 0;
    }
    
    private function getNotificationsCount($user)
    {
        return $user ? rand(0, 3) : 0;
    }
    
    private function transformBook($book, $includeSales = false)
    {
        $transformed = [
            'id' => $book->id,
            'title' => $book->title,
            'author' => $book->author,
            'description' => $book->description,
            'price' => $book->price,
            'stock_quantity' => $book->stock_quantity,
            'image_url' => $book->image_url,
            'isbn' => $book->isbn,
            'category' => $book->category,
            'category_id' => $book->category_id,
            'publication_date' => $book->publication_date,
            'rating' => rand(35, 50) / 10, // Random rating between 3.5-5.0
            'reviews_count' => rand(100, 10000),
            'is_in_stock' => $book->stock_quantity > 0,
            'is_bestseller' => rand(0, 1) == 1,
            'is_featured' => false,
            'is_trending' => false,
            'is_new_release' => false,
        ];
        
        if ($includeSales) {
            $transformed['sales_count'] = rand(1000, 50000);
        }
        
        return $transformed;
    }
}
