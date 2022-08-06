<?php

/**
 * -------------------------------------------------------------------
 *  || Bootstrapping the Application ||
 * -------------------------------------------------------------------
 * 
 * Bootstrapping all environment variables, constants, paths, and class namespaces to autoload in Front Controller (public_html/index.php)
 */
 
/**
 * -------------------------------------------------------------------
 *  || Define Constants & Paths ||
 * -------------------------------------------------------------------
 */
define('PROTOCOL', "https://");
define('APP_DIR', FCPATH . '..' . DS . 'app' . DS);
define('CONFIG_DIR', APP_DIR . 'Config' . DS);
define('CONTROL_DIR', APP_DIR . 'Controllers' . DS);
define('ENTITY_DIR', APP_DIR . 'Entities' . DS);
define('HELPER_DIR', APP_DIR . 'Helpers' . DS);
define('LIB_DIR', APP_DIR . 'Library' . DS);
define('VENDOR_DIR', FCPATH . '..' . DS . 'vendor' . DS);
define('WRITE_DIR', FCPATH . '..' . DS . 'writable' . DS);
define('TEST_DIR', FCPATH . '..' . DS . 'tests' . DS);
define('VIEWS_DIR', FCPATH . '..' . DS . 'app' . DS . 'Views' . DS);
define('INI_FILEPATH', CONFIG_DIR . 'environment' . DS . 'shape-share.ini');

/**
 * -------------------------------------------------------------------
 *  || PSR4 Compliant Class Autoloader ||
 * -------------------------------------------------------------------
 * 
 * Currently using Composer but will add custom autoloader soon.
 */

require(VENDOR_DIR . 'autoload.php');

/**
 * -------------------------------------------------------------------
 *  || Instantiate Configuration Class ||
 * ------------------------------------------------------------------ 
 * 
 * Configuation file can take 1 of 4 flavors:
 *    - Default ('DEFAULT') : Limited configuration, no security, no PHP.ini settings
 *    - Development ('DEV') : Full Database with Debugging, Logging, Errors, PHP *     settings, security, etc
 *    - Testing ('TEST') : Full Database with Fake Values, Debugging, Logging, * *     Errors, etc
 *    - Production ('PROD') : Full Database, Logging, No Debugging, Vague * Error Messages
 */


$config = Config\Configuration::getInstance('DEV');

define('ENV_VAR', $config->get('app', 'environment'));
define('LANG_VAR', $config->get('app', 'language'));
define('CHARSET_VAR', $config->get('app', 'site_charset'));

/**
 * -------------------------------------------------------------------
 *  || Write Configuration Data to shape-share.ini File
 * -------------------------------------------------------------------
 */

$phpConfig = [
  'php' => [
    'display_errors' => $config->get('php', 'display_errors'),
    'display_startup_errors' => $config->get('php', 'display_startup_errors'),
    'error_log' => $config->get('php', 'error_log'),  
    'error_reporting' => $config->get('php', 'error_reporting'),
    'log_errors' => $config->get('php', 'log_errors'), 
    'ignore_repeated_errors' => $config->get('php', 'ignore_repeated_errors'), 
    'ignore_repeated_source' => $config->get('php', 'ignore_repeated_source'),
    'report_memleaks' => $config->get('php', 'report_memleaks'),
    'doc_root' => $config->get('php', 'doc_root'),
    'file_uploads' => $config->get('php', 'file_uploads'),
    'upload_max_filesize' => $config->get('php', 'upload_max_filesize'),
    'max_file_uploads' => $config->get('php', 'max_file_uploads'),
  ],
];

Helpers\PHPIniHelper::write_ini_file($phpConfig, INI_FILEPATH);

/**
 * -------------------------------------------------------------------
 * || Error and Exception Handling ||
 * -------------------------------------------------------------------
 */
