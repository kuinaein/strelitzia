<?php

declare(strict_types=1);

namespace App\Http\Controllers\Journal;

use App\Domain\Account\Dao\AccountTitleDao;
use App\Domain\Account\Dto\AccountTitleType;
use App\Domain\Account\Dto\SystemAccountTitleKey;
use App\Domain\Journal\Dao\AccountingJournalDao;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JournalApiController extends Controller {
  private $dao;

  private $accountDao;

  public function __construct(AccountingJournalDao $dao, AccountTitleDao $accountDao) {
    $this->dao = $dao;
    $this->accountDao = $accountDao;
  }

  public function showOpeningBalance(Request $request, int $bsAccountId): array {
    $bs = $this->accountDao->findOrFail($bsAccountId);
    $op = $this->accountDao->findOrFailBySystemKey(
  new SystemAccountTitleKey(SystemAccountTitleKey::OPENING_BALANCE)
  );
    return ['message' => 'OK',
      'data' => $bs->type === AccountTitleType::ASSET
  ? $this->dao->findOrFailByAccount($bs, $op)
  : $this->dao->findOrFailByAccount($op, $bs), ];
  }
}
