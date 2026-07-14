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
            ->assertSee("Math.random() < .2 ? 'air' : 'ground'", escape: false)
            ->assertSee('const collisionX = creature.offsetLeft + creature.clientWidth * .67;', escape: false)
            ->assertSee('const minimumGap = Math.max(20, state.speed * (jumpCycle + .12) - distanceToCollision);', escape: false)
            ->assertSee('const airClearance = Math.ceil(creature.clientHeight * .88);', escape: false)
            ->assertSee("if (obstacle.dataset.lane === 'air')", escape: false)
            ->assertSee('return horizontalOverlap && state.y > 6;', escape: false)
            ->assertSee("document.addEventListener('pointerdown'", escape: false)
            ->assertSee('const obstacleTypes = [', escape: false)
            ->assertSee('obstacleTypes.filter(type => type.lane === lane)', escape: false)
            ->assertSee('state.speed = Math.min(520, state.speed + 14);', escape: false)
            ->assertSee('background: transparent;', escape: false)
            ->assertSee('box-shadow: none;', escape: false)
            ->assertSee('obstacle.style.fontSize = `${type.fontSize}px`;', escape: false)
            ->assertSee("window.addEventListener('resize', () => {\n        resetObstacle();", escape: false)
            ->assertDontSee('filter: drop-shadow', escape: false);

        $this->assertSame(20, substr_count($response->getContent(), "{ icon: '"));
        $this->assertSame(15, substr_count($response->getContent(), "lane: 'ground'"));
        $this->assertSame(5, substr_count($response->getContent(), "lane: 'air'"));
    }

    public function test_lose_sound_is_available_and_prepared_during_game_start(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('assets/lose.ogg')
            ->assertSee('const prepareLoseSound = () => {', escape: false)
            ->assertSee('audioContext ??= new AudioContext();', escape: false)
            ->assertSee('playLoseSound();', escape: false);

        $this->assertFileExists(public_path('assets/lose.ogg'));
    }
}
