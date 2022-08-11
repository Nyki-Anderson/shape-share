<?php

namespace Entities;

/**
 * All defined routes are mapped having a name, controller, action, params(if any), and method used.
 * 
 * @var string $method - accepted HTTP methods for route ('GET' OR 'POST')
 * @var string $url - URL request with controller/action/param1/param2 etc (in order)
 * @var string $controller - name of controller for route
 * @var string $action - name of action (method) for route
 * @var array $paramsRegex - regex for parameters (if any)
 * @var string $name - name of this route (for reverse routing)
 * @var string $namespace - namespace excluding app\Controllers
 */
class RouteEntity
{
  private $route = [
    'method',
    'url',
    'controller',
    'action',
    'paramsRegex',
    'name',
    'namespace',
    'filters',
  ];

  /**
   * Set defaults for $route class. Certain defaults are specified: $method which defaults to 'GET', $controller defaults to 'Landing', and $action which defaults to 'index'. Some attributes of the route class will be empty such as those that don't require params, regex, or filters.
   */
  public function __construct()
  {
    $this->route['url'] = '';
    $this->route['method'] = 'GET';
    $this->route['controller'] = 'Pages';
    $this->route['action'] = 'index';
    $this->route['paramsRegex'] = null;
    $this->route['paramsMap'] = [0,1];
    $this->route['name'] = null;
    $this->route['namespace'] = null;
    $this->route['filters'] = [];
  }

  /**
   * Magic get function returns value for route attribute, first checking if key exists in $route array.
   *
   * @param string $key
   * @return void
   */
  public function __get(string $key)
  {
    if (array_key_exists($key, $this->route)) {
      
      return $this->route[$key];
    }
  }

  /**
   * Magic set function that checks for custom set method (formatted: setKey) before using default set method.
   *
   * @param string $key
   * @param mixed $value
   */
  public function __set(string $key, mixed $value)
  {
    $setMethod = 'set' . ucfirst($key);
    
    if (function_exists($setMethod)){

      $this->$setMethod($value);

    } else {
      
      if (array_key_exists($key, $this->route)) {
        
        $this->route[$key] = $value;
      }
    }
  }
}