<?php
use App\Models\User;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can get categories', function () {

    $user = User::factory()->create();

    Sanctum::actingAs($user);

    Category::factory()->count(3)->create();

    $response = $this->getJson('/api/categories');

    $response->assertStatus(200);
});

it('admin can create category', function () {

    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/categories', [
        'name' => 'Eco'
    ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('categories', [
        'name' => 'Eco'
    ]);
});
