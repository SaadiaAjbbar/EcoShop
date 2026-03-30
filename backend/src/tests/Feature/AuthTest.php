<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

/**
 * TEST REGISTER
 */
it('user can register', function () {

    // كنصيفط request POST لل API
    $response = $this->postJson('/api/register', [
        'name' => 'test',
        'email' => 'test@test.com',
        'password' => '123456'
    ]);

    // كنأكد أن response ناجح
    $response->assertStatus(200);

    // كنأكد أن user تسجل ف database
    $this->assertDatabaseHas('users', [
        'email' => 'test@test.com'
    ]);
});


/**
 * 🧪 TEST LOGIN
 */
it('user can login', function () {

    // كنصاوب user ف database
    $user = User::create([
        'name' => 'test',
        'email' => 'test@test.com',
        'password' => Hash::make('123456')
    ]);

    // كنصيفط request login
    $response = $this->postJson('/api/login', [
        'email' => 'test@test.com',
        'password' => '123456'
    ]);

    // كنأكد النجاح ووجود token
    $response->assertStatus(200)
             ->assertJsonStructure(['token']);
});


/**
 * 🧪 TEST ACCESS WITHOUT TOKEN
 */
it('cannot access profile without token', function () {

    // كنحاول ندخل route محمية
    $response = $this->getJson('/api/profile');

    // خاص يرجع unauthorized
    $response->assertStatus(401);
});


/**
 * 🧪 TEST ACCESS WITH TOKEN
 */
it('can access profile with token', function () {

// Créer un utilisateur avec factory
    $user = \App\Models\User::factory()->create();

    // Générer token
    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->getJson('/api/profile');

    $response->assertStatus(200)
             ->assertJson([
                 'email' => $user->email,
             ]);

});
