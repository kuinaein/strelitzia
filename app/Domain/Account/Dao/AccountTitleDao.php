<?php
declare (strict_types = 1);

namespace App\Domain\Account\Dao;

use App\Domain\Account\Dto\AccountTitle;
use App\Domain\Account\Dto\SystemAccountTitleKey;
use App\Domain\Account\Model\AccountTitleModel;
use Illuminate\Support\Collection;

class AccountTitleDao
{
    /**
     * @var AccountTitleModel;
     */
    private $repo;

    public function __construct(AccountTitleModel $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @return AccountTitle[]
     */
    public function all() : Collection
    {
        return $this->repo->all()->map(function ($m) {
            return new AccountTitle($m);
        });
    }

    public function findOrFail(int $id) : AccountTitle
    {
        return new AccountTitle($this->repo->findOrFail($id));
    }

    public function findOrFailBySystemKey(SystemAccountTitleKey $key) : AccountTitle
    {
        return new AccountTitle($this->repo->where('system_key', $key)->firstOrFail());
    }
}
