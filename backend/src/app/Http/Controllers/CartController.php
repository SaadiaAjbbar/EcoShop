<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class CartController extends Controller
{
    #[OA\Get(
        path: "/cart",
        summary: "Get current user's cart",
        tags: ["cart"],
        security:[['bearerAuth' => []]] ,

        responses: [
            new OA\Response(
                response: 200,
                description: "Added to cart"
            )
        ]
    )]

    public function index(Request $request)
    {
        return $request->user()->cart()->with('items.product')->first();
    }

    #[OA\Post(
        path: "/cart",
        summary: "Add item to cart",
        tags: ["cart"],
        security:[['bearerAuth' => []]] ,
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "product_id", type: "integer", example: 1),
                        new OA\Property(property: "quantity", type: "integer", example: 2)
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Cart items"
            )
        ]
    )]


    public function store(Request $request)
    {
        $cart = Auth::user()->cart;

        $item = $cart->items()->where('product_id', $request->product_id)->first();

        if ($item) {
            $item->quantity += $request->quantity;
            $item->save();
        } else {
            $cart->items()->create($request->only('product_id', 'quantity'));
        }

        return response()->json(['message' => 'Added to cart']);
    }

    #[OA\Put(
        path: "/cart/{item_id}",
        summary: "Update cart item quantity",
        tags: ["cart"],
        security:[['bearerAuth' => []]] ,
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "quantity", type: "integer", example: 2)
                    ]
                )
            )
        ),
        parameters: [
            new OA\Parameter(
                name: "item_id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Updated",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: "message", type: "string", example: "Updated")
                        ]
                    )
                )
            )
        ]
    )]


    public function update(Request $request)
    {
        $item = CartItem::findOrFail($request->item_id);
        $item->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Updated']);
    }

    public function remove($id)
    {
        CartItem::findOrFail($id)->delete();
        return response()->json(['message' => 'Removed']);
    }
}
