<?php
declare (strict_types = 1);

namespace App\Core\DataAccess;

use Illuminate\Support\Collection;

// TODO phan上はテンプレートにしたい...
abstract class StreDao
{
    /**
     * @var string
     */
    protected static $dtoClass;

    /**
     * @var \Eloquent
     */
    private $repo;

    protected function __construct(\Eloquent $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @return \Eloquent
     */
    protected function repo()
    {
        return $this->repo;
    }

    /**
     * @return Collection[StreDto]
     */
    public function all() : Collection
    {
        return $this->repo->all()->map(function ($m) {
            return new static::$dtoClass($m);
        });
    }

    public function findOrFail($dtoId)
    {
        return new static::$dtoClass($this->repo->findOrFail($dtoId));
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
