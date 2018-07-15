<?php

declare(strict_types=1);

namespace App\Domain\Account\Dao;

use App\Domain\Account\Dto\BsAccount;
use App\Domain\Account\Model\AccountTitleModel;

class BsAccountDao {
  /**
   * @var AccountTitleModel
   */
  private $repo;

  public function __construct(AccountTitleModel $repo) {
    $this->repo = $repo;
  }

  public function findOrFail(int $id): BsAccount {
    return new BsAccount($this->repo->findOrFail($id));
  }

  public function save(BsAccount $bsAccount): BsAccount {
    $bsAccount->unwrap()->save();
    return $bsAccount;
  }
}
