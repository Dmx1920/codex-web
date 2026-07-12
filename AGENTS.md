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

## Verification

Run before committing:

```bash
composer validate --strict
php artisan test
./vendor/bin/pint --test
```

For front-end changes, also open the game at a mobile and desktop viewport and play at least one round.

## Deployment

- Production host: `sush.run.place`
- The Nginx document root must point to Laravel's `public/` directory.
- Production must use `APP_ENV=production` and `APP_DEBUG=false`.
- Run `php artisan optimize` after deployment.
- Never expose MariaDB publicly.
