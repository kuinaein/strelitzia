<?php

declare(strict_types = 1);

namespace App\Domain\Account\Dto;

use App\Util\Enum;

class AccountTitleType extends Enum {
  const ASSET = 'ASSET';
  const LIABILITY = 'LIABILITY';
  const NET_ASSET = 'NET_ASSET';
  const REVENUE = 'REVENUE';
  const EXPENSE = 'EXPENSE';
  const OTHER = 'OTHER';

  public function isDebitSide() {
    switch ($this->valueOf()) {
      case self::ASSET:
      case self::EXPENSE:
        return true;
      default:
        return false;
    }
  }
}
