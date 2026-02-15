<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;
use App\Models\Book;





class CartController extends Controller
{
public function index()
{
    $cartItems = CartItem::with([
        'book.author:id,name',
        'book.publisher:id,name',
        'book.language:id,name',
        'book.category:id,name'
    ])
    ->where('user_id', Auth::id())
    ->get()
    ->map(function ($item) {
        return [
            'cart_id'          => $item->id,
            'quantity'         => $item->quantity,
            'cart_created_at'  => $item->created_at,
            'cart_updated_at'  => $item->updated_at,

            'id'               => $item->book->id,
            'title'            => $item->book->title,
            'pages'            => $item->book->pages,
            'description'      => $item->book->description,
            'price'            => $item->book->price,
            'stock_quantity'   => $item->book->stock_quantity,
            'sold_count'       => $item->book->sold_count,
            'image_url'        => $item->book->image_url,
            'isbn'             => $item->book->isbn,
            'publication_date' => $item->book->publication_date,

            'author_id'        => $item->book->author->id,
            'author_name'      => $item->book->author->name,

            'publisher_id'     => $item->book->publisher->id,
            'publisher_name'   => $item->book->publisher->name,

            'language_id'      => $item->book->language->id,
            'language_name'    => $item->book->language->name,

            'category_id'      => $item->book->category->id,
            'category_name'    => $item->book->category->name,
        ];
    });

    return response()->json($cartItems);
}


public function add(Request $request)
{
    $request->validate([
        'book_id' => 'required|exists:books,id',
        'quantity' => 'required|integer|min:1'
    ]);

    $userId = Auth::id();
    $bookId = $request->book_id;
    $quantityToAdd = $request->quantity;

    // 🔹 Get the book and stock
    $book = Book::find($bookId);
    if (!$book) {
        return response()->json(['message' => 'Book not found'], 404);
    }

    // 🔹 Check existing cart item
    $existingItem = CartItem::where('user_id', $userId)
        ->where('book_id', $bookId)
        ->first();

    $currentQuantity = $existingItem ? $existingItem->quantity : 0;
    $newQuantity = $currentQuantity + $quantityToAdd;

    // 🔹 Check stock availability
    if ($newQuantity > $book->stock_quantity) {
        return response()->json([
            'message' => 'Not enough stock available. Only ' . $book->stock_quantity . ' left.'
        ], 400);
    }

    // 🔹 Add or update cart item
    if ($existingItem) {
        $existingItem->quantity = $newQuantity;
        $existingItem->save();
    } else {
        CartItem::create([
            'user_id' => $userId,
            'book_id' => $bookId,
            'quantity' => $quantityToAdd,
        ]);
    }

    // 🔹 Remove from wishlist
    DB::table('wishlists')
        ->where('user_id', $userId)
        ->where('book_id', $bookId)
        ->delete();

    return response()->json(['message' => 'Added to cart']);
}


public function update(Request $request, $book_id)
{
    $request->validate([
        'quantity' => 'required|integer|min:1'
    ]);

    $userId = Auth::id();

    // Find the book
    $book = Book::find($book_id);

    if (!$book) {
        return response()->json(['message' => 'Book not found'], 404);
    }

    // Check if the requested quantity exceeds stock
    if ($request->quantity > $book->stock_quantity) {
        return response()->json([
            'message' => 'Requested quantity exceeds available stock',
            'available_stock' => $book->stock_quantity
        ], 400);
    }

    // Update cart quantity
    CartItem::where('user_id', $userId)
        ->where('book_id', $book_id)
        ->update(['quantity' => $request->quantity]);

    return response()->json(['message' => 'Cart updated successfully']);
}


    public function remove($book_id)
    {
        CartItem::where('user_id', Auth::id())
                ->where('book_id', $book_id)
                ->delete();

        return response()->json(['message' => 'Removed from cart']);
    }
}

