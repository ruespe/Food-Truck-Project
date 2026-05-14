<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nuevo pedido</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 0; }
        .wrapper { max-width: 580px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,.08); }
        .header { background: #dc2626; padding: 28px 32px; text-align: center; color: #ffffff; }
        .header h1 { margin: 8px 0 0; font-size: 20px; }
        .body { padding: 32px; }
        .section-label { font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: .05em; color: #6b7280; margin-bottom: 8px; }
        .info-box { background: #f8fafc; border-radius: 10px; padding: 14px 18px; margin-bottom: 24px; font-size: 14px; color: #374151; line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th { text-align: left; font-size: 11px; color: #9ca3af; text-transform: uppercase; letter-spacing: .05em; padding: 8px 0; border-bottom: 1px solid #e5e7eb; }
        td { padding: 12px 0; font-size: 15px; font-weight: bold; color: #111827; border-bottom: 1px solid #f3f4f6; }
        td.qty { text-align: center; }
        .total-line { text-align: center; font-size: 14px; color: #6b7280; margin-bottom: 24px; }
        .total-line strong { color: #d97706; font-size: 18px; }
        .btn-wrap { text-align: center; margin-bottom: 8px; }
        .btn { display: inline-block; background: #f59e0b; color: #ffffff; text-decoration: none; padding: 14px 36px; border-radius: 999px; font-weight: bold; font-size: 15px; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #9ca3af; background: #f9fafb; border-top: 1px solid #f3f4f6; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <img src="{{ config('app.url') }}/logoFoodtruck.png" alt="FoodTruck" style="height:48px;width:auto;margin-bottom:8px;filter:brightness(0) invert(1);">
        <h1>Nuevo pedido recibido: #FT-{{ $order->id }}</h1>
    </div>

    <div class="body">
        <p class="section-label">Datos del cliente</p>
        <div class="info-box">
            <strong>{{ $order->user->name }}</strong><br>
            {{ $order->user->email }}
        </div>

        <p class="section-label">Productos a preparar</p>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th class="qty">Cantidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ is_array($item->product->name) ? ($item->product->name[app()->getLocale()] ?? $item->product->name['es'] ?? $item->product->name['en'] ?? reset($item->product->name)) : $item->product->name }}</td>
                    <td class="qty">× {{ $item->quantity }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total-line">
            Total cobrado: <strong>{{ number_format($order->total_price, 2) }} €</strong>
        </p>

        <div class="btn-wrap">
            <a class="btn" href="{{ config('app.url') }}/admin/orders/{{ $order->id }}">
                Ver pedido en el panel →
            </a>
        </div>
    </div>

    <div class="footer">Panel de administración · FoodTruck</div>
</div>
</body>
</html>
