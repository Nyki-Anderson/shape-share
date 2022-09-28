<?php
declare(strict_types=1);

namespace Database\Orm;

interface IOrm
{
  public function getAll($limit);

  public function getById($id);

  public function execute($sql, array $params);
}