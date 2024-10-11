<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
//    public function test_example()
//    {
//        $response = $this->get('/');
//
//        $response->assertStatus(200);
//    }


    public $email;
    private $adminId;

    public function  testLoginWithAdminAccount(){


        $data = [
            'email' => 'admin@gmail.com',
            'password' => '12345678',
        ];

        $user = $this->json('POST', '/api/auth/login', $data);

        $user->assertStatus(200);

    //    $user->assertSee('admin');

    //    $user->assertJson(['data' => ['role' => 'Admin']]);

        $user->assertSee(200)->assertJson(['data' => ['role' => 'Admin']]);

    //    dd($user['data']['email']);

        $this->email = $user['data']['email'];

    //    dd($this->email);

//        $this->adminId = $user['data']['id'];

        }












        public function testCredentials(){

            $data = [
                'email' => 'admin@gmail.com',
                'password' => '12345678',
            ];

            $user = $this->json('POST', '/api/auth/login', $data);

            $user->assertSee(200)->assertJson(['data' => ['role' => 'Admin']]);

//            dd($user);

        }
}
