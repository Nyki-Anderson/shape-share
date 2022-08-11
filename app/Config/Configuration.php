<?php

namespace Config;

/**
 * Shape-Share Configuration Wrapper Class

 * Resource: https://stackoverflow.com/a/34146580/17345694

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

  public static function getInstance(string $environment)
  {
    if (self::$_instance == null) {

      self::$_instance = new Self;

      switch ($environment) {

        case 'DEV':
          self::$config = json_decode(file_get_contents(CONFIG_DIR . 'environment' . DS . 'Development.json'), true);
          break;

        case 'PROD':
          self::$config = json_decode(file_get_contents(CONFIG_DIR . 'environment' . DS . 'Production.json'), true);
          break;

        case 'TEST':
          self::$config = json_decode(file_get_contents(CONFIG_DIR . 'environment' . DS . 'Testing.json'), true);
          break;

        default:
        self::$config = json_decode(file_get_contents(CONFIG_DIR . 'environment' . DS . 'Default.json'), true);
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
}