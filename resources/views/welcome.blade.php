<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#dff36a">
    <title>Весёлый сущ</title>
    <style>
        :root {
            color-scheme: light;
            --ink: #20261d;
            --paper: #fffaf0;
            --lime: #dff36a;
            --pink: #ff9fbd;
            --sky: #84d9ef;
            --danger: #e05c56;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
            font-family: "Trebuchet MS", "Segoe UI", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 12% 15%, #ffffffaa 0 7%, transparent 8%),
                linear-gradient(180deg, #86dcf1 0 58%, #dff36a 58% 100%);
        }

        button { font: inherit; }

        .page {
            width: min(1100px, calc(100% - 28px));
            margin: 0 auto;
            padding: 24px 0 36px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 18px;
        }

        .brand {
            margin: 0;
            font-size: clamp(2rem, 6vw, 4.5rem);
            line-height: .9;
            letter-spacing: -.07em;
            text-transform: uppercase;
            text-shadow: 3px 3px 0 #fff;
        }

        .scoreboard {
            display: flex;
            gap: 10px;
        }

        .score {
            min-width: 98px;
            padding: 9px 14px;
            border: 3px solid var(--ink);
            border-radius: 18px;
            background: var(--paper);
            box-shadow: 4px 4px 0 var(--ink);
            text-align: center;
        }

        .score span { display: block; font-size: .72rem; font-weight: 800; text-transform: uppercase; }
        .score strong { font-size: 1.45rem; }

        .energy-score {
            min-width: 170px;
            display: grid;
            grid-template-columns: auto minmax(0, 1fr);
            align-items: center;
            column-gap: 8px;
        }
        .energy-body { min-width: 0; }
        .energy-head { display: flex; align-items: baseline; justify-content: space-between; gap: 8px; }
        .score .energy-icon { display: block; font-size: 2.25rem; line-height: 1; }
        .energy-head strong { font-size: .95rem; }
        .energy-meter {
            display: block;
            width: 100%;
            height: 14px;
            margin-top: 6px;
            overflow: hidden;
            border: 2px solid var(--ink);
            border-radius: 999px;
            background: #fff;
            appearance: none;
        }
        .energy-meter::-webkit-progress-bar { background: #fff; }
        .energy-meter::-webkit-progress-value { background: #7fbe3f; transition: width .15s linear; }
        .energy-meter::-moz-progress-bar { background: #7fbe3f; }
        .energy-score.low .energy-head strong { color: var(--danger); }
        .energy-score.low .energy-meter::-webkit-progress-value { background: var(--danger); }
        .energy-score.low .energy-meter::-moz-progress-bar { background: var(--danger); }

        .game-shell {
            position: relative;
            overflow: hidden;
            height: min(58vw, 510px);
            min-height: 350px;
            border: 4px solid var(--ink);
            border-radius: 30px;
            background:
                radial-gradient(circle at 80% 18%, #fff7a8 0 44px, transparent 46px),
                linear-gradient(180deg, #9be5f5 0 67%, #f6dc8b 67% 100%);
            box-shadow: 9px 10px 0 var(--ink);
            touch-action: manipulation;
            user-select: none;
        }

        .cloud, .cloud::before, .cloud::after {
            position: absolute;
            width: 110px;
            height: 34px;
            border: 3px solid var(--ink);
            border-radius: 999px;
            background: #fff;
            content: "";
        }

        .cloud { top: 54px; left: 16%; }
        .cloud::before { width: 48px; height: 48px; left: 15px; bottom: -3px; }
        .cloud::after { width: 58px; height: 58px; right: 10px; bottom: -3px; }

        .ground {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            height: 33%;
            border-top: 4px solid var(--ink);
            background: repeating-linear-gradient(-22deg, #f7df92 0 22px, #f0cc70 22px 44px);
        }

        .creature {
            position: absolute;
            z-index: 4;
            left: 9%;
            bottom: calc(33% - 4px);
            width: clamp(118px, 23vw, 235px);
            transform-origin: 50% 100%;
            will-change: transform;
        }

        .creature.running { animation: bob .32s ease-in-out infinite alternate; }
        .creature.hit { animation: wobble .28s linear 3; }

        .obstacle {
            position: absolute;
            z-index: 3;
            bottom: 33%;
            left: 0;
            display: grid;
            place-items: center;
            width: 46px;
            height: 66px;
            border: 0;
            background: transparent;
            box-shadow: none;
            font-size: 40px;
            line-height: 1;
            will-change: transform;
        }

        .panel {
            position: absolute;
            z-index: 8;
            inset: 0;
            display: grid;
            place-content: center;
            justify-items: center;
            padding: 28px;
            background: #fffaf0d9;
            text-align: center;
            backdrop-filter: blur(5px);
        }

        .panel[hidden] { display: none; }
        .panel h2 { margin: 0 0 8px; font-size: clamp(2rem, 6vw, 4rem); letter-spacing: -.05em; }
        .panel p { max-width: 520px; margin: 0 0 20px; font-size: 1.05rem; line-height: 1.5; }

        .start-button {
            padding: 13px 26px;
            border: 3px solid var(--ink);
            border-radius: 999px;
            background: var(--lime);
            color: var(--ink);
            box-shadow: 5px 5px 0 var(--ink);
            font-size: 1.1rem;
            font-weight: 900;
            cursor: pointer;
            transition: transform .12s, box-shadow .12s;
        }

        .start-button:hover { transform: translate(-2px, -2px); box-shadow: 7px 7px 0 var(--ink); }
        .start-button:active { transform: translate(4px, 4px); box-shadow: 1px 1px 0 var(--ink); }

        .instructions {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            margin-top: 22px;
            padding: 16px 20px;
            border: 3px solid var(--ink);
            border-radius: 20px;
            background: var(--paper);
            box-shadow: 5px 5px 0 var(--ink);
            font-weight: 700;
        }

        .status { color: #53604e; }
        kbd { padding: 3px 8px; border: 2px solid var(--ink); border-radius: 8px; background: #fff; box-shadow: 2px 2px 0 var(--ink); }

        @keyframes bob { to { translate: 0 -5px; rotate: -1deg; } }
        @keyframes wobble { 25% { rotate: -5deg; } 75% { rotate: 5deg; } }

        @media (max-width: 680px) {
            .topbar { align-items: stretch; flex-direction: column; gap: 12px; }
            .scoreboard {
                display: grid;
                grid-template-columns: minmax(0, .8fr) minmax(0, .95fr) minmax(118px, 1.45fr);
                gap: 6px;
                width: 100%;
            }
            .score {
                min-width: 0;
                padding: 6px;
                border-width: 2px;
                border-radius: 14px;
                box-shadow: 3px 3px 0 var(--ink);
            }
            .energy-score { min-width: 0; column-gap: 4px; }
            .score span { font-size: .62rem; }
            .score strong { font-size: 1.15rem; }
            .score .energy-icon { font-size: 1.55rem; }
            .energy-head { gap: 2px; }
            .energy-head strong { font-size: .78rem; }
            .energy-meter { height: 12px; margin-top: 4px; }
            .game-shell { min-height: 420px; }
            .creature { left: 3%; width: 145px; }
            .instructions { flex-direction: column; text-align: center; }
        }

        @media (max-width: 340px) {
            .energy-score { column-gap: 3px; }
            .score .energy-icon { font-size: 1.4rem; }
            .energy-head span { font-size: .58rem; }
            .energy-head strong { font-size: .72rem; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; }
        }
    </style>
</head>
<body>
<main class="page">
    <header class="topbar">
        <h1 class="brand">Весёлый сущ</h1>
        <div class="scoreboard">
            <div class="score"><span>Счёт</span><strong id="score">0</strong></div>
            <div class="score"><span>Рекорд</span><strong id="best">0</strong></div>
            <div class="score energy-score" id="energy-card">
                <span class="energy-icon" aria-hidden="true">🥣</span>
                <div class="energy-body">
                    <div class="energy-head"><span>Энергия</span><strong id="energy-value">100%</strong></div>
                    <progress class="energy-meter" id="energy" max="100" value="100" aria-label="Энергия суща"></progress>
                </div>
            </div>
        </div>
    </header>

    <section class="game-shell" id="game" data-game-shell aria-label="Игровое поле">
        <div class="cloud" aria-hidden="true"></div>
        <div class="ground" aria-hidden="true"></div>
        <img class="creature" id="creature" src="{{ asset('assets/sush.gif') }}" alt="Весёлый сущ">
        <div class="obstacle" id="obstacle" role="img" aria-label="Препятствие"></div>

        <div class="panel" id="panel">
            <h2 id="panel-title">Пора веселиться!</h2>
            <p id="panel-copy">Перепрыгивай препятствия, но миску с кормом не перепрыгивай — набеги на неё, чтобы восстановить энергию.</p>
            <button class="start-button" id="start" type="button">Начать забег</button>
        </div>
    </section>

    <div class="instructions">
        <span><kbd>Пробел</kbd> или касание — прыжок · миска — набеги на неё</span>
        <span class="status" id="status" role="status" aria-live="polite">Сущ ждёт приключений</span>
    </div>
</main>

<audio id="jump-sound" preload="none" src="{{ asset('assets/jump.ogg') }}"></audio>
<audio id="lose-sound" preload="none" src="{{ asset('assets/lose.ogg') }}"></audio>
<audio id="eat-sound" preload="none" src="{{ asset('assets/eating.ogg') }}"></audio>

<script>
(() => {
    const game = document.querySelector('#game');
    const creature = document.querySelector('#creature');
    const obstacle = document.querySelector('#obstacle');
    const scoreNode = document.querySelector('#score');
    const bestNode = document.querySelector('#best');
    const panel = document.querySelector('#panel');
    const panelTitle = document.querySelector('#panel-title');
    const panelCopy = document.querySelector('#panel-copy');
    const startButton = document.querySelector('#start');
    const status = document.querySelector('#status');
    const energyNode = document.querySelector('#energy');
    const energyValue = document.querySelector('#energy-value');
    const energyCard = document.querySelector('#energy-card');
    const jumpSound = document.querySelector('#jump-sound');
    const loseSound = document.querySelector('#lose-sound');
    const eatSound = document.querySelector('#eat-sound');

    let audioContext = null;
    let jumpSoundBufferPromise = null;
    let loseSoundBufferPromise = null;
    let eatSoundBufferPromise = null;

    const obstacleTypes = [
        { icon: '🐍', label: 'Змея', lane: 'ground', width: 60, height: 53, fontSize: 42 },
        { icon: '💩', label: 'Какашка', lane: 'ground', width: 55, height: 64, fontSize: 44 },
        { icon: '🔥', label: 'Огонь', lane: 'ground', width: 53, height: 70, fontSize: 46 },
        { icon: '🌵', label: 'Кактус', lane: 'ground', width: 53, height: 75, fontSize: 46 },
        { icon: '🪨', label: 'Камень', lane: 'ground', width: 64, height: 55, fontSize: 42 },
        { icon: '🐌', label: 'Улитка', lane: 'ground', width: 64, height: 53, fontSize: 42 },
        { icon: '🕷️', label: 'Паук', lane: 'ground', width: 57, height: 55, fontSize: 43 },
        { icon: '🦂', label: 'Скорпион', lane: 'ground', width: 64, height: 57, fontSize: 43 },
        { icon: '🍄', label: 'Гриб', lane: 'ground', width: 55, height: 66, fontSize: 44 },
        { icon: '🦀', label: 'Краб', lane: 'ground', width: 64, height: 55, fontSize: 43 },
        { icon: '🧨', label: 'Динамит', lane: 'ground', width: 53, height: 68, fontSize: 45 },
        { icon: '🧱', label: 'Кирпич', lane: 'ground', width: 64, height: 53, fontSize: 42 },
        { icon: '🪵', label: 'Бревно', lane: 'ground', width: 68, height: 51, fontSize: 42 },
        { icon: '🦔', label: 'Ёж', lane: 'ground', width: 62, height: 57, fontSize: 43 },
        { icon: '⚡', label: 'Молния', lane: 'ground', width: 51, height: 70, fontSize: 46 },
        { icon: '🐦', label: 'Птица', lane: 'air', width: 62, height: 52, fontSize: 44 },
        { icon: '☁️', label: 'Тучка', lane: 'air', width: 68, height: 48, fontSize: 46 },
        { icon: '🦇', label: 'Летучая мышь', lane: 'air', width: 64, height: 50, fontSize: 45 },
        { icon: '🐝', label: 'Пчела', lane: 'air', width: 56, height: 48, fontSize: 42 },
        { icon: '🛸', label: 'НЛО', lane: 'air', width: 70, height: 48, fontSize: 46 },
    ];

    const foodType = {
        icon: '🥣',
        label: 'Миска с кормом',
        lane: 'ground',
        kind: 'food',
        width: 68,
        height: 54,
        fontSize: 46,
    };

    const state = {
        running: false,
        y: 0,
        velocity: 0,
        obstacleX: 0,
        score: 0,
        lastTime: 0,
        speed: 280,
        jumpVelocity: 820,
        gravity: 1800,
        energy: 100,
        energyCostPerObstacle: 2.5,
        obstacleTravelDistance: 1,
        obstaclesSinceFood: 0,
        foodIntervalMin: 10,
        foodIntervalMax: 12,
        nextFoodAfter: 10,
        best: Number(localStorage.getItem('vesely-sush-best') || 0),
        frame: 0,
        lastObstacleLabel: '',
    };

    bestNode.textContent = state.best;

    const updateEnergy = () => {
        const value = Math.max(0, Math.min(100, state.energy));
        energyNode.value = value;
        energyValue.textContent = `${Math.ceil(value)}%`;
        energyCard.classList.toggle('low', value <= 25);
    };

    const randomFoodInterval = () => state.foodIntervalMin +
        Math.floor(Math.random() * (state.foodIntervalMax - state.foodIntervalMin + 1));

    const positionObstacle = (lane = obstacle.dataset.lane) => {
        const airClearance = Math.ceil(creature.clientHeight * .88);
        obstacle.style.bottom = lane === 'air' ? `calc(33% + ${airClearance}px)` : '33%';
    };

    const resetObstacle = () => {
        const jumpCycle = (2 * state.jumpVelocity) / state.gravity;
        const collisionX = creature.offsetLeft + creature.clientWidth * .67;
        const distanceToCollision = game.clientWidth - collisionX;
        const minimumGap = Math.max(20, state.speed * (jumpCycle + .12) - distanceToCollision);
        const randomGap = Math.random() * state.speed * .65;
        state.obstacleX = game.clientWidth + minimumGap + randomGap;
        state.obstacleTravelDistance = Math.max(1, state.obstacleX + 100);

        const shouldSpawnFood = state.running && state.obstaclesSinceFood >= state.nextFoodAfter;
        let type;

        if (shouldSpawnFood) {
            type = foodType;
            state.obstaclesSinceFood = 0;
            state.nextFoodAfter = randomFoodInterval();
        } else {
            const lane = Math.random() < .2 ? 'air' : 'ground';
            const candidates = obstacleTypes.filter(candidate => candidate.lane === lane);
            type = candidates[Math.floor(Math.random() * candidates.length)];

            if (type.label === state.lastObstacleLabel) {
                type = candidates[(candidates.indexOf(type) + 1) % candidates.length];
            }

            if (state.running) state.obstaclesSinceFood += 1;
        }

        state.lastObstacleLabel = type.label;
        obstacle.textContent = type.icon;
        obstacle.setAttribute('aria-label', type.label);
        obstacle.dataset.lane = type.lane;
        obstacle.dataset.kind = type.kind || 'hazard';
        obstacle.style.width = `${type.width}px`;
        obstacle.style.height = `${type.height}px`;
        obstacle.style.fontSize = `${type.fontSize}px`;
        positionObstacle(type.lane);
    };

    const jump = () => {
        if (!state.running) return;
        if (state.y <= 1 && state.velocity === 0) {
            state.velocity = state.jumpVelocity;
            playSound(jumpSoundBufferPromise).catch(() => {});
            status.textContent = 'Прыг!';
        }
    };

    const loadSound = sound => fetch(sound.src)
        .then(response => {
            if (!response.ok) throw new Error('Unable to load game sound');
            return response.arrayBuffer();
        })
        .then(data => audioContext.decodeAudioData(data))
        .catch(() => null);

    const prepareSounds = () => {
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        if (!AudioContext) return;

        audioContext ??= new AudioContext();
        audioContext.resume().catch(() => {});
        jumpSoundBufferPromise ??= loadSound(jumpSound);
        loseSoundBufferPromise ??= loadSound(loseSound);
        eatSoundBufferPromise ??= loadSound(eatSound);
    };

    const playSound = async bufferPromise => {
        const buffer = await bufferPromise;
        if (!audioContext || !buffer) return;

        const source = audioContext.createBufferSource();
        source.buffer = buffer;
        source.connect(audioContext.destination);
        source.start();
    };

    const start = () => {
        prepareSounds();
        state.running = true;
        state.y = 0;
        state.velocity = 0;
        state.score = 0;
        state.speed = 280;
        state.energy = 100;
        state.obstaclesSinceFood = 0;
        state.nextFoodAfter = randomFoodInterval();
        state.lastTime = performance.now();
        scoreNode.textContent = '0';
        updateEnergy();
        panel.hidden = true;
        creature.classList.add('running');
        creature.classList.remove('hit');
        status.textContent = 'Сущ побежал!';
        resetObstacle();
        cancelAnimationFrame(state.frame);
        state.frame = requestAnimationFrame(tick);
    };

    const finish = (reason = 'obstacle') => {
        state.running = false;
        playSound(loseSoundBufferPromise).catch(() => {});
        creature.classList.remove('running');
        creature.classList.toggle('hit', reason === 'obstacle');
        state.best = Math.max(state.best, Math.floor(state.score));
        localStorage.setItem('vesely-sush-best', String(state.best));
        bestNode.textContent = state.best;
        panelTitle.textContent = reason === 'energy' ? 'Сущ проголодался!' : 'Ой, подозрительное!';
        panelCopy.textContent = reason === 'energy'
            ? `Энергия закончилась на ${Math.floor(state.score)} очках. Не пропускай миски с кормом!`
            : `Сущ набрал ${Math.floor(state.score)} очков. Ещё один забег?`;
        startButton.textContent = 'Попробовать снова';
        panel.hidden = false;
        status.textContent = reason === 'energy'
            ? 'Сущ устал и остановился'
            : 'Сущ делает вид, что так и задумано';
    };

    const completeObstacle = () => {
        state.score += 10;
        state.speed = Math.min(520, state.speed + 14);
        resetObstacle();
    };

    const eatFood = () => {
        playSound(eatSoundBufferPromise).catch(() => {});
        state.energy = 100;
        updateEnergy();
        status.textContent = 'Ням! Энергия восстановлена';
        completeObstacle();
    };

    const collides = () => {
        const a = creature.getBoundingClientRect();
        const b = obstacle.getBoundingClientRect();
        const paddingX = a.width * .33;
        const paddingY = a.height * .22;
        const horizontalOverlap = a.right - paddingX > b.left &&
            a.left + paddingX < b.right;

        if (obstacle.dataset.lane === 'air') {
            return horizontalOverlap && state.y > 6;
        }

        return horizontalOverlap &&
            a.bottom - paddingY > b.top &&
            a.top + paddingY < b.bottom;
    };

    function tick(now) {
        if (!state.running) return;

        const dt = Math.max(0, Math.min((now - state.lastTime) / 1000, .08));
        state.lastTime = now;
        state.velocity -= state.gravity * dt;
        state.y = Math.max(0, state.y + state.velocity * dt);

        if (state.y === 0 && state.velocity < 0) state.velocity = 0;

        state.obstacleX -= state.speed * dt;
        if (state.obstacleX < -100) {
            completeObstacle();
        }

        const energyDrainPerSecond = state.speed * state.energyCostPerObstacle /
            state.obstacleTravelDistance;
        state.energy = Math.max(0, state.energy - energyDrainPerSecond * dt);
        updateEnergy();

        state.score += dt;
        scoreNode.textContent = Math.floor(state.score);
        creature.style.transform = `translateY(${-state.y}px) scaleX(-1)`;
        obstacle.style.transform = `translateX(${state.obstacleX}px)`;

        if (collides()) {
            if (obstacle.dataset.kind === 'food') {
                eatFood();
            } else {
                finish('obstacle');
                return;
            }
        }

        if (state.energy === 0) {
            finish('energy');
            return;
        }

        state.frame = requestAnimationFrame(tick);
    }

    startButton.addEventListener('click', start);
    document.addEventListener('pointerdown', event => {
        if (event.target.closest('#start')) return;
        jump();
    });
    window.addEventListener('keydown', event => {
        if (event.code !== 'Space') return;
        event.preventDefault();
        if (event.repeat) return;
        if (state.running) {
            jump();
        } else {
            start();
            jump();
        }
    });
    window.addEventListener('resize', () => {
        positionObstacle();
    });
    creature.addEventListener('load', () => positionObstacle());

    resetObstacle();
})();
</script>
</body>
</html>
