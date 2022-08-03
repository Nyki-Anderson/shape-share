<?php

use Config\Router;
use Config\Psr4Autoloader;

/**
 * _____________________________________________________________________________
 *                || Bootstrapping the Application ||
 * _____________________________________________________________________________

 * Bootstrapping all environment variables, constants, paths, and class namespaces to autoload in Front Controller (public_html/index.php)
 */

/**
 * -----------------------------------------------------------------------------
 *              || Include and Instantiate Configuration Class ||
 * -----------------------------------------------------------------------------

 * Configuation file can take 1 of 4 flavors:
 * - Default ('DEFAULT') : Limited configuration, no security, no PHP.ini
 *      settings
 * - Development ('DEV') : Full Database with Debugging, Logging, Errors, PHP *     settings, security, etc
 * - Testing ('TEST') : Full Database with Fake Values, Debugging, Logging, * *     Errors, etc
 * - Production ('PROD') : Full Database, Logging, No Debugging, Vague * Error Messages
 */

//new Config\Configuration('DEV'); // In Development Mode Now (Change Later)
$config = Config\Configuration::getInstance('DEV');


/**
 * -----------------------------------------------------------------------------
 * || Define Constants and Paths (using DIRECTORY_SEPARATOR - OS Compatibility)
 * _____________________________________________________________________________
 */
define('PROTOCOL', "https://");
define('APP_DIR', FCPATH . DS . '..');
define('WRITE_DIR', FCPATH . DS . '..' . DS . '..' . DS . 'writable');
define('TEST_DIR', FCPATH . DS . '..' . DS . '..' . DS . 'tests');
define('VIEWS_DIR', FCPATH . DS . '..' . DS . 'app' . DS . 'Views');
define('DEFAULT_VIEW', VIEWS_DIR . '/landing' . DS . 'static_pages' . DS . 'landing');

/**
 * _____________________________________________________________________________
 *              || PSR4 Compliant Class Autoloader ||
 * _____________________________________________________________________________
 */

$loader = new Config\Psr4Autoloader();
$loader->register(); // Register the autoloader

// Register the base directories for the namespace prefix
$loader->addNamespace('Shape\Share\vendors', FCPATH . DS . 'vendors');
$loader->addNamespace('Shape\Share\tests', FCPATH . DS . 'tests');
$loader->addNamespace('Shape\Share\Controllers', FCPATH . DS . '../app/Controllers');
$loader->addNamespace('Shape\Share\Library', FCPATH . DS . '../app/Library');
$loader->addNamespace('Shape\Share\Models', FCPATH . DS . '../app/Models');
$loader->addNamespace('Shape\Share\Config',  FCPATH . DS . '../app/Config');
$loader->addNamespace('Shape\Share\Entities', FCPATH . DS . '../app/Entities');