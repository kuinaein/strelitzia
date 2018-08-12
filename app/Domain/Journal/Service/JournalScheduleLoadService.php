<?php
declare (strict_types = 1);

namespace App\Domain\Journal\Service;

use App\Core\DataAccess\StreDtoLoadService;
use App\Domain\Journal\Dao\JournalScheduleDao;

class JournalScheduleLoadService extends StreDtoLoadService
{
    public function __construct(JournalScheduleDao $dao)
    {
        parent::__construct($dao);
    }
}
