<?php

declare(strict_types=1);

namespace App\Domain\Account\Service;

use App\Domain\Account\Dao\BsAccountDao;
use App\Domain\Account\Vo\BsAccount;
use Illuminate\Support\Facades\Validator;

class BsAccountSaveService {
  private $dao;

  public function __construct(BsAccountDao $dao) {
    $this->dao = $dao;
  }

  /**
   * @param BsAccount $bsAccount
   * @throws \Illuminate\Validation\ValidationException
   */
  public function create(BsAccount $bsAccount): BsAccount {
    $this->validate($bsAccount);
    $a = $this->dao->create($bsAccount);
    info('資産・負債科目作成: ' . $a->name);
    return $a;
  }

  /**
   * @param BsAccount      $bsAccount
   * @param null|BsAccount $old
   */
  private function validate(BsAccount $bsAccount, BsAccount $old = null): void {
    $validator = Validator::make($bsAccount->toArray(), ['name' => 'required'])
    ->sometimes('updatedAt', 'date_equals:' . optional($old)->bsAccoount, function ($o) {
      return $o->id;
    })
    ->validate();
  }
}
