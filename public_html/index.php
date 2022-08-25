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

require('..' . DS . 'framework' . DS . 'Core' . DS . 'Bootstrap.php');
require('..' . DS . 'framework' . DS . 'Core' . DS . 'Controller.php');
require('..' . DS . 'framework' . DS . 'Database' . DS . 'DatabasePDO.php');

Bootstrap::run();

/**
 * -------------------------------------------------------------------
 * Define all Routes
 * -------------------------------------------------------------------
 */

  use Core\Route;

  // Default Router
  Route::add('/', function() {
    $data = [
      'title' => 'Welcome to Shape-Share!',
      'description' => 'Shape-Share is a simple online image sharing platform where members can upload, react, save, and search for images of shapes.',
    ];
    include VIEW_PATH . 'static_pages' . DS . 'site_landing.html';
  });

  Route::add('/register', function () {Route::map('Index', 'register');});
  Route::add('/register', function () {Route::map('Index', 'register');}, 'post');
  Route::add('/login', function () {Route::map('Index', 'login');});

 /**
 * -------------------------------------------------------------------
 * Initialize Routing
 * -------------------------------------------------------------------
 */

// Trailing '/' doesn't matter, case doesn't matter, and no multimatching 
Route::run('/', false, false, false);



