<?php

// Define path constants
//mb_internal_encoding('UTF-8'); // String Encoding
define('DS', DIRECTORY_SEPARATOR); 
define('ROOT', dirname(dirname(__FILE__)) . DS); 

require('../vendor/autoload.php');

/**
 * -------------------------------------------------------------------
 *  || Include Core Framework ||
 * -------------------------------------------------------------------
 */

require('..' . DS . 'framework' . DS . 'Bootstrap.php');

Bootstrap::run('dev');

/**
 * -------------------------------------------------------------------
 * Define all Routes
 * -------------------------------------------------------------------
 */

use Core\Route;

  // Default Route
  Route::add('/', function() {});

  Route::add('/register/', function (){}, ['get', 'post']);
  Route::add('/login/', function (){}, ['get', 'post']);

 /**
 * -------------------------------------------------------------------
 * Initialize Routing
 * -------------------------------------------------------------------
 */

// Trailing '/' doesn't matter, case doesn't matter, and no multi-matching 
Route::run('/', false, false, false);



