<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAccountTitlesTable extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::create('account_titles', function (Blueprint $table): void {
      $table->increments('id');
      $table->enum(
  'type',
  ['ASSET', 'LIABILITY', 'NET_ASSET', 'REVENUE', 'EXPENSE', 'OTHER']
  );
      $table->string('name')->unique();
      $table->string('system_key')->default('');
      $table->timestamps();
    });

    DB::table('account_titles')->insert(
  ['type' => 'OTHER', 'name' => '開始残高', 'system_key' => 'OPENING_BALANCE']
  );
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::dropIfExists('account_titles');
  }
}
