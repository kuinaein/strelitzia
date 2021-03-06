<?php
declare (strict_types = 1);

namespace App\Domain\Journal\Service;

use App\Domain\Account\Dao\AccountTitleDao;
use App\Domain\Account\Dao\BsAccountDao;
use App\Domain\Account\Dto\AccountTitleType;
use App\Domain\Account\Dto\BsAccount;
use App\Domain\Account\Dto\SystemAccountTitleKey;
use App\Domain\Journal\Dao\AccountingJournalDao;
use App\Domain\Journal\Dto\AccountingJournal;
use Illuminate\Support\Carbon;

/**
 * Accountに置くと名前空間同士の相互参照になるのでこちらに置く.
 */
class BsAccountSaveService
{
    private $dao;
    private $accountDao;
    private $journalDao;

    public function __construct(
        BsAccountDao $dao,
        AccountTitleDao $accountDao,
        AccountingJournalDao $journalDao
    ) {
        $this->dao = $dao;
        $this->accountDao = $accountDao;
        $this->journalDao = $journalDao;
    }

    /**
     * @param BsAccount $bsAccount
     * @param int       $openingBalance
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(BsAccount $bsAccount, int $openingBalance) : BsAccount
    {
        $this->validate($bsAccount, $openingBalance);
        $op = $this->accountDao->findOrFailBySystemKey(
            new SystemAccountTitleKey(SystemAccountTitleKey::OPENING_BALANCE)
        );
        $a = \DB::transaction(function () use ($bsAccount, $openingBalance, $op) {
            $a = $this->dao->createOrFail($bsAccount);
            $isAsset = $bsAccount->type === AccountTitleType::ASSET;
            $j = new AccountingJournal();
      // MySQLのDATE型の最小値は1000-01-01なので一応そちらに合わせておく
            $j->journalDate = (string)Carbon::createFromDate(1000, 1, 1);
            $j->debitAccountId = $isAsset ? $a->id : $op->id;
            $j->creditAccountId = $isAsset ? $op->id : $a->id;
            $j->amount = $openingBalance;
            $this->journalDao->save($j);
            return $a;
        });
        optional(logger())->notice('資産・負債科目の追加', ['新科目' => $a]);
        return $a;
    }

    /**
     * @param BsAccount $bsAccount
     * @param int       $openingBalance
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(BsAccount $bsAccount, int $openingBalance) : BsAccount
    {
        $op = $this->accountDao->findOrFailBySystemKey(
            new SystemAccountTitleKey(SystemAccountTitleKey::OPENING_BALANCE)
        );
        $opJournal = $bsAccount->type === AccountTitleType::ASSET
            ? $this->journalDao->findOrFailByAccount($bsAccount, $op)
            : $this->journalDao->findOrFailByAccount($op, $bsAccount);

        return \DB::transaction(function () use ($bsAccount, $openingBalance, $opJournal) {
            $old = $this->dao->findOrFail($bsAccount->id);
            $this->validate($bsAccount, $openingBalance, $old);
            $new = $old->fill($bsAccount);
            $opJournal->amount = $openingBalance;
            $a = $this->dao->updateOrFail($new);
            $this->journalDao->save($opJournal);
            optional(logger())->notice('資産・負債科目の更新', ['科目' => $a]);
            return $a;
        });
    }

    /**
     * @param BsAccount      $bsAccount
     * @param int            $openingBalance
     * @param null|BsAccount $old
     */
    private function validate(BsAccount $bsAccount, int $openingBalance, BsAccount $old = null) : void
    {
        $ar = $bsAccount->toArray();
        $ar['openingBalance'] = $openingBalance;
        \Validator::make($ar, [
            'name' => 'required',
            'openingBalance' => 'numeric|required|min:0',
        ])
  // ->sometimes('updatedAt', 'date_equals:' . optional($old)->bsAccoount, function ($o) {
  //   return $o->id;
  // })
            ->validate();
    }
}
