<?php

declare(strict_types=1);

namespace App\Domain\Journal\Model;

use Illuminate\Database\Eloquent\Model;

class JournalScheduleModel extends Model {
  protected $table = 'journal_schedule';

  protected $guarded = ['id', Model::UPDATED_AT, MODEL::CREATED_AT];
}
