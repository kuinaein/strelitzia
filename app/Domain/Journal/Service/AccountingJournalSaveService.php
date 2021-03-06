<?php
declare (strict_types = 1);

namespace App\Domain\Journal\Service;

use App\Domain\Account\Dao\AccountTitleDao;
use App\Domain\Journal\Dao\AccountingJournalDao;
use App\Domain\Journal\Dto\AccountingJournal;
use App\Exceptions\StreException;
use Illuminate\Support\Collection;

class AccountingJournalSaveService
{
    /**
     * @var AccountingJournalDao
     */
    private $dao;

    /**
     * @var AccountTitleDao
     */
    private $accountDao;

    public function __construct(
        AccountingJournalDao $dao,
        AccountTitleDao $accountDao
    ) {
        $this->dao = $dao;
        $this->accountDao = $accountDao;
    }

    /**
     * @param AccountingJournal $journal
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(AccountingJournal $journal) : AccountingJournal
    {
        $this->validate($journal);
        $j = \DB::transaction(function () use ($journal) {
            return $this->dao->save($journal);
        });
        logger('記帳', ['仕訳' => $j]);
        return $j;
    }

    /**
     * @param AccountingJournal $journal
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(AccountingJournal $journal) : AccountingJournal
    {
        return \DB::transaction(function () use ($journal) {
            $old = $this->dao->findOrFail($journal->id);
            if ($old instanceof Collection) {
                throw new StreException();
            }
            $this->validate($journal, $old);
            $new = $old->fill($journal);
            $j = $this->dao->save($new);
            logger('記帳修正', ['仕訳' => $j]);
            return $j;
        });
    }

    public function destroy(int $journalId) : void
    {
        \DB::transaction(function () use ($journalId) : void {
            $this->dao->destroy($journalId);
        });
    }

    private function validate(AccountingJournal $journal, AccountingJournal $old = null) : void
    {
        $ar = $journal->toArray();
        \Validator::make($ar, [
            'journalDate' => 'required|date',
            'debitAccountId' => 'required|numeric|min:1',
            'creditAccountId' => 'required|numeric|min:1',
            'amount' => 'required|numeric|min:1',
        ])->validate();
    }
}
