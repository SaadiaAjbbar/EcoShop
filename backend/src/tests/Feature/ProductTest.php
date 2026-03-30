<?php

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;


uses(RefreshDatabase::class);

/**
 * 🧪 get all products
 */
it('can get products list', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    Product::factory()->count(3)->create();

    $response = $this->getJson('/api/products');

    $response->assertStatus(200);
});


/**
 * 🧪 create product (admin)
 */
it('admin can create product', function () {

    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $category = Category::factory()->create();

    $response = $this->postJson('/api/products', [
        'name' => 'test product',
        'price' => 100,
        'stock' => 10,
        'category_id' => $category->id
    ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('products', [
        'name' => 'test product'
    ]);
});
