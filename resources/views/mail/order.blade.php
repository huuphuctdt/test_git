<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Mail</title>
</head>
<body>
    <h1>{{ $data['content'] }}</h1>
    <div>Name : {{ $data['name'] }}</div>
    <div>Email : {{ $data['email'] }}</div>
    <div>Order Total : {{ $data['order_total'] }}</div>
    <table style="border: 1px solid black;">
        <tr>
            <th>STT</th>
            <th>Product Name</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Price Total</th>
        </tr>
        
        @php $total = 0; @endphp

        @foreach ($data['order_products'] as $product)
            <tr>    
                <td>{{ $loop->iteration }}</td>
                <td>{{ $product['name'] }}</td>
                <td>{{ $product['qty'] }}</td>
                <td>{{ $product['price'] }}</td>

                @php
                $totalRow = $product['qty'] * $product['price'];
                $total += $totalRow;
                 @endphp

                <td>{{ number_format($totalRow, 2) }}</td>
            </tr>
        @endforeach

        <tr>
            <td>Total : </td>
            <td colspan="4">{{ number_format($total,2) }}</td>
        </tr>
    </table>
</body>
</html>