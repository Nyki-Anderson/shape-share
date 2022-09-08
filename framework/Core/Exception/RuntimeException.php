<?php
declare(strict_types=1);

namespace framework\Exception;

class RuntimeException extends \Exception
{
  public function __construct($message = 'Runtime exception!')
  {
    parent::__construct($message, 500);
  }
}