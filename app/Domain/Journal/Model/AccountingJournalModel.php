<?php

declare(strict_types=1);

namespace App\Domain\Journal\Model;

use Illuminate\Database\Eloquent\Model;

class AccountingJournalModel extends Model {
  protected $table = 'accounting_journal';

  protected $guarded = ['id', Model::UPDATED_AT, MODEL::CREATED_AT];
}
