<?php
declare (strict_types = 1);

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

Artisan::command('stre:deploy', function () : void {
    $this->call('stre:clean');
    passthru('yarn install --prod');
    passthru('npm run prod');
    $this->call('migrate');

    $this->call('optimize');
    $this->call('config:cache');
    $this->call('route:cache');
})->describe('デプロイ');

Artisan::command('stre:clean', function () : void {
    $this->call('cache:clear');
    $this->call('config:clear');
    $this->call('route:clear');
    $this->call('view:clear');
})->describe('キャッシュなどのクリア');

Artisan::command('stre:update', function () : void {
    $this->call('migrate');
    $this->call('stre:deploy');
})->describe('アップデート');

Artisan::command('stre:install', function () : void {
    $this->call('key:generate');
    $this->call('migrate:install');
    $this->call('stre:update');
})->describe('インストール');

Artisan::command('stre:createdb', function () : void {
    $user = config('database.connections.pgsql.username');
    $pass = config('database.connections.pgsql.password');
    $db = config('database.connections.pgsql.database');
  // sudo -u postgres initdb -E UTF8 --no-locale -D '/var/lib/postgres/data'
    $psqlCmd = 'psql -U postgres -c ';
    passthru($psqlCmd . "\"CREATE USER ${user} WITH ENCRYPTED PASSWORD '${pass}'\"");
    passthru($psqlCmd . "\"CREATE DATABASE ${db} WITH OWNER ${user} ENCODING 'UTF8'" .
        " LC_COLLATE 'C' LC_CTYPE 'C' TEMPLATE template0\"");
})->describe('データベース初期化');

Artisan::command('stre:dev', function () : void {
    $spawnCmd = 'xfce4-terminal --tab -T \'' . config('app.name') . '\' -x ';
    passthru('npm run dev');
    exec($spawnCmd . 'npm run watch');
    exec($spawnCmd . 'php artisan serve');
    sleep(1);
    passthru('xdg-open ' . config('app.url'));
})->describe('開発環境として起動');

Artisan::command('stre:cs', function () {
    $this->info('PHP Code Beautifier and Fixer ...');
    passthru(base_path('vendor/bin/phpcbf') . ' --standard=' . base_path('phpcs.xml'));
    $this->info('PHP_CodeSniffer ...');
    passthru(base_path('vendor/bin/phpcs') . ' --standard=' . base_path('phpcs.xml'));
    $this->info('PHPStan ...');
    passthru(base_path('vendor/bin/phpstan') . ' analyze');

    $buf = [];
    $this->info('PHPMD ...');
    exec(base_path('vendor/bin/phpmd ') . implode([
        app_path(),
        base_path('tests'),
        base_path('routes'),
        config_path(),
        database_path(),
    ], ',') . ' html ' . base_path('phpmd.xml'), $buf);
    file_put_contents(base_path('phpmd-result.html'), $buf);
    exec('xdg-open ' . base_path('phpmd-result.html'));

    $this->info('PHP Copy/Paste Detector ...');
    passthru(base_path('vendor/bin/phpcpd') . ' --min-tokens 30 ' . implode([
        app_path(),
        base_path('tests'),
        base_path('routes'),
        config_path(),
    ], ' '));

    passthru('npm run lint-fix');
})->describe('lint, 静的解析とコード整形');
