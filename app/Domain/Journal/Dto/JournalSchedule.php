<?php
declare (strict_types = 1);

namespace App\Domain\Journal\Dto;

use App\Core\DataAccess\StreDto;
use App\Domain\Journal\Model\JournalScheduleModel;

/**
 * 仕訳スケジュール
 *
 * @property-read int $id
 * @property int $debitAccountId 借方勘定科目ID
 * @property int $creditAccountId 借方勘定科目ID
 * @property bool $enabled 有効フラグ
 * @property string $remarks 摘要
 * @property int $amount 金額
 * @property string $nextPostDate 次の仕訳日
 * @property-read string $createdAt
 * @property-read string $updatedAt
 */
class JournalSchedule extends StreDto
{
    protected static $modelClass = JournalScheduleModel::class;
}
