<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Confirmation de Commande</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body style="font-family: sans-serif; background-color: #f4f4f7; padding: 20px;">

    <div
        style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">

        <div style="background-color: #4f46e5; padding: 30px; text-align: center; color: #ffffff;">
            <h1 style="margin: 0; font-size: 24px;">Merci pour votre commande !</h1>
            <p style="margin-top: 10px; opacity: 0.9;">Commande #{{ $order->id }}</p>
        </div>

        <div style="padding: 30px;">
            <p style="font-size: 16px; color: #374151;">Bonjour <strong>{{ $order->user->name }}</strong>,</p>
            <p style="color: #6b7280; line-height: 1.5;">Nous avons bien reçu votre commande. Voici un récapitulatif de
                vos achats :</p>

            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr style="border-bottom: 2px solid #e5e7eb; text-align: left;">
                        <th style="padding: 10px 0; color: #374151;">Produit</th>
                        <th style="padding: 10px 0; color: #374151;">Qté</th>
                        <th style="padding: 10px 0; color: #374151; text-align: right;">Prix</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 15px 0; color: #4b5563;">{{ $item->product_name }}</td>
                            <td style="padding: 15px 0; color: #4b5563;">{{ $item->quantity }}</td>
                            <td style="padding: 15px 0; color: #4b5563; text-align: right;">
                                {{ number_format($item->price, 2) }} DH</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="padding: 20px 0 5px 0; font-weight: bold; color: #374151;">Total</td>
                        <td
                            style="padding: 20px 0 5px 0; font-weight: bold; color: #4f46e5; text-align: right; font-size: 18px;">
                            {{ number_format($order->total_price, 2) }} DH
                        </td>
                    </tr>
                </tfoot>
            </table>

            <div style="margin-top: 40px; text-align: center;">
                <a href="{{ url('/orders/' . $order->id) }}"
                    style="background-color: #4f46e5; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
                    Suivre ma commande
                </a>
            </div>
        </div>

        <div style="background-color: #f9fafb; padding: 20px; text-align: center; color: #9ca3af; font-size: 12px;">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
        </div>
    </div>

</body>

</html>
