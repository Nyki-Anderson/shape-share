<?php

namespace Config;

/**
 * WebApp Configuration Wrapper Class

 * Resource: https://stackoverflow.com/a/34146580/17345694

 * Meant to allow configuration on different server environments ie:
 *    - Development
 *    - Production
 *    - Testing
 *    - Default (limited configuration)
 */
class Configuration
{
  // @var string environment - Either 'DEV', 'PROD', 'TEST', or 'DEFAULT'
  protected static $_instance = null;

  // @var array()
  private static $config;

  /**
   * Configuration constructor, tells server which environment config file to require_once
   */
  /**
   * Returns the environment instance.

   * @static
   * @return _instance
   */
  public static function getInstance(string $environment = 'DEFAULT')
  {
    self::getConfiguration($environment);

    if (self::$_instance == null) {

      self::$_instance = new Self;
    }

    return self::$_instance;
  }

  private function getConfiguration(string $environment = "DEFAULT")
  {
    switch ($environment) {

      case 'DEV':
        self::$config = require_once(FCPATH . '../app/Config/environment/Development.json');
        break;

      case 'PROD':
        self::$config = require_once(FCPATH . '../app/Config/environment/Production.json');
        break;

      case 'TEST':
        self::$config = require_once(FCPATH . '../app/Config/environment/Testing.json');
        break;

      default:
      self::$config = require_once(FCPATH . '../app/Config/environment/Default.json');
        break;
    }
  }

  public function get($directive)
  {
    if (isset($directive)) {

      $directive = explode('.', $directive);
      $config = $this->config;

      foreach ($directive as $key) {

        if (isset($config[$key])) {

          $config = $config[$key];
        }
      }

      return $config;
    }
  }

  public function __destruct()
  {
    self::$_instance = 'DEFAULT';
  }
}