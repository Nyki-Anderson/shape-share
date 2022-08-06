<?php

namespace Helpers;

class PHPIniHelper
{

  public static function write_ini_file($data, $filepath)
  {
    $content = "";

    $parseINI = parse_ini_file($filepath, true);
    
    foreach ($data as $section => $values) {

      $content .= "[" . $section . "]\n";

      foreach ($values as $key => $value) {

        $content .= $key . "=" . $value . "\n";
      }
    }

    if (! $handle = fopen($filepath, "w+")) {

      return false;
    }

    $success = fwrite($handle, $content);
    fclose($handle);

    return $success;
  }
}