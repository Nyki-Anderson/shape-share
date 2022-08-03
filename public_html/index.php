<?php

use Config\Route;
use Config\Bootstrap;
use Config\View;

mb_internal_encoding('UTF-8'); // String Encoding

define('DS', DIRECTORY_SEPARATOR); // OS Compatible Directory Separator
define('FCPATH', __DIR__ . DS); //Path to the front controller (this file)
chdir(FCPATH); // Ensure the cwd is pointing to front controller

/**
 * _____________________________________________________________________________
 *                          || Routes ||
 * _____________________________________________________________________________
 * 
 *  Routes take these forms:
 * 
 *    1.  Route::add('/<controller>', function() {
 *          View::render(<controller>, <index of controller>)->print();
 *         });
 * 
 *    2.  Route::add('/<controller>/<method>', function() {
 *          View::render(<controller>, <method of controller>)->print();
 *        });
 * 
 *    3.  Route::add('/<controller>/<method>/<regex>/<regex>', function(param1,
 *        param2) {
 *          View::render(<controller>, <method of controller>)->print();
 *        });
 */

  // Default 
  Route::add(DEFAULT_VIEW, function() {
    View::render('HomeController', 'index')->print();
  });

 /**
  *  ---------------------------------------------------------------------------
  *                       || Run Application! ||
  */
  Route::run(DEFAULT_VIEW, true, false, true);

