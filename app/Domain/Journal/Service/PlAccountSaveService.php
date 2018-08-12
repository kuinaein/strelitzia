<?php
declare (strict_types = 1);

namespace App\Domain\Journal\Service;

use App\Domain\Account\Dao\AccountTitleDao;
use App\Domain\Account\Dao\PlAccountDao;
use App\Domain\Account\Dto\PlAccount;

/**
 * Accountに置くと名前空間同士の相互参照になるのでこちらに置く.
 */
class PlAccountSaveService
{
    private $dao;

    private $accountDao;

    public function __construct(
        PlAccountDao $dao,
        AccountTitleDao $accountDao
    ) {
        $this->dao = $dao;
        $this->accountDao = $accountDao;
    }

    /**
     * @param PlAccount $plAccount
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(PlAccount $plAccount) : PlAccount
    {
        $this->validate($plAccount);
        $a = \DB::transaction(function () use ($plAccount) {
            return $this->dao->save($plAccount);
        });
        logger()->notice('収益・費用科目の追加', ['新科目' => $a]);
        return $a;
    }

    /**
     * @param PlAccount $plAccount
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(PlAccount $plAccount) : PlAccount
    {
        return \DB::transaction(function () use ($plAccount) {
            $old = $this->dao->findOrFail($plAccount->id);
            $this->validate($plAccount, $old);
            $new = $old->fill($plAccount);
            $a = $this->dao->save($new);
            logger()->notice('収益・費用科目の更新', ['科目' => $a]);
            return $a;
        });
    }

    /**
     * @param PlAccount      $plAccount
     * @param null|PlAccount $old
     */
    private function validate(PlAccount $plAccount, PlAccount $old = null) : void
    {
        $ar = $plAccount->toArray();
        $validator = \Validator::make($ar, [
            'name' => 'required',
        ])->validate();
    }
}
