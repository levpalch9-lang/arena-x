<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_homepage_returns_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('ARENA X');
        $response->assertSee('Время выйти');
    }
}
