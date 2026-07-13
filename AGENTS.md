# Project guidance

## Product

`Весёлый сущ` is a friendly, slightly absurd browser game. Keep the interface playful, responsive, accessible, and usable with both keyboard and touch controls.

## Stack

- PHP 8.3+ and Laravel 13
- MariaDB in production
- Blade for server-rendered pages
- Plain JavaScript for the real-time game loop
- Livewire may be introduced for accounts, settings, and leaderboards

## Development rules

- Never commit `.env`, credentials, SSH keys, generated certificates, `vendor/`, or `node_modules/`.
- Keep gameplay code deterministic enough to test. Separate complex rules from DOM rendering when the game grows.
- Do not require a database for the anonymous game page.
- Preserve keyboard and touch controls.
- Avoid new dependencies unless they materially reduce complexity.
- Treat user-provided artwork as an original project asset; do not replace or transform it without approval.

## Learning workflow

The primary goal of this project is learning to use Codex effectively; shipping the game is the practical exercise.

- Before a substantial action, briefly explain what will be done, why, and what trade-offs matter.
- After the action, summarize the result, the evidence used to verify it, and the key lesson.
- Proactively introduce relevant technologies, tools, and Codex capabilities that the user may not know.
- Compare alternatives when the choice is educational, and state why the selected option fits this project.
- Let the user perform an important step when doing it manually teaches a reusable skill; automate repetitive work.
- Use independent agents for bounded reviews such as pre-merge correctness, security, and regression audits.
- Do not hide failures or permission issues; explain their cause and the safer configuration that resolves them.

## Verification

Run before committing:

```bash
composer validate --strict
php artisan test
./vendor/bin/pint --test
```

For front-end changes, also open the game at a mobile and desktop viewport and play at least one round.

## Deployment

- Production host: `playsush.mooo.com`; `sush.run.place` redirects to it.
- The Nginx document root must point to Laravel's `public/` directory.
- Production must use `APP_ENV=production` and `APP_DEBUG=false`.
- Run `php artisan optimize` after deployment.
- Never expose MariaDB publicly.
