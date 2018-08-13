<?php
declare (strict_types = 1);

namespace App\Domain\Account\Dao;

use App\Core\DataAccess\StreDao;
use App\Domain\Account\Dto\BsAccount;
use App\Domain\Account\Model\AccountTitleModel;

class BsAccountDao extends StreDao
{
    protected static $dtoClass = BsAccount::class;

    public function __construct(AccountTitleModel $repo)
    {
        parent::__construct($repo);
    }
}
