<?php

declare(strict_types=1);

namespace App\Core\DataAccess;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

abstract class StreDtoLoadService {
  /**
   * @var StreDao
   */
  private $dao;

  protected function __construct(StreDao $dao) {
    $this->dao = $dao;
  }

  /**
   * @return StreDto[]
   */
  public function all(): Collection {
    return DB::transaction(function () {
      return $this->dao->all();
    });
  }

  /**
   * @param mixed $id
   * @return StreDto
   */
  public function find($id) {
    return DB::transaction(function () {
      return $this->dao->findOrFail($id);
    });
  }
}
