<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected function getCart() {
        return Cart::firstOrCreate(['user_id'=>auth()->id()]);
    }

    public function show() {
        $cart = $this->getCart()->load('items.medicine');
        return new CartResource($cart);
    }

    public function add(AddToCartRequest $req) {
        $cart = $this->getCart();
        $item = $cart->items()->updateOrCreate(
            ['medicine_id'=>$req->medicine_id],
            ['quantity'=>$req->quantity]
        );
        return response()->json(new CartResource($cart->load('items.medicine')));
    }

    public function remove($id) {
        $cart = $this->getCart();
        $cart->items()->where('id',$id)->delete();
        return response()->json(new CartResource($cart->load('items.medicine')));
    }
}
