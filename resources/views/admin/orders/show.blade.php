@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Order Details <span class="text-primary">#{{ $order->id }}</span></h1>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-info text-white fw-semibold">
            Order Information
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <p><strong>User:</strong> {{ $order->user->name ?? '-' }}</p>
                    <p><strong>Email:</strong> {{ $order->user->email ?? '-' }}</p>
                    <p><strong>Phone:</strong> {{ $order->user->phone ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Shipping Address:</strong> {{ $order->shipping_address }}</p>
                    <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>
                    <p><strong>Total Amount:</strong> <span class="text-success fs-5">${{ number_format($order->total_amount, 2) }}</span></p>
                    <p><strong>Created At:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mb-3">Order Items</h3>
    @if($order->orderItems->count())
    <div class="table-responsive shadow-sm">
<table class="table table-striped table-bordered align-middle">
    <thead class="table-dark">
        <tr>
            <th scope="col" style="width: 50px;">#</th>
            <th scope="col" style="width: 80px;">Image</th>
            <th scope="col">Book Title</th>
            <th scope="col" style="width: 100px;">Quantity</th>
            <th scope="col" style="width: 120px;">Price</th>
            <th scope="col" style="width: 120px;">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->orderItems as $item)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>
                @if($item->book && $item->book->image_url)
                    <img src="{{ $item->book->image_url }}" alt="{{ $item->book->title }}" style="max-width: 60px; height: auto; object-fit: contain; border-radius: 4px;">
                @else
                    <img src="{{ asset('images/no-image.png') }}" alt="No Image" style="max-width: 60px; height: auto; border-radius: 4px;">
                @endif
            </td>

            <td>{{ $item->book->title ?? '-' }}</td>
            <td class="text-center">{{ $item->quantity }}</td>
            <td>${{ number_format($item->price, 2) }}</td>
            <td class="fw-semibold text-success">${{ number_format($item->quantity * $item->price, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

    </div>
    @else
        <p class="text-muted fst-italic">No order items found.</p>
    @endif

    <a href="{{ route('orders.index') }}" class="btn btn-outline-primary mt-4">
        <i class="bi bi-arrow-left-circle me-2"></i>Back to Orders
    </a>
</div>
@endsection
