@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Publisher: {{ $publisher->name }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('publishers.update', $publisher->id) }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Publisher Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $publisher->name) }}"
                required maxlength="255"
            >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Update Publisher</button>
        <a href="{{ route('publishers.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection
