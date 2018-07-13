<?php

declare(strict_types=1);

use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\WebProcessor;

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
  realpath(__DIR__ . '/../')
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
  Illuminate\Contracts\Http\Kernel::class,
  App\Http\Kernel::class
);

$app->singleton(
  Illuminate\Contracts\Console\Kernel::class,
  App\Console\Kernel::class
);

$app->singleton(
  Illuminate\Contracts\Debug\ExceptionHandler::class,
  App\Exceptions\Handler::class
);

$app->configureMonologUsing(function ($monolog) {
  $monolog->pushHandler(new StreamHandler('php://stderr'));
  $monolog->pushHandler(new RotatingFileHandler(
  storage_path('logs/strelitzia.log'),
  30
  ));

  // クラス名等を extra フィールドに挿入するプロセッサを生成
  $ip = new IntrospectionProcessor(Logger::DEBUG, ['Illuminate\\']);

  // IPアドレス等を extra フィールドに挿入するプロセッサを生成
  $wp = new WebProcessor();

  foreach ($monolog->getHandlers() as $handler) {
    $handler->pushProcessor($ip);
    $handler->pushProcessor($wp);
  }

  return $monolog;
});

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
