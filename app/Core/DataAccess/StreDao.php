<?php
declare (strict_types = 1);

namespace App\Core\DataAccess;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

// TODO phan上はテンプレートにしたい...
abstract class StreDao
{
    /**
     * @var string
     */
    protected static $dtoClass;

    /**
     * @var Model
     */
    private $repo;

    protected function __construct(Model $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @return Collection<StreDto>
     */
    public function all() : Collection
    {
        return $this->repo->all()->map(function ($m) {
            return new static::$dtoClass($m);
        });
    }

    /**
     * @param mixed $id
     * @return StreDto
     * @phan-return mixed
     */
    public function findOrFail($id)
    {
        return new static::$dtoClass($this->repo->findOrFail($id));
    }

    /**
     * @param StreDto $dto
     * @return StreDto
     */
    public function createOrFail($dto)
    {
        return $this->updateOrFail($dto);
    }

    /**
     * @param StreDto $dto
     * @return StreDto
     * @phan-param mixed $dto
     * @phan-return mixed
     */
    public function updateOrFail($dto)
    {
        $dto->unwrap()->save();
        return $dto;
    }
}
