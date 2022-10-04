<?php
declare(strict_types=1);

namespace Exception;

class InvalidArgumentException extends \Exception
{
  public function __construct($message = 'Invalid argument!')
  {
    parent::__construct($message, 500);
  }
}
