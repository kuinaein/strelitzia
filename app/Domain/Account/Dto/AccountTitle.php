<?php

declare(strict_types = 1);

namespace App\Domain\Account\Dto;

use App\Core\DataAccess\StreDto;
use App\Domain\Account\Model\AccountTitleModel;

/**
 * 勘定科目
 *
 * @property-read int $id
 * @property string $name
 * @property string $systemKey
 * @property string $type
 * @property int $parentId
 * @property-read string $createdAt
 * @property-read string $updatedAt
 */
class AccountTitle extends StreDto {
  protected static $modelClass = AccountTitleModel::class;
}
