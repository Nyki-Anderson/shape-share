<?php

namespace Libraries;

use Entities\RouteEntity;
use Helpers\StringFormatHelper as Formatter;

/**
 * Resource: https://github.com/daveh/php-mvc/blob/master/Core/Router.php
 * Class that matches URLs to the corresponding controller and method by matching against an array of Route objects. Parameters are dynamically assigned (if available).
 * 
 * @param array $routeCollection - all defined routes
 * @param Route $routeMatch - a Route object matched to url with all params defined
 * @param array $routeParams - parameters to be passed to Controller/Method();
 */

class Router
{
  private $routeCollection = [];
  private $routeMatch;
  private $routeParams = [];

   /**
   * Get all routes from the routing collection
   *
   * @return array $routeCollection - all defined route objects
   */
  public function getRouteCollection()
  {
    return $this->routeCollection;
  }

   /**
   * Get currently matched route
   *
   * @return Route $routeMatch - returns Route object of matching route
   */
  public function getRouteMatch()
  {
    return $this->routeMatch;
  }

  /**
   * Get current route parameters
   *
   * @return array $routeParams - parameters of the current url
   */
  public function getRouteParams () 
  {
    return $this->routeParams;
  }

  /**
   * Function that sets values for a new Route Object and then stores the new Route in the $routeCollection. Besides the default route which is '', the url will always list the controller first (if only). If the next element is not the action, the action defaults to 'index'. Do not name action in the route if it is index! ParamRegexes can be set to the second element onward in the absence of the action.
   *
   * @param string $request
   * @param string $url
   * @param string $name
   * @param string $namespace
   * @param array $filters
   * @return void
   */
  public function add(string $request, string $url, string $name = null, string $namespace = null, array $filters = null)
  {
    // New instance of Route object with default parameters
    $route = new RouteEntity;
   
    $route->method = $request;
    
    $urlRegex = $url;

    // Convert url to regex: escape '/'
    $urlRegex = preg_replace('/\//', '\\/', $urlRegex);

    // Convert variables e.g. {controller}
    $urlRegex = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $urlRegex);

    // Convert variables with custom regex {id:\d+}
    $urlRegex = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $urlRegex);

    // Add start and end delimiters and case insensitve flag
    $urlRegex = '/^' . $urlRegex . '$/i';

    $route->url = $urlRegex;

    // Explode url into components. Limit set to -1 so that trailing '/' will not be included in array but this also prevents a value error from being thrown if there is nothing else in the url string.
    $url = $url . '/';


    $urlComponents = explode('/', $url, -1);

    // If explode returns atleast one component and that component is not an empty string.
    if (! empty($urlComponents) && $urlComponents[0] !== '') {

      // The first component will be the controller, no routes will contain action or paramRegex only.
      $route->controller = $urlComponents[0];

      // Remove controller from array (remove starting at element 0 ending after 1 element is removed)
      array_shift($urlComponents);

      // Determine if there are any other components provided in the route.
      if (! empty($urlComponents) && $urlComponents[0] !== '') {

        // If false, component is most likely not regex so it is the action.
        if (! Formatter::isRegex($urlComponents[0])) {
          
          $route->action = $urlComponents[0];

          // Remove action from array 
          array_shift($urlComponents);
        
        // Else, action is not explicitly set, so action defaults to index. This also means a regex param is present
        } else {

          $route->action = 'index';

          $route->paramsRegex[] = $urlComponents[0];
        }
      }

      // Determine if there are any other paramRegex set
      if (! empty($urlComponents) && $urlComponents[0] !== '') {

        foreach ($urlComponents as $component) {

          $route->paramsRegex[] = $component;
        }
    }

      if (isset($name)) {

        $route->name = $name;
      }
     
      if (isset($namespace)) {

        $route->namespace = $namespace;
      }

      if (isset($filters)) {

        $route->filters = $filters;
      }
      

    // Is default route, so no need to set any routeDefinitions, already defined in Route object constructor
    }
  
    // Add new Route object to routeCollection
    $this->routeCollection[] = $route; 
  }

  /**
   * Match the URL Request to the corresponding route url in $routeCollection, storing matching Route object in $this->urlMatch.
   *
   * @param string $url - the URL request
   * @return bool - true if a match is found, otherwise false
   */
  public function matchByUrl(string $url)
  {
    // Iterates through all stored routes until a match is found
    foreach ($this->routeCollection as $route) {

      // Checks if url matches a stored route url
      if (preg_match($route->url, $url)) {

        // Checks if requested method is the same as the stored route's method
        if ($_SERVER['REQUEST_METHOD'] == $route->method) {

          if ($this->checkRegex($url)) {

            $this->routeMatch = clone $route;
            return true; 
          }
        }
      }
    }

    return false;
  }

  /**
   * CheckRegex verifies that each parameter passed through the url matches the regex rules defined in the matching route. It also defines $this->routeParams.
   *
   * @param string $url - current url
   * @return bool true either if no regex is necessary or ALL params match their regex rules. returns false if even one regex rule is not met.
   */
  protected function checkRegex(string $url)
  {
    if (! empty($this->routeMatch->paramRegex) ) {

      $regex = $this->routeMatch->paramRegex;

      $params = $this->captureParams($url);

      for ($i = 0; $i < count($regex); $i++) {

        if (preg_match($regex[$i], $params[$i])) {

          $this->routeParams[$i] = $params[$i];
        }
      }

      if (count($this->routeParams) == count($regex)) {

        return true;
      
      } else {
        
        return false;
      }
    }

    return true;
  }

   /**
   * This function separates the query string at the end of the current url from the rest of the url. Then it stores each parameter in an array ()$key => $value).
   *
   * @param string $url - current url
   * @return array $params - query parameters at the end of the current url
   */
  protected function captureParams(string $url) 
  {
    $params = [];
    $parts = [];

    // If not the default route and the number of paramRegex is greater than 0.
    if ($url !== '' && count($this->routeMatch->paramRegex) > 0) {

      // Explode and splice the non-parameter part of the url
      $parts = explode('&', $url, 2);
      $parts = array_splice($parts, 0, 1);

      // parse_str returns 
      parse_str($parts[0], $params);
    }

    return $params;
  }

  /**
   * Dispatch the route, creating the controller object and running the action method with assigned parameters. First, the controller is formatted to mirror this project's naming convention ie: <ControllerName>Controller, then a new Controller object is made. Next, the call is made to the matchedRoute's method, passing parameters as necessary.
   *
   * @param string $url - the route url
   * @return void
   */
  public function dispatch(string $url)
  {
    $urlString = $this->removeQueryStringVariables($url);

    if ($this->matchByUrl($url)) {

      $controller = $this->routeMatch->controller;
      $controller = Formatter::convertToStudlyCaps($controller);
      $controller = $this->getNamespace() . $controller;

      if (class_exists($controller)) {

        $controllerObject = new $controller($this->routeParams, $this->routeMatch->filters);

        $action = $this->routeMatch->action;
        $action = Formatter::convertToCamelCase($action);

        // Checks if action already has the 'Action' suffix, this will be added later so define routes without the suffix
        if (preg_match('/action$/i', $action) === 0) {

          $controllerObject->$action();
        
        } else {

          throw new \Exception("Method $action in controller $controller cannot be called directly - remove the action suffix to call this method.");
        }

      } else {

        throw new \Exception("Controller class $controller not found.");
      }
    
    } else {

      throw new \Exception("No route matched.", 404);
    }
  }

   /**
   * Remove the query string variables from URL (if any). As the full query string is used for the route, any variables at the end will need to be removed before the route is matched to the routeCollection. (NB the .htaccess file converts the first ? to a & when it's passed through to the $_SERVER variable)
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
    $namespace = 'Controllers\\';

    if (isset($this->routeMatch->namespace)) {

      $namespace = $namespace . $this->routeMatch->namespace;
    }

    return $namespace;
  }

}