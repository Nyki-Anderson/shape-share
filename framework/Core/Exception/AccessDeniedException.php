<?php
declare(strict_types=1);

namespace framework\Exception;

class AccessDeniedException extends \Exception
{
  public function __construct($message = 'Access Denied')
  {
    parent::__construct($message, 403);
  }
}