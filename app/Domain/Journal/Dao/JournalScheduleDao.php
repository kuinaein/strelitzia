<?php

declare(strict_types=1);

namespace App\Domain\Journal\Dao;

use App\Core\DataAccess\StreDao;
use App\Domain\Journal\Dto\JournalSchedule;
use App\Domain\Journal\Model\JournalScheduleModel;

class JournalScheduleDao extends StreDao {
  protected static $dtoClass = JournalSchedule::class;

  public function __construct(JournalScheduleModel $repo) {
    parent::__construct($repo);
  }
}
