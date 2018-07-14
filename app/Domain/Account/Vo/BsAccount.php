<?php

declare(strict_types=1);

namespace App\Domain\Account\Vo;

use App\Domain\Account\Model\AccountTitleModel;
use App\Exceptions\BadVoSourceException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

class BsAccount implements Arrayable, \JsonSerializable {
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
        if ($k === 'updatedAt') {
          // 通常fillableではないが楽観的ロックのために必要
          $ar[MODEL::UPDATED_AT] = $v;
        } else {
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
    return $this->model->{snake_case($name)};
  }

  public function __set($name, $value) {
    return $this->model->{snake_case($name)} = $value;
  }

  public function unwrap(): AccountTitleModel {
    return $this->model;
  }

  public function toArray(): array {
    $result = [];

    foreach ($this->model->toArray() as $k => $v) {
      $result[camel_case($k)] = $v;
    }
    return $result;
  }

  public function jsonSerialize(): array {
    return $this->toArray();
  }
}
