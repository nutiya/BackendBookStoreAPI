@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Books List</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Search & Filter Form --}}
    <form method="GET" action="{{ route('books.index') }}" class="row g-3 align-items-center mb-4">
        <div class="col-md-5">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by title or author">
        </div>

        <div class="col-md-3">
            <select name="filter" class="form-select">
                <option value="">Filter by</option>
                <option value="lowstock" {{ request('filter') === 'lowstock' ? 'selected' : '' }}>Low Stock (≤ 5)</option>
                <option value="trending" {{ request('filter') === 'trending' ? 'selected' : '' }}>Trending</option>
            </select>
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Apply</button>
        </div>

        <div class="col-md-2 text-md-end">
            <a href="{{ route('books.create') }}" class="btn btn-success w-100">Add New Book</a>
        </div>
    </form>

    {{-- Books Table --}}
    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Publisher</th>
                    <th>Language</th>
                    <th>Category</th>
                    <th class="text-end">Price</th>
                    <th class="text-center">Stock</th>
                    <th class="text-center">Sold</th>
                    <th class="text-center">Publication Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                    <tr>
                        <td>{{ $books->firstItem() + $loop->index }}</td>
                        <td>
                            @if($book->image_url)
                                <img src="{{ $book->image_url }}" alt="{{ $book->title }}" style="max-width: 60px; height: auto; border-radius: 4px;">
                            @else
                                <img src="{{ asset('images/no-image.png') }}" alt="No Image" style="max-width: 60px; height: auto; border-radius: 4px;">
                            @endif
                        </td>

                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author->name ?? '-' }}</td>
                        <td>{{ $book->publisher->name ?? '-' }}</td>
                        <td>{{ $book->language->name ?? '-' }}</td>
                        <td>{{ $book->category->name ?? '-' }}</td>
                        <td class="text-end">${{ number_format($book->price, 2) }}</td>
                        <td class="text-center">
                            @if($book->stock_quantity <= 5)
                                <span class="badge bg-danger">{{ $book->stock_quantity }}</span>
                            @else
                                {{ $book->stock_quantity }}
                            @endif
                        </td>
                        <td class="text-center">{{ $book->sold_count ?? 0 }}</td>
                        <td class="text-center">{{ $book->publication_date ? $book->publication_date->format('Y-m-d') : '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                                Edit
                            </a>

                            <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Are you sure you want to delete this book?');"
                            >
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" type="submit" title="Delete">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center">No books found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $books->links() }}
    </div>
</div>
@endsection
