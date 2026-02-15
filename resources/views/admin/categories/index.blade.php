@extends('layouts.admin')

@section('content')
<div class="container">
  <h1 class="mb-4">Categories</h1>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">Add New Category</a>

  <table class="table table-striped table-bordered align-middle">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($categories as $category)
        <tr>
          <td>{{ $categories->firstItem() + $loop->index }}</td>
          <td>{{ $category->name }}</td>
          <td>
            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-warning">Edit</a>
            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Are you sure you want to delete this category?');"
            >
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-danger" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="3" class="text-center">No categories found.</td></tr>
      @endforelse
    </tbody>
  </table>

  {{ $categories->links() }}
</div>
@endsection
