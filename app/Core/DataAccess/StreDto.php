<?php
declare (strict_types = 1);

namespace App\Core\DataAccess;

use App\Exceptions\BadVoSourceException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;

abstract class StreDto implements Arrayable, \JsonSerializable
{
    /**
     * @var string
     */
    protected static $modelClass;

    /**
     * @var Model
     */
    private $model;

    /**
     * @param null|array|Model $src
     */
    public function __construct($src = null)
    {
        if (!isset($src)) {
            $this->model = new static::$modelClass();
        } elseif ($src instanceof static::$modelClass) {
            $this->model = $src;
        } elseif (is_array($src)) {
            $this->model = new static::$modelClass();
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

    public function __get(string $name)
    {
        return $this->unwrap()->{snake_case($name)};
    }

    public function __set(string $name, $value)
    {
        return $this->unwrap()->{snake_case($name)} = $value;
    }

    public function unwrap() : Model
    {
        return $this->model;
    }

    /**
     * @param self $another
     * @return $this
     */
    public function fill(self $another) : self
    {
        $this->unwrap()->fill($another->unwrap()->toArray());
        return $this;
    }

    public function jsonSerialize() : array
    {
        return $this->toArray();
    }

    public function toArray() : array
    {
        $result = [];

        foreach ($this->unwrap()->toArray() as $k => $v) {
            $result[camel_case($k)] = $v;
        }
        return $result;
    }
}
