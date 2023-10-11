<!DOCTYPE html>
<html>
<head>
    <title>Hóa đơn đặt hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
        }

        .header {
            background-color: #3498db;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 20px;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Hóa đơn đặt hàng</h1>
    </div>

    <div class="content">
        <p>Cảm ơn bạn đã đặt hàng. Dưới đây là chi tiết đơn hàng của bạn:</p>

        <p>Mã số đơn hàng: {{ $order->number }}</p>
        <p>Tổng số tiền: {{ $order->total_price }} đ</p>

        <p>Cảm ơn bạn đã chọn sản phẩm của chúng tôi!</p>

        <a class="button" href="{{ url('/') }}">Xem Website</a>
    </div>
</div>
</body>
</html>
