<?php

declare(strict_types=1);

namespace App\Domain\Account\Dto;

use App\Core\DataAccess\StreDto;
use App\Domain\Account\Model\AccountTitleModel;

class AccountTitle extends StreDto {
  protected static $modelClass = AccountTitleModel::class;
}
