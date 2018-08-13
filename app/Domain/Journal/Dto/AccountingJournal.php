<?php
declare(strict_types = 1);

namespace App\Domain\Journal\Dto;

use App\Core\DataAccess\StreDto;
use App\Domain\Journal\Model\AccountingJournalModel;

/**
 * 仕訳
 *
 * @property-read int $id
 * @property int $debitAccountId 借方勘定科目ID
 * @property int $creditAccountId 借方勘定科目ID
 * @property string $journalDate
 * @property string $remarks 摘要
 * @property int $amount 金額
 * @property-read string $createdAt
 * @property-read string $updatedAt
 */
class AccountingJournal extends StreDto
{
    protected static $modelClass = AccountingJournalModel::class;
}
