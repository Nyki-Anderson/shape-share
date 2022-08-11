<?php

mb_internal_encoding('UTF-8'); // String Encoding

define('DS', DIRECTORY_SEPARATOR); // OS Compatible Directory Separator
define('ROOT', dirname(dirname(__FILE__))); // Shape-Share Directory


/**
 * -------------------------------------------------------------------
 *  || Include Bootstrap ||
 * -------------------------------------------------------------------
 */

require_once(ROOT . DS . 'app' . DS . 'Bootstrap.php');

unregisterGlobals();

new Libraries\Core();

/**
 * -------------------------------------------------------------------
 *  || Define Routes ||
 * -------------------------------------------------------------------
 * Besides the default route which is '', the url will always list the controller first (if only). If the next element is not the action, the action defaults to 'index'. Do not name action in the route if it is index! ParamRegexes can be set to the second element onward in the absence of the action.
 * 
 * Routes should be formatted as follows:
 * 
 *  $router->add(<request>, <url>, <name>, <namespace>);
 */
$router = new Libraries\Router;

// Default Router
$router->add('GET', '', 'site_landing');

/**
 * -------------------------------------------------------------------
 * || Run Application! ||
 * -------------------------------------------------------------------
 */

$router->dispatch($_SERVER['QUERY_STRING']);
