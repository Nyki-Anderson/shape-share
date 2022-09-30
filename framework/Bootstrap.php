<?php

use Config\Configuration;
use Core\Core;
use DependencyInjection\Container;

class Bootstrap 
{
  public static function run(string $environment)
  {
    if (! session_id()) {
      @session_start();
    }

    self::init($environment);
    self::configureApp();
    self::unregisterGlobals();

    $container = new Container();
    $core = new Core($container);
  }

  /**
   * -------------------------------------------------------
   * Initialization
   * --------------------------------------------------------
   */
  private static function init(string $environment)
  {
    // Define path constants
    mb_internal_encoding('UTF-8'); // String Encoding
    define('APP_PATH', ROOT . 'app' . DS);
    define('FCPATH', ROOT . 'public_html' . DS);
    define('VENDOR_PATH', ROOT . 'vendor' . DS);
    define('LOG_PATH', ROOT . 'tmp' . DS . 'Logs' . DS);
    define('TEST_PATH', ROOT . 'tests' . DS);
    define('FRAMEWORK_PATH', ROOT . 'framework' . DS);
    define('CORE_PATH', FRAMEWORK_PATH . 'Core' . DS);
    define('HELPER_PATH', APP_PATH . 'Helpers' . DS);
    define('LIB_PATH', APP_PATH . 'Libraries' . DS);
    define('VIEW_PATH', ROOT . DS . 'app' . DS . 'Views' . DS . DS);
    define('CONFIG_PATH', FRAMEWORK_PATH . 'Config' . DS);
    define('CONTROLLER_PATH', APP_PATH . 'Controllers' . DS);
    define('MODEL_PATH', APP_PATH . 'Models' . DS);
    define('ENTITY_PATH', APP_PATH . 'Entities' . DS);
    define('URL_ROOT', 'https' . '://' . $_SERVER['HTTP_HOST'] . DS);
    define('SITE_NAME', 'local.shape-share');
    define('CSS_PATH', URL_ROOT . 'assets' . DS . 'css' . DS);
    define('JS_PATH', URL_ROOT . 'assets' . DS . 'js' . DS);
    define('IMG_PATH', URL_ROOT . 'assets' . DS . 'img' . DS);
    define('INI_FILEPATH', ROOT);
    define('ENVIRONMENT', $environment);
  }

  /**
   * -------------------------------------------------------------------
   *  || Instantiate Configuration Class ||
   * ------------------------------------------------------------------ 
   * 
   * Configuation file can take 1 of 3 flavors:
   *    - Development ('DEV') : Full Database with Debugging, Logging, Errors, PHP *     settings, security, etc
   *    - Testing ('TEST') : Full Database with Fake Values, Debugging, Logging, * *     Errors, etc
   *    - Production ('PROD') : Full Database, Logging, No Debugging, Vague * Error Messages
   */

   private static function configureApp()
   {
    $config = Configuration::getInstance(ENVIRONMENT);

    define('LANG_VAR', $config->get('app', 'language'));
    define('CHARSET_VAR', $config->get('app', 'site_charset'));

    $config->setIni();
    $config->configTwig();
    $config->reCaptchaKeys();
   }


  /**
   * -------------------------------------------------------------------
   * || Turn OFF! Register Globals ||
   * -------------------------------------------------------------------
   * 
   * When register_globals is on, the next program can access $_GET and $_POST variables direclty as global variables (highly insecure).
   */

  public static function unregisterGlobals()
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
}