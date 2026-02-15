@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Feedback Details</h1>

    @if($feedback->user)
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            User Information
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $feedback->user->name }}</p>
            <p><strong>Email:</strong> {{ $feedback->user->email }}</p>
            <p><strong>Phone:</strong> {{ $feedback->user->phone ?? 'N/A' }}</p>
            <p><strong>Registered At:</strong> {{ $feedback->user->created_at->format('d M Y') }}</p>
        </div>
    </div>
    @else
    <div class="alert alert-warning">
        User data not available.
    </div>
    @endif

<div class="card mb-4">
    <div class="card-header bg-secondary text-white">
        Feedback Information
    </div>
    <div class="card-body">
        <h4 class="card-title mb-3 fw-bold text-primary">{{ $feedback->title }}</h4>
        
        <h6 class="text-muted mb-2">Message:</h6>
        <blockquote class="blockquote bg-light p-3 rounded border border-secondary">
            <p class="mb-0" style="white-space: pre-wrap;">{{ $feedback->message }}</p>
        </blockquote>
        
        <p class="mt-4 text-muted"><small>Submitted At: {{ $feedback->created_at->format('d M Y H:i') }}</small></p>
    </div>
</div>


    <a href="{{ route('feedback.index') }}" class="btn btn-outline-secondary">← Back to List</a>
</div>
@endsection
