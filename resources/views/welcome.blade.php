<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="{{ asset('bootstrap.min.css') }}" rel="stylesheet">

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        <div style="padding: 20px;width: 80%">
            <h1>PO: {{ $users[0]['Document No_'] }}</h1>
            <h2>{{ $headers['Buy-from Vendor Name'] }}</h2>
            <h5>Order Date: {{ substr($headers['Order Date'], 0, 10) }}</h5>
            <h5>Document Date: {{ substr($headers['Document Date'], 0, 10) }}</h5>

            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th>Line No_</th>
                    <th>No_</th>
                    <th>Variant Code</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit of Measure</th>
                    <th>Direct Unit Cost</th>
                    <th>Amount Including VAT</th>
                    <th>Quantity Received</th>
                    <th>Quantity Invoiced</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user['Line No_'] }}</td>
                        <td>{{ $user['No_'] }}</td>
                        <td>{{ $user['Variant Code'] }}</td>
                        <td>{{ $user['Description'] }}</td>
                        <td>{{ number_format($user['Quantity']) }}</td>
                        <td>{{ $user['Unit of Measure'] }}</td>
                        <td>¥ {{ number_format($user['Direct Unit Cost'], 2) }}</td>
                        <td>¥ {{ number_format($user['Amount Including VAT'], 2) }}</td>
                        <td>{{ intval($user['Quantity Received']) }}</td>
                        <td>{{ intval($user['Quantity Invoiced']) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <h2 style="float: right">¥ {{ number_format($users->sum('Amount Including VAT'), 2) }}</h2>
        </div>

    </body>
</html>
