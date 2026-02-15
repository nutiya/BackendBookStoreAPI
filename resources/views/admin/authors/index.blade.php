@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Authors</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <a href="{{ route('authors.create') }}" class="btn btn-primary mb-3">Add New Author</a>

    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($authors as $author)
                <tr>
                    <td>{{ $authors->firstItem() + $loop->index }}</td>
                    <td>{{ $author->name }}</td>
                    <td class="text-center">
                        <a href="{{ route('authors.edit', $author->id) }}" class="btn btn-sm btn-warning me-1">
                            Edit
                        </a>

                        <form action="{{ route('authors.destroy', $author->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this author?');"
                        >
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" type="submit">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center">No authors found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $authors->links() }}
    </div>
</div>
@endsection
