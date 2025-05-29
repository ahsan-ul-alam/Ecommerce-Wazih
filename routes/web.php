<?php

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/cart', function () {
    return Inertia::render('Cart');
});

Route::get('details/{id}/{name}', function ($id, $name) {
    $response = Http::withOptions(['verify' => false])->get('https://dummyjson.com/products/1');
    $product = $response->json();

    return Inertia::render('ProductDetails', [
        'product' => $product,
        'id' => $id,
        'title' => $name,
    ]);
})->name('product.details');

Route::get('/checkout', function () {
    return Inertia::render('Checkout');
})->name('checkout');


Route::post('/checkout', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'mobile' => 'required|string|max:20',
        'address' => 'required|string',
        'district' => 'required|string',
        'cart' => 'required|array|min:1',
        'total_amount' => 'required|numeric|min:0',
    ]);

    // Step 1: Calculate total from cart
    $calculatedTotalAmount = 0;
    foreach ($validated['cart'] as $item) {
        if (!isset($item['price']) || !isset($item['quantity'])) {
            return back()->withErrors('কার্ট আইটেমে প্রাইস বা কোয়ান্টিটি নেই।');
        }
        $calculatedTotalAmount += $item['price'] * $item['quantity'];
    }

    // Step 2: Add delivery charge
    $deliveryCharge = $validated['district'] === 'Dhaka' ? 70 : 130;
    $calculatedTotalAmountWithDelivery = $calculatedTotalAmount + $deliveryCharge;

    // Step 3: Verify total matches
    if ((float)$request->input('total_amount') !== (float)$calculatedTotalAmountWithDelivery) {
        return back()->withErrors('মোট টাকার পরিমাণ সঠিক নয়। দয়া করে আবার চেষ্টা করুন।');
    }

    // Step 4: Save order
    $order = Order::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'mobile' => $validated['mobile'],
        'address' => $validated['address'],
        'district' => $validated['district'],
        'cart' => json_encode($validated['cart']),
        'total_amount' => $calculatedTotalAmountWithDelivery,
        'delivery_charge' => $deliveryCharge,
        'order_number' => uniqid(),
    ]);



return redirect()->route('order.summary', $order->id);
});

Route::get('/order/summary/{id}', function ($id) {
    $order = Order::findOrFail($id);
    return Inertia::render('OrderSummary', [
        'order' => $order
    ]);
})->name('order.summary');



Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
