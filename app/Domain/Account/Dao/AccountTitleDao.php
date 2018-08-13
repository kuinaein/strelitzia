<?php
declare (strict_types = 1);

namespace App\Domain\Account\Dao;

use App\Core\DataAccess\StreDao;
use App\Domain\Account\Dto\AccountTitle;
use App\Domain\Account\Dto\SystemAccountTitleKey;
use App\Domain\Account\Model\AccountTitleModel;

class AccountTitleDao extends StreDao
{
    protected static $dtoClass = AccountTitle::class;

    public function __construct(AccountTitleModel $repo)
    {
        parent::__construct($repo);
    }

    public function findOrFailBySystemKey(SystemAccountTitleKey $key) : AccountTitle
    {
        return new AccountTitle($this->repo()->where('system_key', '=', $key)->firstOrFail());
    }
}
