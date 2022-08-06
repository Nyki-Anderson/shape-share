<?php

namespace Library;

class View 
{
  public static function render($view, $args = [])
  {
    extract($args, EXTR_SKIP);

    $file = VIEWS_DIR . $view . 'html';

    if (is_readable($file)) {

      require $file;
    
    } else {

      throw new \Exception('$file not found');
    }
  }
}