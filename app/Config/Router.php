<?php

namespace Config;

use Library\Route;
use Helpers\StringFormatHelper as Formatter;

/**
 * Resource: https://github.com/daveh/php-mvc/blob/master/Core/Router.php
 * Class that matches URLs to the corresponding controller and method by matching against an array of Route objects. Parameters are dynamically assigned (if available).
 * 
 * @param array $routeCollection - all defined routes
 * @param Route $routeMatch - a Route object matched to url
 */
class Router
{
  private $routeCollection = [];
  public $routeMatch;

  /**
   * Function that sets values for a new Route Object and then stores the new Route in the $routeCollection.
   *
   * @param array $routeDefinition = [$request = 'GET', $url, $params = [], $paramsRegex = [], $name, $filter = []];
   * @return void
   */
  public function add(array $routeDefinition)
  {
    // New instance of Route object
    $route = new Route();
   
    $route->method = $routeDefinition[0];
    
    // Trim '/' from left/right of url
    $url = $routeDefinition[1];

    // Convert url to regex: escape '/'
    $url = preg_replace('/\//', '\\/', $url);

    // Convert variables e.g. {controller}
    $url = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $url);

    // Convert variables with custom regex {id:\d+}
    preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $url);

    // Add start and end delimiters and case insensitve flag
    $url = '/^' . $url . '$/i';

    $route->url = $url;

    // Explode trimmed url into components
    $urlComponents = trim($routeDefinition[1]);
    $urlComponents = explode('/', $urlComponents);

    if (count($urlComponents) > 1) {

      $route->controller = $urlComponents[0];
      array_splice($urlComponents, 0);

      // Determine if action is specified
      if (count($urlComponents) > 1) {

        $route->action = $urlComponents[1];
        array_splice($urlComponents, 1);

        // Determine if route contains any params 
        if (count($urlComponents) > 1) {

          // Store each param into paramsRegex array
          foreach ($urlComponents as $param) {

            $route->paramsRegex[] = $param;
          }
        
        // Route contains no params
        } else {

          $route->paramsRegex = null;
        }
      
      // Route does not specify action, default is index
      } else {

        $route->action = 'index';
      }
    
    // Route must be the default, assign variables as follows
    } else {

      $route->controller = 'Static';
      $route->action = 'index';
      $route->paramsRegex = null;
      $route->name = 'site_landing';
      $route->filter = null;
    }
    
    $route->name = $routeDefinition[2];

    if (isset($routeDefinition[3])) {

      foreach ($routeDefinition[3] as $filter) {

        $route->filters[] = $filter;
      }
    
    } else {

      $route->filters = null;
    }
  
    // Add new Route object to routeCollection
    $this->routeCollection[] = $route; 
  }

  /**
   * Get all routes from the routing collection
   *
   * @return array $routeCollection - all defined route objects
   */
  public function getRoutes()
  {
    return $this->routeCollection;
  }

  /**
   * Match the URL Request to the corresponding route url in $routeCollection, storing matching Route object in $this->urlMatch.
   *
   * @param string $url - the URL request
   * @return bool - true if a match is found, otherwise false
   */
  public function matchByUrl(string $url)
  {
    foreach ($this->routeCollection as $route) {

      if (preg_match($route->url, $url)) {

        if ($_SERVER['REQUEST_METHOD'] == $route->method) {

          $this->routeMatch = clone $route;
          return true;
        }
      }
    }

    return false;
  }

  /**
   * Get currently matched route
   *
   * @return array params[] - url of currently matched route
   */
  public function getRoute()
  {
    return $this->urlMatch;
  }

  /**
   * Dispatch the route, creating the controller object and running the action method with assigned parameters. First, the controller and method are formatted to mirror this project's naming convention ie: <ControllerName>Controller and <methodName>Action
   *
   * @param string $url - the route url
   * @return void
   */
  public function dispatch(string $url)
  {
    $url = $this->removeQueryStringVariables($url);

    if ($this->matchByUrl($url)) {

      $controller = $this->routeMatch->controller;
      $controller = Formatter::convertToStudlyCaps($controller) . 'Controller';
      $controller = $this->getNamespace() . $controller;

      if (class_exists($controller)) {

        $controllerObject = new $controller($this->routeMatch->params);

        $action = $this->routeMatch->action;
        $action = Formatter::convertToCamelCase($action);

        if (preg_match('/action$/i', $action) == 0) {

          $controllerObject->$action($this->routeMatch->params);
        
        } else {

          throw new \Exception("Method $action in controller $controller cannot be called directly - remove the action suffix to call this method.");
        }

      } else {

        throw new \Exception("Controller class $controller not found.");
      }
    
    } else {

      throw new \Exception('No route matched.', 404);
    }
  }

  /**
   * Remove the query string variables from URL (if any). As the full query string is used for the route, any variables at the end will need to be removed before the route is matched to the routeCollection.
   * 
   * URL                        $_SERVER['QUERY_STRING]   Route
   * -----------------------------------------------------------------
   * shape-share.com                ''                    ''
   * shape-share.com/?              ''                    ''
   * shape-share.com/?page=1        page=1                ''
   * shape-share.com/posts/page=1   posts&page=1          posts
   * shape-share.com/index?page=1   posts/index&page=1    posts/index
   * 
   * A URL of the format shape-share.com/?page (one variable name, no value) won't work. (NB the .htaccess file converts the first ? to a & when it's passed through to the $_SERVER variable)
   *
   * @param string $url - full url
   * @return string $url - url with query string variables removed
   */
  protected function removeQueryStringVariables(string $url)
  {
    if ($url != '') {

      $parts = explode('&', $url, 2);

      if (strpos($parts[0], '=') === false) {

        $url = $parts[0];
      
      } else {

        $url = '';
      }
    }

    return $url;
  }

  /**
   * Get the namespace for controller class, if a namespace is defined in Route object, this is appended.
   *
   * @return string $namespace
   */
  protected function getNamespace()
  {
    $namespace = 'Controllers\\' . $this->routeMatch->namespace;

    return $namespace;
  }
}