<?php
declare (strict_types = 1);

namespace App\Domain\Journal\Model;

class AccountingJournalModel extends \Eloquent
{
    protected $table = 'accounting_journal';
    protected $guarded = ['id', \Eloquent::UPDATED_AT, \Eloquent::CREATED_AT];
}
