<?php

mb_internal_encoding('UTF-8'); // String Encoding

define('DS', DIRECTORY_SEPARATOR); // OS Compatible Directory Separator
define('FCPATH', __DIR__ . DS); //Path to the front controller (this file)
chdir(FCPATH); // Ensure the cwd is pointing to front controller

/**
 * -------------------------------------------------------------------
 *  || Include Bootstrap ||
 * -------------------------------------------------------------------
 */

require_once(FCPATH . '..' . DS . 'app' . DS . 'Config' . DS . 'Bootstrap.php');

/**
 * -------------------------------------------------------------------
 *  || Define Routes ||
 * -------------------------------------------------------------------
 * 
 * Routes should be formatted as follows:
 * 
 *  $router->add([
 *    'request' => 'GET', 
 *    'url' => '/', 
 *    'name' => 'landing', 
 *    'filters' => [],
 *  ]);
 * 
 * OR...
 * 
 *  $router->add([<request>, <url>, <name>, <filters>]);
 */

 $router = new Config\Router();

 $router->add(['GET', '', 'site_landing', null, null]);



/**
 * -------------------------------------------------------------------
 * || Run Application! ||
 * -------------------------------------------------------------------
 */
 $router->dispatch($_SERVER['QUERY_STRING']);

