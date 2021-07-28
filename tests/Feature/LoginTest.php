<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseMigrations;

    public function test_login_fail()
    {
        $user = $this->createUser();
        $user->save();

        $data = [
            'email' => 'example@gmail.com',
            'password' => '123456789'
        ];

        $response = $this->post('/api/test_login', $data);
        $response->assertStatus(200);
        $response->assertJson(['status' => 'error']);
    }

    public function test_login_success()
    {
        $user = $this->createUser();
        $user->save();

        $data = [
            'email' => 'admin@gmail.com',
            'password' => '123456'
        ];

        $response = $this->post('/api/test_login', $data);
        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
    }
}
