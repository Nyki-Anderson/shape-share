<?php
declare(strict_types=1);

namespace framework\Core;

include(HELPER_PATH . 'string_format_helper.php');

use function Helpers\convertToCamelCase;
use function Helpers\convertToStudlyCaps;

class Route
{
 private static $routes = [];
 private static $pathNotFound = null;
 private static $methodNotAllowed = null;

 /**
  * ------------------------------------------------------------------
  * Add a New Route
  * ------------------------------------------------------------------
  *
  * @param string $expression - route string or expression
  * @param callable $function - function to call if route with allowed method is found
  * @param string|array $method - either a string of allowed methods or an array with string values
  * @return void
  */
  public static function add(string $requestURI, callable $function, array $method = ['get'])
  {
    array_push(self::$routes, Array(
      'requestURI'  => $requestURI,
      'function'    => $function,
      'method'      => $method,
    ));
  }

  public static function getAll()
  {
    return self::$routes;
  }

  public static function pathNotFound(callable $function)
  {
    self::$pathNotFound = $function;
  }

  public static function methodNotAllowed(callable $function)
  {
    self::$methodNotAllowed = $function;
  }

  public static function run(string $basepath = '', bool $case_matters = false, bool $trailing_slash_matters = false, bool $multimatch = false)
  {
    $basepath = rtrim($basepath, '/');

    $parsed_url = parse_url(
      (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . 
      $_SERVER['HTTP_HOST'] . 
      $_SERVER['REQUEST_URI']);

    $path = '/';

    if (isset($parsed_url['path'])) 
    {
      if ($trailing_slash_matters)
      {
        $path = $parsed_url['path'];
      
      } else {

        if ($basepath . '/' != $parsed_url['path'])
        {
          $path = rtrim($parsed_url['path'], '/');
        
        } else {

          $path = $parsed_url['path'];
        }
      }
    }

    $path_match_found = false;

    $route_match_found = false;

    foreach (self::$routes as $route)
    {
      if (((strcasecmp($path, $route['requestURI']) === 0) && $case_matters) || (strcmp($path, $route['requestURI']) === 0) && ! $case_matters){

        $path_match_found = true;

        foreach ($route['method'] as $allowedMethod)
        {
          if (strtolower($_SERVER['REQUEST_METHOD']) == ($allowedMethod))
          {
            $route_match_found = true;

            $temp = explode('/', substr($path, 1));

            // Default Controller
            $controller = convertToStudlyCaps(empty($temp[0]) ? 'landing' : $temp[0]);

            // Default Action
            $action = convertToCamelCase(! isset($temp[1]) || empty($temp[1]) ? 'index' : $temp[1]);

            if (! file_exists(CONTROLLER_PATH . "{$controller}Controller.php")) {

              $controller = "Error";
              $action = "index";
            }
        
            if (! method_exists("Controllers\\{$controller}Controller", "{$action}")) {
        
              $controller = 'Error';
              $action = 'index';
            }

            self::map($controller, $action);
            break;
          }
        }
      }

      if ($route_match_found && ! $multimatch)
      {
        break;
      }
    }

    if (! $route_match_found)
    {
      if ($path_match_found)
      {
        if (self::$methodNotAllowed)
        {
          call_user_func_array(self::$methodNotAllowed, Array($path,  $_SERVER['REQUEST_METHOD']));
        }
      
      } else {

        if (self::$pathNotFound)
        {
          call_user_func_array(self::$pathNotFound, Array($path));
        }
      }
    }
  }

  public static function map(string $controller, string $action = 'index')
  {
    $controllerName = 'Controllers\\' . $controller . 'Controller';
    $actionName = $action;
   
    $paramString = '';

    foreach ($_GET as $key => $value){

      $paramString .= $value . ', ';
    }

    $paramString = rtrim($paramString, ',');

    $controller = new $controllerName;

    $controller->$actionName($paramString);
  }
}