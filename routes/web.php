<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = [
        [
            'id' => 1,
            'category' => 'cake coklat',
            'title' => 'cake coklat',
            'price' => 6.50,
            'img' => 'cake1.jpg'
        ],
        [
            'id' => 2,
            'category' => 'Strawberry Tart',
            'title' => 'Vanilla Strawberry Cake',
            'price' => 7.00,
            'img' => 'cake2.jpg'
        ],
        [
            'id' => 3,
            'category' => 'Cheese Cake',
            'title' => 'Cheese Cake with Berry',
            'price' => 8.00,
            'img' => 'cake3.jpg'
        ],
        // tambah produk lain bila perlu...
    ];

    return view('menu', compact('products'));
});
