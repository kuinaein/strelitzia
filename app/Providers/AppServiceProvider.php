<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
  /**
   * Bootstrap any application services.
   */
  public function boot(): void {
    if (config('app.debug')) {
      DB::listen(function ($query): void {
        $sql = $query->sql;

        for ($i = 0; $i < count($query->bindings); $i++) {
          $sql = preg_replace('/\\?/', $query->bindings[$i], $sql, 1);
        }
        logger($sql);
      });
    }
  }

  /**
   * Register any application services.
   */
  public function register(): void {
  }
}
