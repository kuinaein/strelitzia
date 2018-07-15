<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Domain\Account\Dao\PlAccountDao;
use App\Domain\Account\Dto\PlAccount;
use App\Domain\Journal\Service\PlAccountSaveService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlAccountApiController extends Controller {
  private $dao;

  private $saveService;

  public function __construct(PlAccountDao $dao, PlAccountSaveService $saveService) {
    $this->dao = $dao;
    $this->saveService = $saveService;
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   */
  public function store(Request $request): array {
    $a = new PlAccount($request->plAccount);
    $this->saveService->create($a);
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
    $a = new PlAccount($request->bsAccount);
    $this->saveService->update($a);
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
