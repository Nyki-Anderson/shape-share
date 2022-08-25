<?php

class Bootstrap 
{
  public static function run()
  {
    self::init();
    self::autoload();
    self::configureApp();
    self::unregisterGlobals();
  }

  /**
   * -------------------------------------------------------
   * Initialization
   * --------------------------------------------------------
   */
  private static function init()
  {
    // Define path constants
    mb_internal_encoding('UTF-8'); // String Encoding
    define('APP_PATH', ROOT . 'app' . DS);
    define('FCPATH', ROOT . 'public_html' . DS);
    define('VENDOR_PATH', ROOT . 'vendor' . DS);
    define('WRITE_PATH', ROOT . 'writable' . DS);
    define('TEST_PATH', ROOT . 'tests' . DS);
    define('FRAMEWORK_PATH', ROOT . 'framework' . DS);
    define('CORE_PATH', FRAMEWORK_PATH . 'Core' . DS);
    define('DB_PATH', FRAMEWORK_PATH . 'Database' . DS);
    define('HELPER_PATH', APP_PATH . 'Helpers' . DS);
    define('LIB_PATH', APP_PATH . 'Libraries' . DS);
    define('VIEW_PATH', ROOT . DS . 'app' . DS . 'Views' . DS . DS);
    define('CONFIG_PATH', APP_PATH . 'Config' . DS);
    define('CONTROLLER_PATH', APP_PATH . 'Controllers' . DS);
    define('MODEL_PATH', APP_PATH . 'Models' . DS);
    define('ENTITY_PATH', APP_PATH . 'Entities' . DS);
    define('URL_ROOT', 'https' . '://' . $_SERVER['HTTP_HOST'] . DS);
    define('SITE_NAME', 'local.shape-share');
    define('CSS_PATH', URL_ROOT . 'assets' . DS . 'css' . DS);
    define('JS_PATH', URL_ROOT . 'assets' . DS . 'js' . DS);
    define('IMG_PATH', URL_ROOT . 'assets' . DS . 'img' . DS);
    define('INI_FILEPATH', ROOT . '.user.ini');

    // Start Session
    session_start();
  }

  /**
   * -----------------------------------------------------------
   * Autoloading
   * -----------------------------------------------------------
   */
  private static function autoload()
  {
    spl_autoload_register(array(__CLASS__, 'load'));
  }

  /**
   * ------------------------------------------------------------
   * Load Classes 
   * ------------------------------------------------------------
   * 
   * Name convention: 
   *  xxxController.class.php
   *  xxxModel.class.php
   * 
   * @param string $classname 
   */
  private static function load(string $classname = 'IndexController')
  {
    if (substr($classname, -10) == "Controller")
    {
      require_once CONTROLLER_PATH . "$classname.class.php";
    
    } elseif (substr($classname, -5) == "Model")
    {
      require_once MODEL_PATH . "$classname.class.php";
    }
  }

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

   private static function configureApp()
   {
    require(CONFIG_PATH . 'Configuration.php');

    $config = app\Config\Configuration::getInstance('DEV');

    define('ENVIRONMENT_VAR', $config->get('app', 'environment'));
    define('LANG_VAR', $config->get('app', 'language'));
    define('CHARSET_VAR', $config->get('app', 'site_charset'));

    $config->setIni();
    $config->configTwig();
    $config->configDatabase();
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