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
