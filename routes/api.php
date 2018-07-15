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
  $router->resource('pl-account', 'PlAccountApiController', ['only' => ['store', 'update']]);
});

Route::group(['prefix' => 'journal', 'namespace' => 'Journal'], function (Router $router): void {
  $router->post('trial-balance', 'LedgerApiController@showTrialBalance');
  $router->get('opening/{bsAccountId}', 'LedgerApiController@showOpeningBalance');
  $router->get('ledger/{accountId}/{month}', 'LedgerApiController@index');
});
