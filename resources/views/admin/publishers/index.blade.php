@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Publishers</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <a href="{{ route('publishers.create') }}" class="btn btn-primary mb-3">Add New Publisher</a>

    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($publishers as $publisher)
                <tr>
                    <td>{{ $publishers->firstItem() + $loop->index }}</td>
                    <td>{{ $publisher->name }}</td>
                    <td class="text-center">
                        <a href="{{ route('publishers.edit', $publisher->id) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                            Edit
                        </a>

                        <form action="{{ route('publishers.destroy', $publisher->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this publisher?');"
                        >
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" title="Delete" type="submit">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center">No publishers found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $publishers->links() }}
    </div>
</div>
@endsection
