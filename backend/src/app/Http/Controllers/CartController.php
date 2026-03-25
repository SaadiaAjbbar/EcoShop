<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->cart()->with('items.product')->first();
    }

    public function add(Request $request)
    {
        $cart = $request->user()->cart;

        $item = $cart->items()->where('product_id', $request->product_id)->first();

        if ($item) {
            $item->quantity += $request->quantity;
            $item->save();
        } else {
            $cart->items()->create($request->only('product_id', 'quantity'));
        }

        return response()->json(['message' => 'Added to cart']);
    }

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
