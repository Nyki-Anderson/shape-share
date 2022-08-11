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
define('APP_DIR', ROOT . DS . 'app' . DS);
define('FCPATH', ROOT . DS . 'public_html' . DS);
define('CONFIG_DIR', APP_DIR . 'Config' . DS);
define('CONTROL_DIR', APP_DIR . 'Controllers' . DS);
define('MODEL_DIR', APP_DIR . 'Models' . DS);
define('ENTITY_DIR', APP_DIR . 'Entities' . DS);
define('HELPER_DIR', APP_DIR . 'Helpers' . DS);
define('LIB_DIR', APP_DIR . 'Libraries' . DS);
define('VENDOR_DIR', ROOT . DS . 'vendor' . DS);
define('WRITE_DIR', ROOT . DS . 'writable' . DS);
define('TEST_DIR', ROOT . DS . 'tests' . DS);
define('VIEWS_DIR', ROOT . DS . 'app' . DS . 'Views' . DS . DS);
define('INI_FILEPATH', ROOT . DS . '.user.ini');
define('URL_ROOT', 'https' . '://' . $_SERVER['HTTP_HOST'] . DS);
define('SITE_NAME', 'local.shape-share');
define('CSS_DIR', URL_ROOT . 'assets' . DS . 'css' . DS);

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

define('ENVIRONMENT_VAR', $config->get('app', 'environment'));
define('LANG_VAR', $config->get('app', 'language'));
define('CHARSET_VAR', $config->get('app', 'site_charset'));

/**
 * -------------------------------------------------------------------
 *  || Write Configuration Data to .user.ini File (.htaccess)
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
 * || Configure and Extend Twig Template Engine ||
 * -------------------------------------------------------------------
 */

define('TWIG_DEBUG', $config->get('twig', 'debug'));
define('TWIG_CACHE', $config->get('twig', 'cache'));
define('TWIG_AUTO_RELOAD', $config->get('twig', 'auto_reload'));
define('TWIG_STRICT_VARIABLES', $config->get('twig', 'strict_variables'));
define('TWIG_AUTOESCAPE', $config->get('twig', "autoescape"));

/**
 * -------------------------------------------------------------------
 * || Turn OFF! Register Globals ||
 * -------------------------------------------------------------------
 * 
 * When register_globals is on, the next program can access $_GET and $_POST variables direclty as global variables (highly insecure).
 */

 function unregisterGlobals()
 {
  if (ini_get('register_globals')) {

    $array = [
      '_SESSION',
      '_POST',
      '_GET',
      '_COOKIE',
      '_REQUEST',
      '_SERVER',
      '_ENV',
      '_FILES'
    ];

    foreach ($array as $value) {

      // GLOBALS prints all global variables
      foreach ($GLOBALS[$value] as $key => $var) {

        if ($var === $GLOBALS($key)) {

          unset($GLOBALS[$key]);
        }
      }
    }
  }
 }

 