<?php

namespace app\Helpers;

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

if (! function_exists('verifyPasswordsMatch')) {

  function verifyPasswordsMatch(string $password1, string $password2)
  {
    if (strcmp($password1, $password2) === 0) 
    {
      return true;
    
    } else {

      return false;
    }
  }
}

if (! function_exists('validateUsername')) {

  function validateUsername(string $username)
  {
    $regex = '/(^[a-z][A-Z])(\w+-){5,25}$/';

    if (preg_match($regex, $username))
    {
      return true;
    
    } else {

      return false;
    }
  }
}

if (! function_exists('validatePassword')) {

  function validatePassword(string $password)
  {
    $regex = '/(^[a-z][A-Z])(\w+-!@#%&\*\$){8,25}$/';

    if (preg_match($regex, $password))
    {
      return true;
    
    } else {

      return false;
    }
  }
}
