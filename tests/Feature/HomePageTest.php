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

    public function test_tournament_catalog_shows_demo_tournaments_without_database_credentials(): void
    {
        $response = $this->get('/tournaments');

        $response->assertOk();
        $response->assertSee('ARENA OPEN #01');
        $response->assertSee('NIGHT CUP');
        $response->assertSee('Демонстрационный режим');
    }
}
