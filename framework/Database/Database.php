<?php
declare(strict_types=1);

namespace Database;

use Config\Configuration;

class Database implements IDatabase
{
  private static $dataBase = null;

  public static function getInstance()
  {
    if (self::$dataBase = null) {

      try {

        self::$dataBase = new \PDO(
          "mysql:host=" . Configuration::get('mariadb', 'hostname') . ";dbname=" . Configuration::get('mariadb', 'database') . ";charset=" . Configuration::get('mariadb', 'charset') . ";port=" . Configuration::get('mariadb', 'port') . ";driver=" . Configuration::get('mariadb', 'driver') . ";collation=" . Configuration::get('mariadb', 'collation'), Configuration::get('mariadb', 'username'), Configuration::get('mariadb', 'password')
        );

        self::$dataBase->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      
      } catch (\PDOException $e) {

        print "Database error: " . $e->getMessage() . "<br/>";
      }
    }
    return self::$dataBase;
  }
}