<?php

namespace Tests\Feature;

use Tests\TestCase;

class LoginPageTest extends TestCase
{
    public function test_home_page_displays_login_and_registration_forms(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Sign in')
            ->assertSee('Create an account');
    }
}
