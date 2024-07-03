<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeLoginRegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_home_page_can_be_rendered()
    {
        $response = $this->get(route('index'));

        $response->assertStatus(200);
        $response->assertSeeText('register');
        $response->assertSeeText('event');
        $response->assertSeeTextInOrder(['log', 'in']);
    }
    public function test_the_login_page_can_be_rendered()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);

        $response->assertSeeText('email');
        $response->assertSeeText('password');
        $response->assertSeeTextInOrder(['log', 'in']);
        $response->assertSeeText('cancel');
    }
    public function test_the_register_page_can_be_rendered()
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);

        $response->assertSeeText('email');
        $response->assertSeeText('password');
        $response->assertSeeTextInOrder(['confirm', 'password']);
        $response->assertSeeText('register');
        $response->assertSeeText('cancel');
    }
}
