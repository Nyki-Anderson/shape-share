<?php

namespace Libraries;

use Helpers\StringFormatHelper as Formatter;
/**
 * ------------------------------------------------------------
 * || Core Class for Creating URL and Loading Core
 * ------------------------------------------------------------
 * 
 * URL Format - /controller/method/param
 */

 class Core
{
  protected $currentController = 'Pages';
  protected $currentMethod = 'index';
  protected $params = [];

  /**
   * Get the current URl and explode it into an array by '/'
   *
   * @return array $url 
   */
  public function getUserURL()
  {
    // If the url param is set
    if (isset($_GET['url'])) {

      $url = rtrim($_GET['url'], '/');
      $url = filter_var($url, FILTER_SANITIZE_URL);
      $url = explode('/', $url);

      // Return url to whatever place it's being called.
      return $url;
    }
  }

  /**
   * Account for all url components as controller, method, and params then call the function defined.
   */
  public function __construct() {

    // If you want to output the value of the array use:
    // print_r($this->getUrl());
    $url = $this->getUserURL();

    if ($url != '') {
      // If file exists, we set it as the current controller and format the controller as:
      // 'ControllerNames.php'
      $this->currentController = Formatter::convertToStudlyCaps($url[0]) . '.php';

      // Remove first index in url array
      array_shift($url);

      // Require and instantite the controller
      require_once(CONTROL_DIR . $this->currentController);
      $this->currentController = new $this->currentController;

      // Check for second parameter of the url
      if (isset($url[0])) {

        // Check if method exists in controller, if it does, reformat it to camelCase
        if (method_exists($this->currentController, $url[0])) {

          $this->currentMethod = Formatter::convertToCamelCase($url[0]);

          array_shift($url);

          // If url still has parameters left we add to params, otherwise we default to an empty array
          $this->params = $url ? array_values($url) : [];

          // Call a callback with array of parms
          call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
        }
      }
    }
  }
}