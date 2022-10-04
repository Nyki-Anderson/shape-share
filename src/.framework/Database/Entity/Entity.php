<?php
declare(strict_types=1);

namespace Database\Entity;

abstract class Entity
{
  /** @var int */
  protected $id;

  /**
   * @return integer
   */
  public function getId(): int
  {
    return $this->id;
  }

  public function setId(int $id): Entity
  {
    $this->id = $id;
    return $this;
  }
}