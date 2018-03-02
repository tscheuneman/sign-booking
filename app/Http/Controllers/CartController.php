<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cart;
use App\CartEntry;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function count()
    {
        $user = Auth::id();

        $cart = Cart::where('cust_id', $user)->first();
        if($cart) {
            $cartItems = CartEntry::where('cart_id', $cart->id)->count();
            return $cartItems;
        }

    }
}