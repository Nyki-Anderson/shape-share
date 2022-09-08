<?php
declare(strict_types=1);

namespace framework\Exception;

class NotFoundException extends \Exception
{
  public function __construct($message = '404 not found!')
  {
    parent::__construct($message, 404);
  }
}