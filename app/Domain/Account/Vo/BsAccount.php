<?php

declare(strict_types=1);

namespace App\Domain\Account\Vo;

use App\Domain\Account\Model\AccountTitleModel;
use App\Exceptions\BadVoSourceException;
use Illuminate\Contracts\Support\Arrayable;

class BsAccount implements AccountTitle, Arrayable, \JsonSerializable {
  /**
   * @var AccountTitleModel
   */
  private $model;

  /**
   * @param AccounTitleModel|array $src
   */
  public function __construct($src) {
    if ($src instanceof AccountTitleModel) {
      $this->model = $src;
    } elseif (is_array($src)) {
      $this->model = new AccountTitleModel();
      $ar = [];

      foreach ($src as $k => $v) {
        switch ($k) {
  case 'id':
  case 'updatedAt':
  // 通常fillableではないがバリデーションのために必要
  $this->model->{snake_case($k)} = $v;
  break;
  default:
  $ar[snake_case($k)] = $v;
  }
      }
      $this->model->fill($ar);
    } else {
      throw new BadVoSourceException(
  'VOのコンストラクタ引数はModelか連想配列にしてください: ' . get_class($src)
  );
    }
  }

  public function __get($name) {
    return $this->unwrap()->{snake_case($name)};
  }

  public function __set($name, $value) {
    return $this->unwrap()->{snake_case($name)} = $value;
  }

  public function unwrap(): AccountTitleModel {
    return $this->model;
  }

  /**
   * @param BsAccount $another
   * @return $this
   */
  public function fill(self $another): self {
    $this->unwrap()->fill($another->toArray());
    return $this;
  }

  public function toArray(): array {
    $result = [];

    foreach ($this->unwrap()->toArray() as $k => $v) {
      $result[camel_case($k)] = $v;
    }
    return $result;
  }

  public function jsonSerialize(): array {
    return $this->toArray();
  }
}
