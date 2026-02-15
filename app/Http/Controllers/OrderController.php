<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Mail\OrderPlaced;
use Illuminate\Support\Facades\Mail;


class OrderController extends Controller
{
public function placeOrder(Request $request)
{
    $user = Auth::user(); 

    // Validate required fields
    $request->validate([
        'shipping_address' => 'required|string',
        'payment_method' => 'required|string',
        'order_note' => 'nullable|string',
    ]);

    // Get cart items for the user
    $cartItems = CartItem::with('book')->where('user_id', $user->id)->get();

    if ($cartItems->isEmpty()) {
        return response()->json(['message' => 'Your cart is empty'], 400);
    }

    // Calculate total amount
    $totalAmount = $cartItems->sum(function ($item) {
        return $item->book->price * $item->quantity;
    });

    DB::beginTransaction();

    try {
        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $totalAmount,
            'shipping_address' => $request->shipping_address,
            'payment_method' => $request->payment_method,
            'order_note' => $request->order_note,
        ]);

        // Create order items + update book stock & sold count
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'book_id' => $item->book_id,
                'quantity' => $item->quantity,
                'price' => $item->book->price, // snapshot price
            ]);

            // Update book stock and sold count
            $book = $item->book;
            $book->sold_count += $item->quantity;
            $book->stock_quantity -= $item->quantity;

            // Ensure stock does not go below zero
            if ($book->stock_quantity < 0) {
                DB::rollBack();
                return response()->json([
                    'message' => "Not enough stock for '{$book->title}'"
                ], 400);
            }

            $book->save();
        }

        $order->load('orderItems.book', 'user');

        // Send order placed email
        Mail::to($order->user->email)->send(new OrderPlaced($order));

        // Clear user's cart
        CartItem::where('user_id', $user->id)->delete();

        DB::commit();

        return response()->json([
            'message' => 'Order placed successfully',
            'order_id' => $order->id,
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'message' => 'Failed to place order',
            'error' => $e->getMessage()
        ], 500);
    }
}


public function getOrderHistory()
{
    $user = Auth::user();

    // Get all orders with order items and related book info
    $orders = Order::with(['orderItems.book'])
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();

    // Count total number of orders the user has placed
    $totalOrders = Order::where('user_id', $user->id)->count();

    return response()->json([
        'orders' => $orders,
        'total_orders' => $totalOrders
    ]);
}

}
