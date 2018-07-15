<?php

declare(strict_types=1);

namespace App\Domain\Journal\Dao;

use App\Domain\Account\Dao\AccountTitleDao;
use App\Domain\Account\Dto\AccountTitle;
use App\Domain\Account\Dto\AccountTitleType;
use App\Domain\Journal\Dto\AccountingJournal;
use App\Domain\Journal\Model\AccountingJournalModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AccountingJournalDao {
  /**
   * @var AccountingJournalModel
   */
  private $repo;

  private $accountDao;

  public function __construct(AccountingJournalModel $repo, AccountTitleDao $accountDao) {
    $this->repo = $repo;
    $this->accountDao = $accountDao;
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

  /**
   * @param \App\Domain\Account\Dto\AccountTitleType[] $accountTypes
   * @return array[int=>int] accountId => 金額
   */
  public function buildTrialBalance(array $accountTypes): array {
    $debitSums = $this->sumOneSide($accountTypes, 'debit');
    $creditSums = $this->sumOneSide($accountTypes, 'credit');
    $result = [];

    foreach ($debitSums as $d) {
      switch ($d->type) {
  case AccountTitleType::ASSET:
  case AccountTitleType::EXPENSE:
  $result[$d->id] = +$d->amount;
  break;
  default:
  $result[$d->id] = -$d->amount;
  }
    }

    foreach ($creditSums as $c) {
      switch ($c->type) {
  case AccountTitleType::ASSET:
  case AccountTitleType::EXPENSE:
  $result[$c->id] = isset($result[$c->id]) ? $result[$c->id] - $c->amount : -$c->amount;
  break;
  default:
  $result[$c->id] = isset($result[$c->id]) ? $result[$c->id] + $c->amount : +$c->amount;
  break;
  }
    }
    return $result;
  }

  private function sumOneSide(array $accountTypes, string $side): Collection {
    return $this->repo->select(
  'account_title.type',
  'account_title.id',
  DB::raw('sum(accounting_journal.amount) as amount')
  )
    ->join('account_title', 'accounting_journal.' . $side . '_account_id', '=', 'account_title.id')
    ->whereIn('account_title.type', $accountTypes)
    ->groupBy('account_title.type', 'account_title.id')
    ->get();
  }
}
