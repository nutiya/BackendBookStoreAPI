@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Language: {{ $language->name }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('languages.update', $language->id) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Language Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $language->name) }}"
                required maxlength="255"
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Update Language</button>
        <a href="{{ route('languages.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection
