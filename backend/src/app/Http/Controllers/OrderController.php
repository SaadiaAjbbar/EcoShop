<?php

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use App\Models\Order;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;


class OrderController extends Controller
{
    #[OA\Post(
        path: "/orders",
        summary: "Create a new order",
        tags: ["orders"],
        security:[['bearerAuth' => []]] ,
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "cart_id", type: "integer", example: 1)
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Order created"
            )
        ]
    )]

    public function store(Request $request)
    {
        $user = $request->user();
        $cart = $user->cart()->with('items.product')->first();

        $total = 0;

        foreach ($cart->items as $item) {
            $total += $item->product->price * $item->quantity;
        }

        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $total
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price
            ]);
        }

        // vider panier
        $cart->items()->delete();

        // EVENT
        event(new OrderPlaced($order));


        return response()->json($order);
    }

    #[OA\Get(
        path: "/orders",
        summary: "Get all orders for the current user",
        tags: ["orders"],
        security:[['bearerAuth' => []]] ,
        responses: [
            new OA\Response(
                response: 200,
                description: "List of orders"
            )
        ]
    )]

    public function index(Request $request)
    {
        return $request->user()->orders()->with('items.product')->get();
    }

    #[OA\Get(
        path: "/orders/{id}",
        summary: "Get an order by ID",
        tags: ["orders"],
        security:[['bearerAuth' => []]] ,
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Order details"
            )
        ]
    )]
    public function show($id)
    {
        return Order::with('items.product')->findOrFail($id);
    }

    #[OA\Put(
        path: "/orders/{id}/cancel",
        summary: "Cancel an order",
        tags: ["orders"],
        security:[['bearerAuth' => []]] ,
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Order cancelled"
            )
        ]
    )]

    public function cancel($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'cancelled']);
        return response()->json(['message' => 'Order cancelled']);
    }

    #[OA\Put(
        path: "/orders/{id}/complete",
        summary: "Complete an order",
        tags: ["orders"],
        security:[['bearerAuth' => []]] ,
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Order completed"
            )
        ]
    )]

    public function complete($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'completed']);
        return response()->json(['message' => 'Order completed']);
    }

    #[OA\Delete(
        path: "/orders/{id}",
        summary: "Delete an order",
        tags: ["orders"],
        security:[['bearerAuth' => []]] ,
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Order deleted"
            )
        ]
    )]

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return response()->json(['message' => 'Order deleted']);
    }

    #[OA\Put(
        path: "/orders/{id}",
        summary: "Update an order",
        tags: ["orders"],
        security:[['bearerAuth' => []]] ,
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "pending")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Order updated"
            )
        ]
    )]

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update($request->only('status'));
        return response()->json(['message' => 'Order updated']);
    }


}
