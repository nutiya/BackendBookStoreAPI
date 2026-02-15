@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Add New Book</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>There are some errors:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('books.store') }}" method="POST" novalidate>
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" id="title" 
                value="{{ old('title') }}" 
                class="form-control @error('title') is-invalid @enderror" 
                required maxlength="255"
            >
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="author_id" class="form-label">Author <span class="text-danger">*</span></label>
                <select name="author_id" id="author_id" 
                    class="form-select @error('author_id') is-invalid @enderror" required>
                    <option value="">-- Select Author --</option>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>
                            {{ $author->name }}
                        </option>
                    @endforeach
                </select>
                @error('author_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="publisher_id" class="form-label">Publisher <span class="text-danger">*</span></label>
                <select name="publisher_id" id="publisher_id" 
                    class="form-select @error('publisher_id') is-invalid @enderror" required>
                    <option value="">-- Select Publisher --</option>
                    @foreach($publishers as $publisher)
                        <option value="{{ $publisher->id }}" {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>
                            {{ $publisher->name }}
                        </option>
                    @endforeach
                </select>
                @error('publisher_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="language_id" class="form-label">Language <span class="text-danger">*</span></label>
                <select name="language_id" id="language_id" 
                    class="form-select @error('language_id') is-invalid @enderror" required>
                    <option value="">-- Select Language --</option>
                    @foreach($languages as $language)
                        <option value="{{ $language->id }}" {{ old('language_id') == $language->id ? 'selected' : '' }}>
                            {{ $language->name }}
                        </option>
                    @endforeach
                </select>
                @error('language_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                <select name="category_id" id="category_id" 
                    class="form-select @error('category_id') is-invalid @enderror" required>
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="pages" class="form-label">Pages</label>
                <input type="number" name="pages" id="pages" 
                    class="form-control @error('pages') is-invalid @enderror" 
                    value="{{ old('pages') }}" min="1" 
                >
                @error('pages')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                <input type="number" step="0.01" name="price" id="price" 
                    class="form-control @error('price') is-invalid @enderror" 
                    value="{{ old('price') }}" min="0" required
                >
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4 mb-3">
                <label for="stock_quantity" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                <input type="number" name="stock_quantity" id="stock_quantity" 
                    class="form-control @error('stock_quantity') is-invalid @enderror" 
                    value="{{ old('stock_quantity') }}" min="0" required
                >
                @error('stock_quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="sold_count" class="form-label">Sold Count</label>
            <input type="number" name="sold_count" id="sold_count" 
                class="form-control @error('sold_count') is-invalid @enderror" 
                value="{{ old('sold_count') }}" min="0"
            >
            @error('sold_count')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="image_url" class="form-label">Image URL</label>
            <input type="url" name="image_url" id="image_url" 
                class="form-control @error('image_url') is-invalid @enderror" 
                value="{{ old('image_url') }}"
            >
            @error('image_url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="isbn" class="form-label">ISBN</label>
            <input type="text" name="isbn" id="isbn" 
                class="form-control @error('isbn') is-invalid @enderror" 
                maxlength="20" value="{{ old('isbn') }}"
            >
            @error('isbn')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="publication_date" class="form-label">Publication Date</label>
            <input type="date" name="publication_date" id="publication_date" 
                class="form-control @error('publication_date') is-invalid @enderror" 
                value="{{ old('publication_date') }}"
            >
            @error('publication_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Add Book</button>
        <a href="{{ route('books.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection
