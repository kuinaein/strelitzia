<?php

declare(strict_types=1);

namespace App\Http\Controllers\Journal;

use App\Domain\Account\Dao\AccountTitleDao;
use App\Domain\Account\Dto\AccountTitleType;
use App\Domain\Account\Dto\SystemAccountTitleKey;
use App\Domain\Journal\Dao\AccountingJournalDao;
use App\Domain\Journal\Service\TrialBalanceBuildService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JournalApiController extends Controller {
  private $dao;

  private $accountDao;

  private $trialBalanceBuildService;

  public function __construct(
  AccountingJournalDao $dao,
  AccountTitleDao $accountDao,
  TrialBalanceBuildService $trialBalanceBuildService
  ) {
    $this->dao = $dao;
    $this->accountDao = $accountDao;
    $this->trialBalanceBuildService = $trialBalanceBuildService;
  }

  public function showTrialBalance(Request $request): array {
    $accountTypes = array_map(function ($t) {
      return new AccountTitleType($t);
    }, $request->accountTypes);
    $r = $this->trialBalanceBuildService->build($accountTypes);
    return ['data' => $r, 'message' => 'OK'];
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
