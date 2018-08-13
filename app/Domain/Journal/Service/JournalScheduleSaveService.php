<?php
declare (strict_types = 1);

namespace App\Domain\Journal\Service;

use App\Domain\Journal\Dao\JournalScheduleDao;
use App\Domain\Journal\Dto\JournalSchedule;

class JournalScheduleSaveService
{
    /**
     * @var JournalScheduleDao
     */
    private $dao;

    public function __construct(JournalScheduleDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param JournalSchedule $schedule
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(JournalSchedule $schedule) : JournalSchedule
    {
        $this->validate($schedule);
        $s = \DB::transaction(function () use ($schedule) {
            return $this->dao->createOrFail($schedule);
        });
        logger()->notice('定期仕訳の作成', ['定期仕訳' => $s]);
        return $s;
    }

    /**
     * @param JournalSchedule $schedule
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(JournalSchedule $schedule) : JournalSchedule
    {
        $s = \DB::transaction(function () use ($schedule) {
            $old = $this->dao->findOrFail($schedule->id);
            $this->validate($schedule, $old);
            $old->fill($schedule);
            return $this->dao->updateOrFail($old);
        });
        info('定期仕訳の更新', ['定期仕訳' => $s]);
        return $s;
    }

    private function validate(JournalSchedule $schedule, JournalSchedule $old = null) : void
    {
        $ar = $schedule->toArray();
        \Validator::make($ar, [
            'postDate' => 'required|numeric|between:1,28',
            'debitAccountId' => 'required|numeric|min:1',
            'creditAccountId' => 'required|numeric|min:1',
            'amount' => 'required|numeric|min:1',
            'nextPostDate' => 'required|date',
        ])->validate();
    }
}
