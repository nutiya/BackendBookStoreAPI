<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; margin:0; padding:20px;">
    <div style="max-width:600px; margin: auto; background: white; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); padding: 30px;">
        <h2 style="color: #2c3e50; margin-bottom: 10px;">Thank you for your order, {{ $order->user->name }}!</h2>
        <p style="color: #34495e; font-size: 16px;">Your order has been placed successfully. Below are your order details:</p>

        <div style="margin: 20px 0;">
            <strong style="display: block; margin-bottom: 6px; color: #2c3e50;">Order Information</strong>
            <ul style="list-style-type: none; padding: 0; color: #555;">
                <li><strong>Order ID:</strong> {{ $order->id }}</li>
                <li><strong>Customer Name:</strong> {{ $order->user->name }}</li>
                <li><strong>Shipping Address:</strong> {{ $order->shipping_address }}</li>
            </ul>
        </div>

        <h3 style="color: #2c3e50; border-bottom: 2px solid #2980b9; padding-bottom: 6px;">Order Items</h3>
        <table role="presentation" style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
            <thead>
                <tr style="background-color: #2980b9; color: white;">
                    <th style="padding: 10px; text-align: left;">Book Name</th>
                    <th style="padding: 10px; text-align: center;">Quantity</th>
                    <th style="padding: 10px; text-align: right;">Price</th>
                    <th style="padding: 10px; text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderItems as $item)
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 10px;">{{ $item->book->title }}</td>
                    <td style="padding: 10px; text-align: center;">{{ $item->quantity }}</td>
                    <td style="padding: 10px; text-align: right;">${{ number_format($item->price, 2) }}</td>
                    <td style="padding: 10px; text-align: right;">${{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p style="text-align: right; font-size: 18px; font-weight: bold; color: #2c3e50; margin-bottom: 30px;">
            Total Amount: ${{ number_format($order->total_amount, 2) }}
        </p>

        <p style="color: #34495e; font-size: 15px;">
            If you have any questions about your order, feel free to reply to this email or contact our support team.
        </p>
        <p style="color: #34495e; font-size: 15px;">
            Tel: 061693460
        </p>

        <p style="color: #34495e; font-size: 15px; margin-top: 40px;">
            Thank you for shopping with us!<br>
            <strong style="color: #2980b9;">The BookStore Team</strong>
        </p>
    </div>
</body>
</html>
