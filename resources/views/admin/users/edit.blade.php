@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit User: {{ $user->name }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('users.update', $user->id) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $user->name) }}" required maxlength="255"
            >
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" id="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $user->email) }}" required
            >
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">New Password (leave blank to keep current)</label>
            <input type="password" name="password" id="password"
                class="form-control @error('password') is-invalid @enderror"
                minlength="6"
            >
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                class="form-control"
                minlength="6"
            >
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" id="phone"
                class="form-control @error('phone') is-invalid @enderror"
                value="{{ old('phone', $user->phone) }}" maxlength="20"
            >
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-success">Update User</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection
