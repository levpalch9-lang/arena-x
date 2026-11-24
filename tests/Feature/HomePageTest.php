<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_homepage_returns_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('ARENA X');
        $response->assertSee('Турниры.');
        $response->assertSee('Ближайшие турниры');
    }

    public function test_tournament_catalog_shows_seeded_tournaments(): void
    {
        $response = $this->get('/tournaments');

        $response->assertOk();
        $response->assertSee('ARENA OPEN #01');
        $response->assertSee('NIGHT CUP');
    }
}
