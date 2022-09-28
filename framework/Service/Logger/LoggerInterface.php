<?php
declare(strict_types=1);

namespace Service\Logger;

interface LoggerInterface
{
  /**
   * @param string $message
   * @param array $context
   * @return void
   */
  public function error(string $message, array $context = []): void;

  /**
   * @param string $message
   * @param array $context
   * @return void
   */
  public function info(string $message, array $context = []): void;

  /**
   * @param string $message
   * @param array $context
   * @return void
   */
  public function critical(string $message, array $context = []): void;
}