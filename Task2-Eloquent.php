<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'items.product'])->get();
        $orderIds = $orders->pluck('id')->toArray();
        $lastAddedToCartItems = CartItem::whereIn('order_id', $orderIds)
            ->select(DB::raw('MAX(created_at) as last_added_to_cart, order_id'))
            ->groupBy('order_id')
            ->pluck('last_added_to_cart', 'order_id')
            ->toArray();

        $completedOrders = Order::whereIn('id', $orderIds)
            ->where('status', 'completed')
            ->pluck('completed_at', 'id')
            ->toArray();

        $orderData = $orders->map(function ($order) use ($lastAddedToCartItems, $completedOrders) {
            $totalAmount = $order->items->sum(fn ($item) => $item->price * $item->quantity);
            $itemsCount = $order->items->count();
            $completedAt = $completedOrders[$order->id] ?? null;

            return [
                'order_id' => $order->id,
                'customer_name' => $order->customer->name,
                'total_amount' => $totalAmount,
                'items_count' => $itemsCount,
                'last_added_to_cart' => $lastAddedToCartItems[$order->id] ?? null,
                'completed_order_exists' => isset($completedOrders[$order->id]),
                'created_at' => $order->created_at,
                'completed_at' => $completedAt,
            ];
        })->toArray();

        usort($orderData, function ($a, $b) {
            return strtotime($b['completed_at'] ?? 'now') - strtotime($a['completed_at'] ?? 'now');
        });

        return view('orders.index', ['orders' => $orderData]);
    }
}

