<?php
declare (strict_types = 1);

namespace App\Domain\Account\Dao;

use App\Domain\Account\Dto\PlAccount;
use App\Domain\Account\Model\AccountTitleModel;

class PlAccountDao
{
    /**
     * @var AccountTitleModel
     */
    private $repo;

    public function __construct(AccountTitleModel $repo)
    {
        $this->repo = $repo;
    }

    public function findOrFail(int $id) : PlAccount
    {
        return new PlAccount($this->repo->findOrFail($id));
    }

    public function save(PlAccount $plAccount) : PlAccount
    {
        $plAccount->unwrap()->save();
        return $plAccount;
    }
}
