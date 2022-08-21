<?php

namespace app\Config;

/**
 * Shape-Share Configuration Wrapper Class
 * 
 * Meant to allow configuration on different server environments ie:
 *    - Development
 *    - Production
 *    - Testing
 *    - Default (limited configuration)
 * 
 * @param array static $config - holds an instance of the private configuration class
 * @param string $env - holds an array of the configuration variables
 */
class Configuration
{
  private static $_instance = null;
  private static $config;
  private $directiveValue;
  private $loader;

  public static function getInstance(string $environment)
  {
    if (self::$_instance == null) {

      self::$_instance = new Self;

      switch ($environment) {

        case 'DEV':
          self::$config = json_decode(file_get_contents('..' . DS .'app' . DS . 'Config' . DS . 'environment' . DS . 'Development.json'), true);
          break;

        case 'PROD':
          self::$config = json_decode(file_get_contents('..' . DS .'app' . DS . 'Config' . DS . 'environment' . DS . 'Production.json'), true);
          break;

        case 'TEST':
          self::$config = json_decode(file_get_contents('..' . DS .'app' . DS . 'Config' . DS . 'environment' . DS . 'Testing.json'), true);
          break;

        default:
        self::$config = json_decode(file_get_contents('..' . DS .'app' . DS . 'Config' . DS . 'environment' . DS . 'Default.json'), true);
          break;
      }
    }

    return self::$_instance;
  }

  public function get(string $directive, string $data)
  {
    $this->directiveValue = self::$config[$directive][$data];
    
    return $this->directiveValue;
  }

  /**
   * -------------------------------------------------------------------
   * Write Configuration Data to .user.ini File (.htaccess)
   * -------------------------------------------------------------------
   */
  public function setIni()
  {
    $phpConfig = [
      'php' => [
        'display_errors' => $this->get('php', 'display_errors'),
        'display_startup_errors' => $this->get('php', 'display_startup_errors'),
        'error_log' => $this->get('php', 'error_log'),  
        'error_reporting' => $this->get('php', 'error_reporting'),
        'log_errors' => $this->get('php', 'log_errors'), 
        'ignore_repeated_errors' => $this->get('php', 'ignore_repeated_errors'), 
        'ignore_repeated_source' => $this->get('php', 'ignore_repeated_source'),
        'report_memleaks' => $this->get('php', 'report_memleaks'),
        'doc_root' => $this->get('php', 'doc_root'),
        'file_uploads' => $this->get('php', 'file_uploads'),
        'upload_max_filesize' => $this->get('php', 'upload_max_filesize'),
        'max_file_uploads' => $this->get('php', 'max_file_uploads'),
      ],
    ];

    $content = "";  
    $iniFilepath = INI_FILEPATH;

    $parseINI = parse_ini_file($iniFilepath, true);
    
    foreach ($phpConfig as $section => $values) {

      $content .= "[" . $section . "]\n";

      foreach ($values as $key => $value) {

        $content .= $key . "=" . $value . "\n";
      }
    }

    if (! $handle = fopen($iniFilepath, "w+")) {

      return false;
    }

    $success = fwrite($handle, $content);
    fclose($handle);

    return $success;
  }

  /**
   * -------------------------------------------------------------------
   * Configure and Extend Twig Template Engine 
   * -------------------------------------------------------------------
   */
  public function configTwig()
  {
    define('TWIG_DEBUG', $this->get('twig', 'debug'));
    define('TWIG_CACHE', $this->get('twig', 'cache'));
    define('TWIG_AUTO_RELOAD', $this->get('twig', 'auto_reload'));
    define('TWIG_STRICT_VARIABLES', $this->get('twig', 'strict_variables'));
    define('TWIG_AUTOESCAPE', $this->get('twig', "autoescape"));
  }

  /**
   * ---------------------------------------------------------------------
   * Configure Database Variables
   * ---------------------------------------------------------------------
   */
  public function configDatabase()
  {
    define('DB_HOST', $this->get('mariadb', 'hostname'));
    define('DB_NAME', $this->get('mariadb', 'database'));
    define('DB_USER', $this->get('mariadb', 'username'));
    define('DB_PASS', $this->get('mariadb', 'password'));
    define('DB_PORT', $this->get('mariadb', 'port'));
    define('DB_DRIVER', $this->get('mariadb', 'driver'));
    define('DB_CHARSET', $this->get('mariadb', 'charset'));
    define('DB_COLLATION', $this->get('mariadb', 'collation'));
  }

  public function reCaptchaKeys()
  {
    define('RECAPTCHAV2_sitekey', '6Ldy92QgAAAAAESJBMUaktUGRiUTFJZwcO8eJE6U');
    define('RECAPTCHAV2_secretkey', '6Ldy92QgAAAAAA2-4KX9eNrg8bD7IcKiOZw0CQqX');
  }
}