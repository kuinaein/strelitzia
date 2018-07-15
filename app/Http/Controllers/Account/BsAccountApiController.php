<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Domain\Account\Dao\BsAccountDao;
use App\Domain\Account\Dto\BsAccount;
use App\Domain\Journal\Service\BsAccountSaveService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BsAccountApiController extends Controller {
  private $dao;

  private $saveService;

  public function __construct(BsAccountDao $dao, BsAccountSaveService $saveService) {
    $this->dao = $dao;
    $this->saveService = $saveService;
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   */
  public function store(Request $request): array {
    $a = new BsAccount($request->bsAccount);
    $this->saveService->create($a, (int) $request->openingBalance);
    return ['messsage' => 'OK'];
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int                      $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, int $id): array {
    $a = new BsAccount($request->bsAccount);
    $this->saveService->update($a, (int) $request->openingBalance);
    return ['messsage' => 'OK'];
  }

  /*
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  // public function destroy($id) {
  // }
}
