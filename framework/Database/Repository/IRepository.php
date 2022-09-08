<?php
declare(strict_types=1);

namespace framework\Database\Repository;

use framework\Database\Entity\Entity;

interface IRepository
{
  public function getById(int $id): Entity;

  public function getAll(): array;

  public function persist(Entity $entity): IRepository;
}