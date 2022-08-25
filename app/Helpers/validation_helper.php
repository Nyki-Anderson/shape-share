<?php

namespace Helpers;

/**
 * Sanitize user input by trimming trailing spaces, stripping any other slashes, and escape special characters
 * 
 * @param array $inputArray - post array 
 * @return array $escapedInput
 */
if (! function_exists('escapeUserInput')) {
  function escapeUserInput(array $inputArray)
  {
    foreach($inputArray as $key => $value)
    {
      $value = trim($value);
      $value = stripSlashes($value);
      $value = htmlspecialchars($value);

      $escapedInput[$key] = $value;
    }

    return $escapedInput;
  }
}

if (! function_exists('esc')) {
  function esc(string $phpOutput)
  {
    $escapedOutput = htmlspecialchars($phpOutput);
    
    return $escapedOutput;
  }
}
