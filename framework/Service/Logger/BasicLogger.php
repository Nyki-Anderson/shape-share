<?php
declare(strict_types=1);

namespace framework\Service\Logger;

class BasicLogger implements LoggerInterface
{
  public const INFO = 200;
  public const ERROR = 400;
  public const CRITICAL = 500;

  /**
   * @param string $message
   * @param array $context
   * @return void
   */
  public function error(string $message, array $context = []): void
  {
    $this->log((string)self::ERROR, $message, $context);
  }

  /**
   * @param string $message
   * @param array $context
   * @return void
   */
  public function info(string $message, array $context = []): void
  {
    $this->log((string)self::INFO, $message, $context);
  }

  public function critical(string $message, array $context = []): void
  {
    $this->log((string)self::CRITICAL, $message, $context);
  }

  protected function log(string $level, string $message, array $context = []): void
  {
    $file = LOG_PATH . DS . date('d-m-Y') . '.log';

    $content = sprintf('%s - LEVEL: %s, MESSAGE "%s", CONTEXT: %s', date('H:i:s d-m-Y'), $level, $message, json_encode($context)) . PHP_EOL;

    file_put_contents($file, $content, FILE_APPEND);
  }
}