<?php

namespace Library;

/**
 * 
 * 
 * All defined routes are mapped having a name, controller, action, params(if any), and method used.
 * 
 * @var string $url - URL request
 * @var array $config - details of the route
 * @var string $method - accepted HTTP methods for route (GET OR POST)
 * @var string $controller - name of controller for route
 * @var string $action - name of action (method) for route
 * @var array $params - array of params passed thru request URL
 * @var string $targetView - target for this route 
 * @var string $routeName - name of this route (for reverse routing)
 * @var string $namespace - namespace excluding app\Controllers
 * @var array $filters - custom param filters for this route
 */
class Route
{
  private $route = [
    'method',
    'url',
    'controller',
    'action',
    'paramsRegex' => [],
    'name',
    'namespace',
    'filters' => [],
  ];

  /**
   * Set defaults for $route class. Certain defaults are specified: $method which defaults to 'GET', $controller defaults to 'Landing', and $action which defaults to 'index'. Some attributes of the route class will be empty such as those that don't require params, regex, or filters.
   */
  public function __construct()
  {
    $this->route['url'] = '';
    $this->route['method'] = 'GET';
    $this->route['controller'] = 'Static';
    $this->route['action'] = 'index';
    $this->route['params'] = [];
    $this->route['name'] = null;
    $this->route['namespace'] = null;
    $this->route['regex'] = [];
    $this->route['filtes'] = [];
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