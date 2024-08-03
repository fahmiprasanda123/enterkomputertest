<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_order()
    {
        $response = $this->postJson('/api/order', [
            'table_number' => 1,
            'items' => [
                ['product_id' => 1, 'quantity' => 2],
                ['product_id' => 2, 'quantity' => 1],
            ]
        ]);

        $response->assertStatus(201);
    }

    /** @test */
    public function get_bill()
    {
        // Buat data produk
        $product1 = Product::create(['name' => 'Product 1', 'category' => 'Category 1', 'variant' => 'Variant 1', 'price' => 10000]);
        $product2 = Product::create(['name' => 'Product 2', 'category' => 'Category 2', 'variant' => 'Variant 2', 'price' => 15000]);

        // Buat order dan order items
        $order = Order::create(['table_number' => 1]);
        OrderItem::create(['order_id' => $order->id, 'product_id' => $product1->id, 'quantity' => 2, 'total_price' => 20000]);
        OrderItem::create(['order_id' => $order->id, 'product_id' => $product2->id, 'quantity' => 1, 'total_price' => 15000]);

        // Lakukan request untuk mendapatkan bill
        $response = $this->getJson('/api/bill/1');

        // Pastikan response status 200 OK
        $response->assertStatus(200);

        // Pastikan struktur data yang diterima sesuai harapan
        $response->assertJson([
            'bill' => [
                ['product' => 'Product 1', 'quantity' => 2, 'total_price' => 20000],
                ['product' => 'Product 2', 'quantity' => 1, 'total_price' => 15000],
            ],
            'total' => 35000
        ]);
    }
}


