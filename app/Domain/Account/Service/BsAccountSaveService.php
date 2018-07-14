<?php

declare(strict_types=1);

namespace App\Domain\Account\Service;

use App\Domain\Account\Dao\AccountTitleDao;
use App\Domain\Account\Dao\BsAccountDao;
use App\Domain\Account\Vo\BsAccount;
use Illuminate\Support\Facades\Validator;

class BsAccountSaveService {
  private $dao;

  private $accountDao;

  public function __construct(BsAccountDao $dao, AccountTitleDao $accountDao) {
    $this->dao = $dao;
    $this->accountDao = $accountDao;
  }

  /**
   * @param BsAccount $bsAccount
   * @throws \Illuminate\Validation\ValidationException
   */
  public function create(BsAccount $bsAccount): BsAccount {
    $this->validate($bsAccount);
    $a = $this->dao->save($bsAccount);
    info('資産・負債科目の追加', ['新科目' => $a]);
    return $a;
  }

  /**
   * @param BsAccount $bsAccount
   * @throws \Illuminate\Validation\ValidationException
   * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
   */
  public function update(BsAccount $bsAccount): BsAccount {
    $old = $this->accountDao->findOrFail($bsAccount->id);
    $this->validate($bsAccount, $old);
    $new = $old->fill($bsAccount);
    $a = $this->dao->save($new);
    info('資産・負債科目の更新', ['科目' => $a]);
    return $a;
  }

  /**
   * @param BsAccount      $bsAccount
   * @param null|BsAccount $old
   */
  private function validate(BsAccount $bsAccount, BsAccount $old = null): void {
    $validator = Validator::make($bsAccount->toArray(), ['name' => 'required'])
  // ->sometimes('updatedAt', 'date_equals:' . optional($old)->bsAccoount, function ($o) {
  //   return $o->id;
  // })
    ->validate();
  }
}
