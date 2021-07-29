<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseMigrations;

    public function test_register_success()
    {
        $data = [
            'name' => 'admin',
            'email' => 'admin2@gmail.com',
            'password' => '123456789'
        ];
        $response = $this->post('api/test_register',$data);
        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
    }

    public function test_register_with_name_null()
    {
        $data = [
            'name' => null,
            'email' => 'admin2@gmail.com',
            'password' => '123456'
        ];

        $response = $this->post('api/test_register',$data);
        $response->assertStatus(200);
        $response->assertJson(['status' => 'error']);
    }

    public function test_register_with_email_null()
    {
        $data = [
            'name' => 'admin',
            'email' => null,
            'password' => '123456'
        ];

        $response = $this->post('api/test_register',$data);
        $response->assertStatus(200);
        $response->assertJson(['status' => 'error']);
    }

    public function test_register_with_password_null()
    {
        $data = [
            'name' => 'admin',
            'email' => 'admin2@gmail.com',
            'password' => null,
        ];

        $response = $this->post('api/test_register',$data);
        $response->assertStatus(200);
        $response->assertJson(['status' => 'error']);
    }
}
