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
            .topbar { align-items: flex-start; }
            .scoreboard { flex-direction: column; }
            .score { min-width: 82px; padding: 6px 10px; }
            .score strong { font-size: 1.15rem; }
            .game-shell { min-height: 420px; }
            .creature { left: 3%; width: 145px; }
            .instructions { flex-direction: column; text-align: center; }
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
        <div class="scoreboard" aria-live="polite">
            <div class="score"><span>Счёт</span><strong id="score">0</strong></div>
            <div class="score"><span>Рекорд</span><strong id="best">0</strong></div>
        </div>
    </header>

    <section class="game-shell" id="game" data-game-shell aria-label="Игровое поле">
        <div class="cloud" aria-hidden="true"></div>
        <div class="ground" aria-hidden="true"></div>
        <img class="creature" id="creature" src="{{ asset('assets/sush.gif') }}" alt="Весёлый сущ">
        <div class="obstacle" id="obstacle" role="img" aria-label="Препятствие"></div>

        <div class="panel" id="panel">
            <h2 id="panel-title">Пора веселиться!</h2>
            <p id="panel-copy">Помоги сущу перепрыгивать всё подозрительное. Нажимай пробел или касайся игрового поля.</p>
            <button class="start-button" id="start" type="button">Начать забег</button>
        </div>
    </section>

    <div class="instructions">
        <span><kbd>Пробел</kbd> или касание — прыжок</span>
        <span class="status" id="status">Сущ ждёт приключений</span>
    </div>
</main>

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
        best: Number(localStorage.getItem('vesely-sush-best') || 0),
        frame: 0,
        lastObstacleLabel: '',
    };

    bestNode.textContent = state.best;

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

        const lane = Math.random() < .2 ? 'air' : 'ground';
        const candidates = obstacleTypes.filter(type => type.lane === lane);
        let type = candidates[Math.floor(Math.random() * candidates.length)];

        if (type.label === state.lastObstacleLabel) {
            type = candidates[(candidates.indexOf(type) + 1) % candidates.length];
        }

        state.lastObstacleLabel = type.label;
        obstacle.textContent = type.icon;
        obstacle.setAttribute('aria-label', type.label);
        obstacle.dataset.lane = type.lane;
        obstacle.style.width = `${type.width}px`;
        obstacle.style.height = `${type.height}px`;
        obstacle.style.fontSize = `${type.fontSize}px`;
        positionObstacle(type.lane);
    };

    const jump = () => {
        if (!state.running) return;
        if (state.y <= 1 && state.velocity === 0) {
            state.velocity = state.jumpVelocity;
            status.textContent = 'Прыг!';
        }
    };

    const start = () => {
        state.running = true;
        state.y = 0;
        state.velocity = 0;
        state.score = 0;
        state.speed = 280;
        state.lastTime = performance.now();
        scoreNode.textContent = '0';
        panel.hidden = true;
        creature.classList.add('running');
        creature.classList.remove('hit');
        status.textContent = 'Сущ побежал!';
        resetObstacle();
        cancelAnimationFrame(state.frame);
        state.frame = requestAnimationFrame(tick);
    };

    const finish = () => {
        state.running = false;
        creature.classList.remove('running');
        creature.classList.add('hit');
        state.best = Math.max(state.best, Math.floor(state.score));
        localStorage.setItem('vesely-sush-best', String(state.best));
        bestNode.textContent = state.best;
        panelTitle.textContent = 'Ой, подозрительное!';
        panelCopy.textContent = `Сущ набрал ${Math.floor(state.score)} очков. Ещё один забег?`;
        startButton.textContent = 'Попробовать снова';
        panel.hidden = false;
        status.textContent = 'Сущ делает вид, что так и задумано';
    };

    const collides = () => {
        const a = creature.getBoundingClientRect();
        const b = obstacle.getBoundingClientRect();
        const paddingX = a.width * .33;
        const paddingY = a.height * .22;

        return a.right - paddingX > b.left &&
            a.left + paddingX < b.right &&
            a.bottom - paddingY > b.top &&
            a.top + paddingY < b.bottom;
    };

    function tick(now) {
        if (!state.running) return;

        const dt = Math.min((now - state.lastTime) / 1000, .08);
        state.lastTime = now;
        state.velocity -= state.gravity * dt;
        state.y = Math.max(0, state.y + state.velocity * dt);

        if (state.y === 0 && state.velocity < 0) state.velocity = 0;

        state.obstacleX -= state.speed * dt;
        if (state.obstacleX < -100) {
            state.score += 10;
            state.speed = Math.min(520, state.speed + 14);
            resetObstacle();
        }

        state.score += dt;
        scoreNode.textContent = Math.floor(state.score);
        creature.style.transform = `translateY(${-state.y}px) scaleX(-1)`;
        obstacle.style.transform = `translateX(${state.obstacleX}px)`;

        if (collides()) {
            finish();
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
        state.running ? positionObstacle() : resetObstacle();
    });
    creature.addEventListener('load', () => positionObstacle());

    resetObstacle();
})();
</script>
</body>
</html>
