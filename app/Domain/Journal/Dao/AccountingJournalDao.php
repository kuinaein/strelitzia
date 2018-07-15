<?php

declare(strict_types=1);

namespace App\Domain\Journal\Dao;

use App\Domain\Account\Dto\AccountTitle;
use App\Domain\Journal\Dto\AccountingJournal;
use App\Domain\Journal\Model\AccountingJournalModel;

class AccountingJournalDao {
  /**
   * @var AccountingJournalModel
   */
  private $repo;

  public function __construct(AccountingJournalModel $repo) {
    $this->repo = $repo;
  }

  public function findOrFailByAccount(AccountTitle $debit, AccountTitle $credit): AccountingJournal {
    return new AccountingJournal(
  $this->repo->where(['debit_account_id' => $debit->Id, 'credit_account_id' => $credit->Id])
    ->firstOrFail()
  );
  }

  public function save(AccountingJournal $dto): AccountingJournal {
    $dto->unwrap()->save();
    return $dto;
  }
}
