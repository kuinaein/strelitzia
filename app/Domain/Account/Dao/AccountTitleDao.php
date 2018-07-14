<?php

declare(strict_types=1);

namespace App\Domain\Account\Dao;

use App\Domain\Account\Model\AccountTitleModel;
use App\Domain\Account\Vo\AccountTitleType;
use App\Domain\Account\Vo\BsAccount;
use Illuminate\Support\Collection;

class AccountTitleDao {
  /**
   * @var AccountTitleModel;
   */
  private $repo;

  public function __construct(AccountTitleModel $repo) {
    $this->repo = $repo;
  }

  /**
   * @return AccountTitle[]
   */
  public function all(): Collection {
    return $this->repo->all()->map(function ($m) {
      return new BsAccount($m);
    });
  }
}
