@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Orders</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>User</th>
                <th>Total Amount</th>
                <th>Shipping Address</th>
                <th>Payment Method</th>
                <th>Created At</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $orders->firstItem() + $loop->index }}</td>
                    <td>{{ $order->user->name ?? '-' }}</td>
                    <td>${{ number_format($order->total_amount, 2) }}</td>
                    <td>{{ $order->shipping_address }}</td>
                    <td>{{ $order->payment_method }}</td>
                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">No orders found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">{{ $orders->links() }}</div>
</div>
@endsection
