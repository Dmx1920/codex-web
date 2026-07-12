<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_game_page_is_available(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('Весёлый сущ')
            ->assertSee('data-game-shell', escape: false)
            ->assertSee('assets/sush.gif');
    }

    public function test_jump_controls_are_protected_from_known_regressions(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('if (event.repeat) return;', escape: false)
            ->assertSee('jumpVelocity: 820', escape: false)
            ->assertSee('@keyframes bob { to { translate: 0 -5px;', escape: false);
    }
}
