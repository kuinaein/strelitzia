<?php

declare(strict_types=1);

namespace App\Domain\Account\Dao;

use App\Domain\Account\Model\AccountTitleModel;
use App\Domain\Account\Vo\BsAccount;

class BsAccountDao {
  /**
   * @var AccountTitleModel
   */
  private $repo;

  public function __construct(AccountTitleModel $repo) {
    $this->repo = $repo;
  }

  public function save(BsAccount $bsAccount): BsAccount {
    $bsAccount->unwrap()->save();
    return $bsAccount;
  }
}
