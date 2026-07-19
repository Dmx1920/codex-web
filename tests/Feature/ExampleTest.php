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
            ->assertSee('obstacleTypes.filter(candidate => candidate.lane === lane)', escape: false)
            ->assertSee('state.speed = Math.min(520, state.speed + 14);', escape: false)
            ->assertSee('background: transparent;', escape: false)
            ->assertSee('box-shadow: none;', escape: false)
            ->assertSee('obstacle.style.fontSize = `${type.fontSize}px`;', escape: false)
            ->assertSee("window.addEventListener('resize', () => {\n        positionObstacle();", escape: false)
            ->assertDontSee('filter: drop-shadow', escape: false);

        $this->assertSame(20, substr_count($response->getContent(), "{ icon: '"));
        $this->assertSame(16, substr_count($response->getContent(), "lane: 'ground'"));
        $this->assertSame(5, substr_count($response->getContent(), "lane: 'air'"));
    }

    public function test_lose_sound_is_available_and_prepared_during_game_start(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('assets/lose.ogg')
            ->assertSee('const prepareSounds = () => {', escape: false)
            ->assertSee('audioContext ??= new AudioContext();', escape: false)
            ->assertSee('playSound(loseSoundBufferPromise).catch(() => {});', escape: false);

        $this->assertFileExists(public_path('assets/lose.ogg'));
    }

    public function test_jump_sound_is_available_and_plays_only_for_an_accepted_jump(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('assets/jump.ogg')
            ->assertSee('jumpSoundBufferPromise ??= loadSound(jumpSound);', escape: false)
            ->assertSee('playSound(jumpSoundBufferPromise).catch(() => {});', escape: false);

        $content = $response->getContent();
        $groundedCheck = strpos($content, 'if (state.y <= 1 && state.velocity === 0) {');
        $playJumpSound = strpos($content, 'playSound(jumpSoundBufferPromise).catch(() => {});');

        $this->assertNotFalse($groundedCheck);
        $this->assertNotFalse($playJumpSound);
        $this->assertGreaterThan($groundedCheck, $playJumpSound);
        $this->assertFileExists(public_path('assets/jump.ogg'));
    }

    public function test_energy_and_food_balance_match_the_game_design(): void
    {
        $response = $this->get('/');
        $content = $response->getContent();

        $response
            ->assertOk()
            ->assertSee('id="energy"', escape: false)
            ->assertSee('<span class="energy-icon" aria-hidden="true">🥣</span>', escape: false)
            ->assertSee('id="status" role="status" aria-live="polite"', escape: false)
            ->assertSee('Миска с кормом')
            ->assertSee('миску с кормом не перепрыгивай')
            ->assertSee("obstacle.dataset.kind === 'food'", escape: false)
            ->assertSee("finish('energy');", escape: false)
            ->assertSee('state.speed * state.energyCostPerObstacle', escape: false)
            ->assertSee('state.energy = 100;', escape: false);

        $this->assertSame(1, preg_match('/energyCostPerObstacle:\s*([\d.]+)/', $content, $energyCost));
        $this->assertSame(1, preg_match('/foodIntervalMin:\s*(\d+)/', $content, $foodMin));
        $this->assertSame(1, preg_match('/foodIntervalMax:\s*(\d+)/', $content, $foodMax));

        $energySpentAfterThreeLongestIntervals =
            (float) $energyCost[1] * ((int) $foodMax[1] + 1) * 3;
        $energySpentAfterFourShortestIntervals =
            (float) $energyCost[1] * ((int) $foodMin[1] + 1) * 4;

        $this->assertLessThan(100, $energySpentAfterThreeLongestIntervals);
        $this->assertGreaterThanOrEqual(100, $energySpentAfterFourShortestIntervals);
    }

    public function test_mobile_scoreboard_stays_in_one_row_below_the_title(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('.topbar { align-items: stretch; flex-direction: column; gap: 12px; }', escape: false)
            ->assertSee('display: grid;', escape: false)
            ->assertSee('grid-template-columns: minmax(0, .8fr) minmax(0, .95fr) minmax(118px, 1.45fr);', escape: false)
            ->assertSee(".score {\n                min-width: 0;", escape: false)
            ->assertSee('.energy-score { min-width: 0; }', escape: false);
    }
}
