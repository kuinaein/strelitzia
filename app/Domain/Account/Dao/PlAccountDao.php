<?php
declare (strict_types = 1);

namespace App\Domain\Account\Dao;

use App\Domain\Account\Dto\PlAccount;
use App\Domain\Account\Model\AccountTitleModel;
use App\Core\DataAccess\StreDao;

class PlAccountDao extends StreDao
{
    protected static $dtoClass = PlAccount::class;

    public function __construct(AccountTitleModel $repo)
    {
        parent::__construct($repo);
    }
}
