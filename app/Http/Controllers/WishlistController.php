<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function add(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'book_id' => 'required|integer|exists:books,id',
        ]);

        $bookId = $request->input('book_id');
        $user->wishlistBooks()->syncWithoutDetaching([$bookId]);

        return response()->json(['message' => 'Added to wishlist']);
    }

    public function remove($bookId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $user->wishlistBooks()->detach($bookId);

        return response()->json(['message' => 'Removed from wishlist']);
    }

    public function list()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $wishlistBooks = $user->wishlistBooks()
            ->with(['author', 'publisher', 'language', 'category'])
            ->get()
            ->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author_name' => $book->author->name ?? null,
                    'publisher_name' => $book->publisher->name ?? null,
                    'language_name' => $book->language->name ?? null,
                    'category_name' => $book->category->name ?? null,
                    'pages' => $book->pages,
                    'description' => $book->description,
                    'price' => $book->price,
                    'stock_quantity' => $book->stock_quantity,
                    'sold_count' => $book->sold_count,
                    'image_url' => $book->image_url,
                    'isbn' => $book->isbn,
                    'publication_date' => $book->publication_date,  // no formatting
                    'created_at' => $book->created_at,
                    'updated_at' => $book->updated_at,
                    'pivot_created_at' => $book->pivot->created_at ?? null,
                    'pivot_updated_at' => $book->pivot->updated_at ?? null,
                ];
            });

        return response()->json($wishlistBooks);
    }
}
