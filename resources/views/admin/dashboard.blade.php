@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Admin Dashboard</h1>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <h5 class="card-title">Total Books</h5>
                    <p class="card-text fs-1 fw-bold">{{ $totalBooks }}</p>
                    <i class="bi bi-book fs-1"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <h5 class="card-title">Total Sold</h5>
                    <p class="card-text fs-1 fw-bold">{{ $totalSold }}</p>
                    <i class="bi bi-cart-check fs-1"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-info h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <h5 class="card-title">Total Revenue</h5>
                    <p class="card-text fs-1 fw-bold">${{ number_format($totalRevenue, 2) }}</p>
                    <i class="bi bi-currency-dollar fs-1"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card text-white bg-secondary h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text fs-1 fw-bold">{{ $totalUsers }}</p>
                    <i class="bi bi-people fs-1"></i>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card text-white bg-dark h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <h5 class="card-title">Total Orders</h5>
                    <p class="card-text fs-1 fw-bold">{{ $totalOrders }}</p>
                    <i class="bi bi-bag-check fs-1"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3">
        <a href="{{ route('books.index') }}" class="btn btn-outline-primary flex-grow-1">Manage Books</a>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary flex-grow-1">Manage Users</a>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-dark flex-grow-1">Manage Orders</a>
        <a href="{{ route('feedback.index') }}" class="btn btn-outline-dark flex-grow-1">Manage Feedback</a>
    </div>
</div>
@endsection
