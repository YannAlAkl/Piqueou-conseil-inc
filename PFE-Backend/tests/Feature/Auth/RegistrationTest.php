<?php

use Illuminate\Support\Str;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $email = 'test-' . Str::uuid() . '@example.com';

    $response = $this->post('/register', [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => $email,
        'password' => 'password',
        'password_confirmation' => 'password',
        'company_name' => 'Acme Corp',
        'phone' => '0600000000',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect('/dashboard');
    $this->assertDatabaseHas('users', [
        'email' => $email,
        'first_name' => 'Test',
    ]);
});
