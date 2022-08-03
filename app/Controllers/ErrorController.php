<?php

namespace Controllers;

use Controllers\Controller;

/**
 * This controller sends a header to the browser to inform it that it's on an error page. It also sets the title and template.
 */
class ErrorController extends Controller
{
  public function process($params)
  {
    header("HTTP/1.0 404 Not Found");

    $this->head['title'] = 'Error 404';

    $this->view = 'error_pages/404_error';
  }
}