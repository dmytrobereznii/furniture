# Меблевий майстер

Laravel 8 site for a Kyiv furniture maker, running on PHP 8.5. This repo is public, so production hosts, IPs and secrets belong in ignored files.

## Deploying

- Deploy with `make deploy`, and get the user's approval first. It stops unless `master` is clean and matches GitHub.
- `make deploy` reads the production login from the ignored `.env.deploy`.
- Get approval before any other production change too.
- `public/css/app.css`, `public/js/app.js` and `public/js/app.js.LICENSE.txt` differ on production on purpose. Keep them out of commits.
- After editing the production `.env`, run `php artisan config:cache`, then set `bootstrap/cache/config.php` to `640 dev:www-data`, because it holds secrets.

## Developing

- Run `make test`. It uses the local Docker database, and spam tests fail if a row with phone `0000000000` exists, so delete rows you create through the local site.
- `composer update` needs `--no-security-blocking` while on Laravel 8.

## Telegram

`TELEGRAM_USERS` holds real recipients. Test notifications with nonexistent chat IDs until the user asks for a real test.

## Content

- Write all visitor and admin text in Ukrainian, using the 2019 spelling (`проєкт`).
- Project names, descriptions and tab titles in the database are the client's data. Suggest fixes to the user, and change them only on request.
