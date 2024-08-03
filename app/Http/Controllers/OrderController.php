<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Validasi request
        $request->validate([
            'table_number' => 'required|integer',
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        // Buat order baru
        $order = Order::create([
            'table_number' => $request->table_number
        ]);

        // Buat order items
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $totalPrice = $item['quantity'] * $product->price;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'total_price' => $totalPrice
            ]);
        }

        // Respon dengan data yang diinginkan
        return response()->json([
            'printers' => [
                'Printer 1',
                'Printer 2'
            ]
        ], 201);
    }

    public function getBill($tableNumber)
{
    // Ambil order berdasarkan table number
    $order = Order::where('table_number', $tableNumber)->first();

    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    // Ambil order items
    $items = $order->items()->with('product')->get();

    $bill = $items->map(function ($item) {
        return [
            'product' => $item->product->name,
            'quantity' => $item->quantity,
            'total_price' => $item->total_price
        ];
    });

    $total = $bill->sum('total_price');

    return response()->json([
        'bill' => $bill,
        'total' => $total
    ]);
}

}


