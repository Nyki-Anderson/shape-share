<?php
declare(strict_types=1);

namespace framework\Core\DependencyInjection;

use framework\Exception\RuntimeException;

interface ContainerInterface
{
  /**
   * @param string $id
   * @param object $service
   * @return void
   */
  public function set(string $id, object $service): void;

  /**
   * @param string $className
   * @return object
   * @throws RuntimeException
   */
  public function get(string $className): object;
}