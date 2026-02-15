@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Users</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Add New User</a>

    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $users->firstItem() + $loop->index }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?? '-' }}</td>
                    <td class="text-center">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning me-1" title="Edit">
                            Edit
                        </a>

                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this user?');"
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
                <tr><td colspan="5" class="text-center">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">{{ $users->links() }}</div>
</div>
@endsection
