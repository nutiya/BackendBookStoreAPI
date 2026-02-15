<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;
use \App\Models\Author;
use \App\Models\Publisher;
use \App\Models\Language;
use \App\Models\Category;

class BookController extends Controller
{
public function index(Request $request)
{
    $query = Book::with(['author', 'publisher', 'language', 'category']);

    if ($search = $request->input('search')) {
        $query->where('title', 'like', "%{$search}%")
              ->orWhereHas('author', fn($q) => $q->where('name', 'like', "%{$search}%"));
    }

    if ($filter = $request->input('filter')) {
        if ($filter === 'lowstock') {
            $query->where('stock_quantity', '<=', 5);
        } elseif ($filter === 'trending') {
            // Define trending logic, e.g., top sold books in last month
            $query->orderBy('sold_count', 'desc');
        }
    }

    $books = $query->paginate(10)->withQueryString();

    return view('admin.books.index', compact('books'));
}


    public function create()
    {
        // Assuming you will pass authors, publishers, languages, categories to the view for selects
        $authors = Author::all();
        $publishers = Publisher::all();
        $languages = Language::all();
        $categories =Category::all();

        return view('admin.books.create', compact('authors', 'publishers', 'languages', 'categories'));
    }

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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Book::create($request->all());

        return redirect()->route('books.index')->with('success', 'Book added successfully.');
    }

    public function edit(string $id)
    {
        $book = Book::findOrFail($id);
        $authors = Author::all();
        $publishers = Publisher::all();
        $languages = Language::all();
        $categories = Category::all();

        return view('admin.books.edit', compact('book', 'authors', 'publishers', 'languages', 'categories'));
    }

    public function update(Request $request, string $id)
    {
        $book = Book::findOrFail($id);

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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $book->update($request->all());

        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }
}
