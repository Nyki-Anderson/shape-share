<?php

namespace Core;

class Controller 
{

  public function __construct()
  {
    
  }

  public function redirect($url, $message, $wait = 0)
  {
    if ($wait == 0)
    {
      header("Location: $url");
    
    } else {

      include VIEW_PATH . "$message.html";
    }

    exit;
  }
}