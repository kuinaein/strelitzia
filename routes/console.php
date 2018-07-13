<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('stre:cs', function (): void {
  @unlink(base_path('.php_cs.cache'));
  passthru(base_path('vendor/bin/php-cs-fixer') . ' fix');
  passthru('npm run lint-fix');
})->describe('lintとコード整形');

Artisan::command('stre:dev', function (): void {
  $spawnCmd = 'nohup xfce4-terminal --tab -T ' . config('app.name') . ' -x ';
  passthru('npm run dev');
  exec($spawnCmd . 'npm run watch');
  exec($spawnCmd . 'php artisan serve');
  sleep(1);
  passthru('xdg-open ' . config('app.url'));
})->describe('開発環境として起動');

Artisan::command('stre:createdb', function (): void {
  $user = config('database.connections.pgsql.username');
  $pass = config('database.connections.pgsql.password');
  $db = config('database.connections.pgsql.database');
  // sudo -u postgres initdb -E UTF8 --no-locale -D '/var/lib/postgres/data'
  $psqlCmd = 'psql -U postgres -c ';
  passthru($psqlCmd . "\"CREATE USER ${user} WITH ENCRYPTED PASSWORD '${pass}'\"");
  passthru($psqlCmd . "\"CREATE DATABASE ${db} WITH OWNER ${user} ENCODING 'UTF8'" .
  " LC_COLLATE 'C' LC_CTYPE 'C' TEMPLATE template0\"");
  Artisan::call('migrate:install');
})->describe('データベース初期化');
