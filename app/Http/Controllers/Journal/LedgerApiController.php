<?php

declare(strict_types=1);

namespace App\Http\Controllers\Journal;

use App\Domain\Account\Dao\AccountTitleDao;
use App\Domain\Account\Dto\AccountTitleType;
use App\Domain\Account\Dto\SystemAccountTitleKey;
use App\Domain\Journal\Dao\AccountingJournalDao;
use App\Domain\Journal\Dto\AccountingJournal;
use App\Domain\Journal\Service\AccountingJournalSaveService;
use App\Domain\Journal\Service\LedgerPageLoadService;
use App\Domain\Journal\Service\TrialBalanceBuildService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LedgerApiController extends Controller {
  /**
   * @var AccountingJournalDao
   */
  private $journalDao;

  /**
   * @var AccountTitleDao
   */
  private $accountDao;

  /**
   * @var TrialBalanceBuildService
   */
  private $trialBalanceBuildService;

  /**
   * @var LedgerPageLoadService
   */
  private $ledgerPageLoadService;

  /**
   * @var AccountingJournalSaveService
   */
  private $saveService;

  public function __construct(
  AccountingJournalDao $journalDao,
  AccountTitleDao $accountDao,
  TrialBalanceBuildService $trialBalanceBuildService,
  LedgerPageLoadService $ledgerPageLoadService,
  AccountingJournalSaveService $saveService
  ) {
    $this->journalDao = $journalDao;
    $this->accountDao = $accountDao;
    $this->trialBalanceBuildService = $trialBalanceBuildService;
    $this->ledgerPageLoadService = $ledgerPageLoadService;
    $this->saveService = $saveService;
  }

  public function index(Request $request, int $accountId, string $month): array {
    $start = Carbon::parse($month)->startOfMonth();
    $end = $start->copy()->addMonth()->startOfMonth();
    $result = $this->ledgerPageLoadService->load($accountId, $start, $end);
    return ['data' => $result, 'message' => 'OK'];
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int                      $accountId
   */
  public function store(Request $request, int $accountId): array {
    $j = new AccountingJournal($request->all());
    $this->saveService->create($j);
    return ['messsage' => 'OK'];
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int                      $accountId
   * @param int                      $journalId
   */
  public function update(Request $request, int $accountId, int $journalId): array {
    $j = new AccountingJournal($request->all());
    $this->saveService->update($j);
    return ['messsage' => 'OK'];
  }

  // Remove the specified resource from storage.
  public function destroy(int $accountId, int $journalId): array {
    $this->saveService->destroy($journalId);
    return ['messsage' => 'OK'];
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
