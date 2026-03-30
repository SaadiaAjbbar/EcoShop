<?php

use App\Models\User;
use App\Models\Product;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('user can add product to cart', function () {

    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $product = Product::factory()->create();
    $cart = $user->cart()->create();

    $response = $this->postJson('/api/cart', [
        'product_id' => $product->id,
        'quantity' => 2
    ]);

    $response->assertStatus(200);
});

it('user can add j to cart', function () {

        
    $product = Product::factory()->create();

    $response = $this->postJson('/api/cart', [
        'product_id' => $product->id,
        'quantity' => 2
    ]);

    $response->assertStatus(401);
});

