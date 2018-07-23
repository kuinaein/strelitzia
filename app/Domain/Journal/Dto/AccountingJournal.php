<?php

declare(strict_types=1);

namespace App\Domain\Journal\Dto;

use App\Core\DataAccess\StreDto;
use App\Domain\Journal\Model\AccountingJournalModel;

class AccountingJournal extends StreDto {
  protected static $modelClass = AccountingJournalModel::class;
}
