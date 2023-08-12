<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('status', '0')->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function view($id)
    {
        $orders = Order::where('id', $id)->first();

        return view('admin.orders.view', compact('orders'));
    }

    public function updateOrder(Request $request, $id)
    {
        $orders = Order::find($id);
        $orders->status = $request->input('order_status');
        $orders->update();

        return redirect('orders')->with('status', "Order Updated Successfully");
    }

    public function orderHistory()
    {
        $orders = Order::where('status', '1')->get();

        return view('admin.orders.history', compact('orders'));
    }

    public function checkout()
    {
        return view('frontend.checkout_google');
    }


    public function payment(Request $request)
    {
        $googlePay = json_decode($request['data']['paymentMethodData']['tokenizationData']['token'], true);
        $order = new Order;
        $order->user_id = auth()->user()->id;
        $order->amount = $request->amount;
        $order->discount = 0;
        $order->status = 1;
        if ($order->save()) {
            $transactions = new Transaction();
            $transactions->order_id = $order->id;
            $transactions->transaction_id = $googlePay['id'];
            $transactions->type = $request['data']['paymentMethodData']['tokenizationData']['type'] ?? '';
            $transactions->type = 1;
            $transactions->save();
            return response(['success' => true, 'url' => 'success']);
        } else {
            return response(['success' => false]);
        }
    }
}
