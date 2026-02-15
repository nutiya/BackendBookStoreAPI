@extends('layouts.admin')

@section('content')
<h1 class="mb-4">Feedback List</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<!-- Filter Form -->
<form method="GET" action="{{ route('feedback.index') }}" class="mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-md-3">
            <label for="date" class="form-label">Filter by Date</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}">
        </div>

        <div class="col-md-3">
            <label for="date_from" class="form-label">From Date</label>
            <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>

        <div class="col-md-3">
            <label for="date_to" class="form-label">To Date</label>
            <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>

        <div class="col-md-3 d-flex align-items-center">
            <button type="submit" class="btn btn-primary me-2">Filter</button>
            <a href="{{ route('feedback.index') }}" class="btn btn-secondary">Clear</a>
        </div>
    </div>
</form>

<!-- Feedback Table -->
<table class="table table-bordered table-hover align-middle">
    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Title</th>
            <th>Message</th>
            <th>Submitted At</th>
            <th style="width: 130px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($feedbacks as $feedback)
        <tr>
            <td>{{ $feedback->id }}</td>
            <td>{{ $feedback->user ? $feedback->user->name : 'Unknown' }}</td>
            <td>{{ $feedback->title }}</td>
            <td>{{ \Illuminate\Support\Str::limit($feedback->message, 50) }}</td>
            <td>{{ $feedback->created_at->format('d M Y H:i') }}</td>
            <td>
                <a href="{{ route('feedback.show', $feedback->id) }}" class="btn btn-info btn-sm me-1">View</a>
                <form action="{{ route('feedback.destroy', $feedback->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this feedback?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">No feedback found.</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $feedbacks->links() }}

@endsection
