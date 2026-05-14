<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>¡Pedido listo!</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fef3c7;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            max-width: 580px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .08);
        }

        .header {
            background: #16a34a;
            padding: 32px;
            text-align: center;
            color: #ffffff;
        }

        .header h1 {
            margin: 8px 0 0;
            font-size: 24px;
        }

        .header p {
            margin: 8px 0 0;
            font-size: 14px;
            opacity: .85;
        }

        .body {
            padding: 32px;
        }

        .order-num {
            font-size: 20px;
            font-weight: bold;
            color: #15803d;
            margin-bottom: 16px;
        }

        .ready-badge {
            display: inline-block;
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            text-align: left;
            font-size: 11px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .05em;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        td {
            padding: 10px 0;
            font-size: 14px;
            color: #374151;
            border-bottom: 1px solid #f3f4f6;
        }

        .td-right {
            text-align: right;
        }

        .td-center {
            text-align: center;
        }

        .total-row td {
            font-weight: bold;
            font-size: 16px;
            color: #111827;
            border-bottom: none;
            padding-top: 16px;
        }

        .location-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            padding: 18px 20px;
            margin-top: 24px;
        }

        .location-box h3 {
            margin: 0 0 8px;
            font-size: 15px;
            color: #14532d;
        }

        .location-box p {
            margin: 4px 0 0;
            font-size: 14px;
            color: #166534;
        }

        .footer {
            text-align: center;
            padding: 24px;
            font-size: 12px;
            color: #9ca3af;
            background: #f9fafb;
            border-top: 1px solid #f3f4f6;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="header">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('logoFoodtruck.png'))) }}" alt="FoodTruck" style="height:64px;width:auto;margin-bottom:8px;">
            <h1>¡Tu pedido está listo! 🎉</h1>
            <p>Ya puedes pasar a recogerlo, {{ $order->user->name }}.</p>
        </div>

        <div class="body">
            <div class="order-num">Pedido #FT-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</div>
            <div class="ready-badge">✅ Listo para recoger</div>

            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th class="td-center">Cant.</th>
                        <th class="td-right">Precio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ is_array($item->product->name) ? ($item->product->name[app()->getLocale()] ?? $item->product->name['es'] ?? $item->product->name['en'] ?? reset($item->product->name)) : $item->product->name }}</td>
                        <td class="td-center">{{ $item->quantity }}</td>
                        <td class="td-right">{{ number_format($item->price * $item->quantity, 2) }} €</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="2">Total</td>
                        <td class="td-right" style="color:#15803d;">{{ number_format($order->total_price, 2) }} €</td>
                    </tr>
                </tbody>
            </table>

            <div class="location-box">
                <h3>📍 ¿Dónde recogerlo?</h3>
                @if($location)
                <p><strong>{{ $location->name }}</strong></p>
                <p>Horario de hoy: {{ \Illuminate\Support\Str::substr($location->start_time, 0, 5) }} – {{ \Illuminate\Support\Str::substr($location->end_time, 0, 5) }} h</p>
                @else
                <p>Consulta nuestra web o redes sociales para la ubicación de hoy.</p>
                @endif
            </div>
        </div>

        <div class="footer">
            FoodTruck · Comida fresca y artesanal en la calle<br>
            Si tienes alguna duda, contáctanos desde nuestra web.
        </div>
    </div>
</body>

</html>