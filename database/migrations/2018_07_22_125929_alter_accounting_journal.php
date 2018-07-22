<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAccountingJournal extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void {
    Schema::table('accounting_journal', function (Blueprint $table): void {
      $table->string('remarks')->default('');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void {
    Schema::table('accounting_journal', function (Blueprint $table): void {
      $table->dropColumn('remarks');
    });
  }
}
