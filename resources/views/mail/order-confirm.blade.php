<!DOCTYPE html>
<head>
    <title>Order Confirmation</title>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
</head>
<body>

<p>Dear {{ $order->firstname }},</p>
<p>Thank you for your drug order on Nello. This mail is to confirm that we have received and have started processing
    your order.</p>
<p>Order reference: {{ $order->order_ref }}</p>
<p>We will send you a confirmatory email once payment has been made.</p>

<h3>Here's a list of item(s) you've ordered</h3>
<table>
    <tr>
        <th>#</th>
        <th>Drug</th>
        <th>Brand</th>
        <th>Category</th>
        <th>Quantity</th>
        <th>Single Price</th>
        <th>Total Price</th>
    </tr>
    @foreach($order->items as $key => $item)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $item->drug->name ?? 'Unavailable' }}</td>
            <td>{{ $item->drug->brand ?? 'Unavailable' }}</td>
            <td>{{ $item->drug->category->name ?? 'Unavailable' }}</td>
            <td>{{ $item->quantity ?? 0 }}</td>
            <td>N{{ $item->drug->price ?? 0 }}</td>
            <td>N{{ $item->price ?? 0 }}</td>
        </tr>
    @endforeach
</table>
<h3>Total Price: N{{ $order->amount ?? 0 }}</h3>
<br>
<hr/>
<p>From the Nello team</p>
<p>www.asknello.com</p>

</body>
