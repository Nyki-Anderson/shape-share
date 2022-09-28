<?php

namespace Config;

/**
 * Shape-Share Configuration Wrapper Class
 * 
 * Meant to allow configuration on different server environments ie:
 *    - Development
 *    - Production
 *    - Testing
 * 
 * @param array static $config - holds an instance of the private configuration class
 * @param string $env - holds an array of the configuration variables
 */
class Configuration
{
  private static $_instance = null;
  private static $config;
  private $directiveValue;
  private static $fileConfig = '.user.ini';

  public static function getInstance(string $environment)
  {
    if (self::$_instance == null) {

      self::$_instance = new Self;

      switch ($environment) {

        case 'dev':
          self::$config = json_decode(file_get_contents(FRAMEWORK_PATH . 'Config' . DS . 'environment' . DS . 'Development.json'), true);
          break;

        case 'prod':
          self::$config = json_decode(file_get_contents(FRAMEWORK_PATH . 'Config' . DS . 'environment' . DS . 'Production.json'), true);
          break;

        case 'test':
          self::$config = json_decode(file_get_contents(FRAMEWORK_PATH . 'Config' . DS . 'environment' . DS . 'Testing.json'), true);
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
    $iniFilepath = INI_FILEPATH . self::$fileConfig;

    if (! file_exists($iniFilepath)) {

      throw new \Exception('Create user.ini file - copy from config.ini.sample!');
    }

    try {

      foreach ($phpConfig as $section => $values) {

        $content .= "[" . $section . "]\n";
  
        foreach ($values as $key => $value) {
  
          $content .= $key . "=" . $value . "\n";
        }
      }

      //write it into file
      if (!$handle = fopen($iniFilepath, 'w')) { 
        
        return false; 
      }
    
      fwrite($handle, $content);
      fclose($handle); 
   
    } catch (\Exception $e) {

      throw new \Exception($e->getMessage());
    }
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

  public function reCaptchaKeys()
  {
    define('RECAPTCHAV2_sitekey', '6Ldy92QgAAAAAESJBMUaktUGRiUTFJZwcO8eJE6U');
    define('RECAPTCHAV2_secretkey', '6Ldy92QgAAAAAA2-4KX9eNrg8bD7IcKiOZw0CQqX');
  }
}