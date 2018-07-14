<?php

declare(strict_types=1);

namespace App\Domain\Account\Dao;

use App\Domain\Account\Model\AccountTitleModel;
use App\Domain\Account\Vo\AccountTitleType;
use App\Domain\Account\Vo\BsAccount;
use Illuminate\Support\Collection;

class BsAccountDao {
  /**
   * @var AccountTitleModel;
   */
  private $repo;

  public function __construct(AccountTitleModel $repo) {
    $this->repo = $repo;
  }

  /**
   * @return BsAccount[]
   */
  public function all(): Collection {
    return $this->repo
    ->whereIn('type', [AccountTitleType::ASSET, AccountTitleType::LIABILITY, AccountTitleType::NET_ASSET])
    ->get()
    ->map(function ($m) {
      return new BsAccount($m);
    });
  }

  public function create(BsAccount $bsAccount): BsAccount {
    $bsAccount->unwrap()->save();
    return $bsAccount;
  }
}
