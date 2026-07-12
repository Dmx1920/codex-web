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
            ->assertSee('@keyframes bob { to { translate: 0 -5px;', escape: false)
            ->assertSee('scaleX(-1)', escape: false)
            ->assertSee('const paddingX = a.width * .33;', escape: false)
            ->assertSee('game.clientWidth + 20 + Math.random() * 100', escape: false)
            ->assertSee("document.addEventListener('pointerdown'", escape: false)
            ->assertSee('const obstacleTypes = [', escape: false)
            ->assertSee('obstacleTypes.length', escape: false)
            ->assertSee('state.speed = Math.min(520, state.speed + 14);', escape: false)
            ->assertSee('background: transparent;', escape: false)
            ->assertSee('box-shadow: none;', escape: false)
            ->assertSee('font-size: 40px;', escape: false)
            ->assertDontSee('filter: drop-shadow', escape: false);

        $this->assertSame(15, substr_count($response->getContent(), "{ icon: '"));
    }
}
