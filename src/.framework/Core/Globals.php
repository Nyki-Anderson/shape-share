<?php
declare(strict_types=1);

spl_autoload_register('MVCAutoLoader');

function MVCAutoLoader($class_name)
{
  $className = Core::dirNameFilter($class_name);

  $class = str_replace('\\', DS, $className) . '.php';

  $fileClass = FRAMEWORK_PATH . $class;

  if (file_exists($fileClass)) {

    require_once($fileClass);
  
  } else {

    $fileClass = APP_PATH . $class;

    if (file_exists($fileClass)) {

      require_once($fileClass);
    }
  }

  /**
   * Return translated text.
   *
   * @param $string
   * @return string
   */
  function t($string)
  {
    return htmlspecialchars($string);
  }

  /**
   * Set flash message into session.
   *
   * @param string $message
   * @return void
   */
  function set_flash_message(string $message)
  {
    if (empty($_SESSION['flash_message']) || ! is_array($_SESSION['flash_message'])) {

      $_SESSION['flash_message'] = array();
    }

    $_SESSION['flash_message'][] = $message;
  }

  /**
 * Sanitize user input by trimming trailing spaces, stripping any other slashes, and escape special characters
 * 
 * @param array $inputArray - post array 
 * @return array $escapedInput
 */

  function escapeUserInput(array $inputArray)
  {
    foreach($inputArray as $key => $value)
    {
      if ($key == 'password' || $key == 'passConfirm' || ! isset($value)) {

        $value = $value;
      
      } else {

        $value = trim($value);
        $value = stripSlashes($value);
        $value = htmlspecialchars($value);
      }

      $escapedInput[$key] = $value;
    }

    return $escapedInput;
  }


  function esc(string $phpOutput)
  {
    $escapedOutput = htmlspecialchars($phpOutput);
    
    return $escapedOutput;
  }

}