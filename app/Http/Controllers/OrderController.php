<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Mail\LowStockAlert;
use App\Mail\NewOrderNotification;
use App\Mail\OrderConfirmation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function store() {

        $cart = Cart::with('items.medicine')->where('user_id', auth()->id())->first();

        if (!$cart) {
            return response()->json([
                'message' => 'No cart found for the user.'
            ], 404);
        }

        if ($cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Your cart is empty. Cannot place an order.'
            ], 400);
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'total_price' => 0,
            'status' => 'processing',
        ]);

        $total = 0;
        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'medicine_id' => $item->medicine_id,
                'quantity' => $item->quantity,
                'price' => $item->medicine->price
            ]);

            $total += $item->quantity * $item->medicine->price;

        }

        $order->update(['total_price' => $total]);

        Mail::to($order->user->email)->send(new OrderConfirmation($order));


        $admins = User::role('admin')->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new NewOrderNotification($order));
        }
        $cart->items()->delete();

        return new OrderResource($order->load('items.medicine', 'user'));
    }


    public function myOrders() {
        return OrderResource::collection(
            Order::with('items.medicine','user')
                 ->where('user_id',auth()->id())
                 ->orderBy('created_at','desc')
                 ->get()
        );
    }


    public function processingOrders() {
        return OrderResource::collection(
            Order::with('items.medicine','user')
                 ->where('status','processing')
                 ->orderBy('created_at','desc')
                 ->get()
        );
    }


     public function index() {
        return OrderResource::collection(
            Order::with('items.medicine','user')->get()
        );
    }


    public function updateStatus(UpdateOrderStatusRequest $req, $id)
    {
        $order = Order::with('items.medicine')->findOrFail($id);

        if ($order->status === 'completed') {
            return response()->json([
                'message' => 'Order already completed. Status cannot be changed.'
            ], 400);
        }

        if ($req->status === 'completed') {
            foreach ($order->items as $item) {
                $medicine = $item->medicine;
                $medicine->decrement('quantity', $item->quantity);


                // reload the medicine to get updated quantity
                $medicine = $item->medicine->fresh();

                // send alert if quantity < 10
                if ($medicine->quantity < 10) {
                    $admins = User::role('admin')->get();
                    foreach ($admins as $admin) {
                        Mail::to($admin->email)->send(new LowStockAlert($medicine));
                    }
                }
            }
        }

        $order->update(['status' => $req->status]);

        return new OrderResource($order->load('items.medicine', 'user'));
    }

    public function destroy($id)
    {
        $order = Order::where('id', $id)
                    ->where('user_id', auth()->id())
                    ->first();

        if (!$order) {
            return response()->json([
                'message' => 'Order not found or does not belong to you.'
            ], 404);
        }

        if ($order->status !== 'processing') {
            return response()->json([
                'message' => 'Only orders with status processing can be deleted.'
            ], 403);
        }

        $order->items()->delete();
        $order->delete();

        return response()->json([
            'message' => 'Order deleted successfully.'
        ]);
    }


}
