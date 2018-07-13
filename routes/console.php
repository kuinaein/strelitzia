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
