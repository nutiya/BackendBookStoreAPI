<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{


public function index()
{
    $totalBooks = Book::count();
    $totalSold = Book::sum('sold_count');
    $totalRevenue = Book::sum(DB::raw('sold_count * price'));

    $totalUsers = User::count();
    $totalOrders = Order::count();

    return view('admin.dashboard', compact('totalBooks', 'totalSold', 'totalRevenue', 'totalUsers', 'totalOrders'));
}
}
