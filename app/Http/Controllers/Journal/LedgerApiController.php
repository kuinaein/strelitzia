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
use Illuminate\Support\Carbon;

class LedgerApiController extends Controller {
  private $journalDao;

  private $accountDao;

  private $trialBalanceBuildService;

  public function __construct(
  AccountingJournalDao $journalDao,
  AccountTitleDao $accountDao,
  TrialBalanceBuildService $trialBalanceBuildService
  ) {
    $this->journalDao = $journalDao;
    $this->accountDao = $accountDao;
    $this->trialBalanceBuildService = $trialBalanceBuildService;
  }

  public function index(Request $request, int $accountId, string $month): array {
    $cMonth = Carbon::parse($month);
    $journals = $this->journalDao->listByAccountIdAndPeriod(
  $accountId,
  $cMonth->startOfMonth(),
  $cMonth->copy()->endOfMonth()
  );
    return ['data' => $journals, 'message' => 'OK'];
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
  ? $this->journalDao->findOrFailByAccount($bs, $op)
  : $this->journalDao->findOrFailByAccount($op, $bs), ];
  }
}
