@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Languages</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <a href="{{ route('languages.create') }}" class="btn btn-primary mb-3">Add New Language</a>

    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($languages as $language)
                <tr>
                    <td>{{ $languages->firstItem() + $loop->index }}</td>
                    <td>{{ $language->name }}</td>
                    <td class="text-center">
                        <a href="{{ route('languages.edit', $language->id) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                            Edit
                        </a>

                        <form action="{{ route('languages.destroy', $language->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this language?');"
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
                <tr><td colspan="3" class="text-center">No languages found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $languages->links() }}
    </div>
</div>
@endsection
