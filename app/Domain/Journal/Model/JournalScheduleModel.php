<?php
declare (strict_types = 1);

namespace App\Domain\Journal\Model;

class JournalScheduleModel extends \Eloquent
{
    protected $table = 'journal_schedule';
    protected $guarded = ['id', \Eloquent::UPDATED_AT, \Eloquent::CREATED_AT];
}
