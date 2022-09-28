<?php
declare(strict_types=1);

namespace Exception;

use Core\Controller;

class ExceptionController extends Controller
{
  public function render(\Throwable $exception)
  {
    $message = $this->getEnvironment() == 'prod' ? t("Fatal error!") : $exception->getMessage();

    return $this->renderView("exception", ["message" => $message], null, false, 500);
  }
}