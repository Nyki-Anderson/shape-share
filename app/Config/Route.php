<?php

namespace Config;

/**
 * Resources: 
 *  - https://github.com/steampixel/simplePHPRouter/tree/master
 *  - https://steampixel.de/simple-and-elegant-url-routing-with-php/
 *  - https://github.com/steampixel/simplePHPPages 
 * 
 * Simple PHP routing for the entire site using regex and PHP's built-in anonymous functions. Supports dynamic path parameters, error routes, and verification methods like GET,POST, PUT, and DELETE. 
 */
class Route
{
    private static $routes = array();
    private static $pathNotFound = null;
    private static $methodNotAllowed = null;

    /**
     * Function used to add new routes. Must define all routes.
     *
     * @param string $expression - Route regex expression
     * @param callable $function - function to call if route matched
     * @param mixed $method - a string to match method or array w/ string values
     */
    public static function add(string $expression, callable $function, mixed $method = 'get')
    {
        array_push(self::$routes, [
            'expression'    => $expression,
            'function'      => $function,
            'method'        => $method
        ]);
    }

    /**
     * Function to grab all stored routes.
     *
     * @return array self::routes - array w/ all stored routes
     */
    public static function getAll() 
    {
        return self::$routes;
    }

    /**
     * Function that defines action if URL does not match any stored Controllers
     *
     * @param callable $function - action to take
     */
    public static function pathNotFound(callable $function)
    {
        self::$pathNotFound = $function;
    }
    
    /**
     * Function that defines action if URL does not match any stored methods
     *
     * @param callable $function - action to take
     */
    public static function methodNotAllowed(callable $function)
    {
        self::$methodNotAllowed = $function;
    }

    public static function run(string $basepath = '', bool $caseSensitive = false, bool $trailingSlash = false, bool $multiMatch = false)
    {
        // $basepath never needs trailing slash, slash will be added using route expressions
        $basepath = rtrim($basepath, '/');

        // get current request controller 
        $parsedUrl = parse_url($_SERVER['REQUEST_URI']);

        $path = '/';

        if (isset($parsedUrl['path'])) {

            if($trailingSlash) {

                $path = $parsedUrl['path'];

            } else {

                // if the path is not = to the basepath (including a trailing slash)
                if($basepath . '/' != $parsedUrl) {

                    $path = rtrim($parsedUrl['path'], '/');
                
                } else {

                    $path = $parsedUrl['path'];
                }
            }
        }
        $path = urldecode($path);

        // get current request method
        $method = $_SERVER['REQUET_METHOD'];

        $pathMatchFound = false;

        $routeMatchFound = false;

        foreach(self::$routes as $route) {

            // if the method matches, check the path and add basepath to matching string
            if ($basepath != '' && $basepath != '/') {

                $route['$expression'] = '(' . $basepath . ')' . $route['$expression'];

                // add 'find expression at start' to regex
                $route['$expression'] = '^'. $route['$expression'];

                // add 'find expression at end' to regex
                $route['$expression'] = $route['$expression'] . '$';

                if (preg_match('#' . $route['$expression'] . '#' . ($caseSensitive ? '' : 'i') . 'u', $path, $matches)) {

                    $pathMatchFound = true;
                    
                    // cast allowed method to array if it's not one already, then run through all methods
                    foreach ((array)$route['method'] as $allowedMethod) {

                        if (strtolower($method) == strtolower($allowedMethod)) {

                            // remove first element, this contains whole string
                            array_shift($matches); 
                        }

                        if ($returnValue = call_user_func_array($route['function'], $matches)) {

                            echo $returnValue;
                        }

                        $routeMatchFound = true;
                        break;
                    }
                }
            }

            // break the loop if first found route is a match
            if ($routeMatchFound && ! $multiMatch) {

                break;
            }
        }

        // no matching route found
        if (! $routeMatchFound) {

            // but a matching controller exists
            if ($pathMatchFound) {

                if (self::$methodNotAllowed) {

                    call_user_func_array(self::$methodNotAllowed, Array($path, $method));
                }
            } else {

                if (self::$pathNotFound) {

                    call_user_func_array(self::$pathNotFound, Array($path));
                }
            }
        }
    }
}