<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pedido confirmado</title>
    <style>
        body { font-family: Arial, sans-serif; background: #fef3c7; margin: 0; padding: 0; }
        .wrapper { max-width: 580px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.08); }
        .header { background: #f59e0b; padding: 32px; text-align: center; color: #ffffff; }
        .header h1 { margin: 8px 0 0; font-size: 24px; }
        .header p { margin: 8px 0 0; font-size: 14px; opacity: .85; }
        .body { padding: 32px; }
        .order-num { font-size: 20px; font-weight: bold; color: #d97706; margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { text-align: left; font-size: 11px; color: #9ca3af; text-transform: uppercase; letter-spacing: .05em; padding: 8px 0; border-bottom: 1px solid #e5e7eb; }
        td { padding: 10px 0; font-size: 14px; color: #374151; border-bottom: 1px solid #f3f4f6; }
        .td-right { text-align: right; }
        .td-center { text-align: center; }
        .total-row td { font-weight: bold; font-size: 16px; color: #111827; border-bottom: none; padding-top: 16px; }
        .location-box { background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 18px 20px; margin-top: 24px; }
        .location-box h3 { margin: 0 0 8px; font-size: 15px; color: #92400e; }
        .location-box p { margin: 4px 0 0; font-size: 14px; color: #78350f; }
        .footer { text-align: center; padding: 24px; font-size: 12px; color: #9ca3af; background: #f9fafb; border-top: 1px solid #f3f4f6; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <img src="{{ config('app.url') }}/logoFoodtruck.png" alt="FoodTruck" style="height:64px;width:auto;margin-bottom:8px;">
        <h1>¡Pedido confirmado!</h1>
        <p>Gracias por tu compra, {{ $order->user->name }}.</p>
    </div>

    <div class="body">
        <div class="order-num">Pedido #FT-{{ $order->id }}</div>

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
                    <td colspan="2">Total pagado</td>
                    <td class="td-right" style="color:#d97706;">{{ number_format($order->total_price, 2) }} €</td>
                </tr>
            </tbody>
        </table>

        <div class="location-box">
            <h3>¿Dónde recoger tu pedido?</h3>
            @if($location)
                <p><strong>{{ $location->name }}</strong></p>
                <p>Horario de hoy: {{ \Illuminate\Support\Str::substr($location->start_time, 0, 5) }} – {{ \Illuminate\Support\Str::substr($location->end_time, 0, 5) }} h</p>
            @else
                <p>Consulta nuestra web o redes sociales para conocer la ubicación de hoy.</p>
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
