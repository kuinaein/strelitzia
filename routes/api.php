<?php

declare(strict_types=1);

use Illuminate\Routing\Router;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'account', 'namespace' => 'Account'], function (Router $router): void {
  $router->resource('/', 'AccountApiController', ['only' => ['index']]);
  $router->resource('bs-account', 'BsAccountApiController', ['only' => ['store', 'update']]);
});

Route::group(['prefix' => 'journal', 'namespace' => 'Journal'], function (Router $router): void {
  $router->post('trial-balance', 'JournalApiController@showTrialBalance');
  $router->get('opening/{bsAccountId}', 'JournalApiController@showOpeningBalance');
});
