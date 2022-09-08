<?php

// Define path constants
//mb_internal_encoding('UTF-8'); // String Encoding
define('DS', DIRECTORY_SEPARATOR); 
define('ROOT', dirname(dirname(__FILE__)) . DS); 

require ('../vendor/autoload.php');

/**
 * -------------------------------------------------------------------
 *  || Include Core Framework ||
 * -------------------------------------------------------------------
 */

require('..' . DS . 'framework' . DS . 'Bootstrap.php');

Bootstrap::run();

//require_once(FRAMEWORK_PATH . 'Core/Globals.php');

require_once(FRAMEWORK_PATH . 'Core/Core.php');

/**
 * -------------------------------------------------------------------
 * Define all Routes
 * -------------------------------------------------------------------
 */

  use framework\Core\Route;

  // Default Route
  Route::add('/', function() {});

  Route::add('/landing/register', function (){}, ['get', 'post']);
  Route::add('/landing/login', function (){}, ['get', 'post']);

 /**
 * -------------------------------------------------------------------
 * Initialize Routing
 * -------------------------------------------------------------------
 */

// Trailing '/' doesn't matter, case doesn't matter, and no multi-matching 
Route::run('/', false, false, false);



