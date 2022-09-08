<?php
declare(strict_types=1);

namespace framework\Service\Profiler;

use framework\Config\Configuration;

class Profiler implements ProfilerInterface
{
  const FIELD_START = 'start';
  const FIELD_END = 'end';
  const FIELD_DIFF = 'diff';

  protected $data = [];

  /**
   * @param string $key
   * @return void
   */
  public function start(string $key): void
  {
    $this->data[$key] = [
      self::FIELD_START => microtime(true)
    ];
  }

  /**
   * @param string $key
   * @return void
   */
  public function end(string $key): void
  {
    $this->data[$key][self::FIELD_END] = microtime(true);

    // diff
    $this->data[$key][self::FIELD_DIFF] = $this->data[$key][self::FIELD_END] - $this->data[$key][self::FIELD_START] ?? 0;
  }

  public function render(): string
  {
    $html = '<div class="profiler-wrapper">';

    foreach ($this->data as $name => $data) {

      $html .= '<p>';
      $html .= '[' . $name . '] time exec: ' . ($data[self::FIELD_DIFF] * 1000) . ' ms';
      $html .= '</p>';
    }

    $html .= '</div>';

    return $html;
  }
}