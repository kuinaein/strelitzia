<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Domain\Account\Dao\BsAccountDao;
use App\Domain\Account\Service\BsAccountSaveService;
use App\Domain\Account\Vo\BsAccount;
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
   * Display a listing of the resource.
   */
  public function index(): array {
    return ['data' => $this->dao->all(), 'messsage' => 'OK'];
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   */
  public function store(Request $request): array {
    $a = new BsAccount($request->all());
    $a = $this->saveService->create($a);
    return ['data' => $a, 'messsage' => 'OK'];
  }

  /**
   * Display the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function show($id) {
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id) {
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int                      $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id) {
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id) {
  }
}
