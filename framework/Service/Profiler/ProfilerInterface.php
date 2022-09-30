<?php
declare(strict_types=1);

namespace Service\Profiler;

interface ProfilerInterface
{
  /** @var string $key */
  public function start(string $key): void;

  /** @var string $key */
  public function end(string $key): void;

  /** @return string */
  function render(): string;
}