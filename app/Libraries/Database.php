<?php

namespace Libraries;

use Config\Configuration;
use PDO;
use PDOException;

/**
 * ----------------------------------------------------------
 * || PDO Database Class ||
 * ----------------------------------------------------------
 */

class Database 
{
  private $dbChar = $config->get('mariadb', 'charset');
  private $dbCollation = $config->get('mariadb', 'collation');
  private $dbName = $config->get('mariadb', 'database');
  private $dbDriver = $config->get('mariadb', 'driver');
  private $dbHost = $config->get('mariadb', 'hostname');
  private $dbPass =  $config->get('mariadb', 'password');
  private $dbPort = $config->get('mariadb', 'port');
  private $dbUser = $config->get('mariadb', 'AgBRAT');

  // Database handler, we use it whenever we prepare SQL statements
  private $db;
  private $stmt;
  private $err;

  /**
   * Try to connect to database using PDO Socket Connection. If fails, an error exception message will be displayed.
   */
  public function __construct()
  {
    // Set DSN
    $dsn = 'mysql:unix_socket=/tmp/mysql.sock;host=' . $this->dbHost . ';dbname=' . $this->dbName . ';charset=' . $this->dbChar . ';port=' . $this->dbPort . ';collation=' . $this->dbCollation . ';driver=' .$this->dbDriver;

    // Options alongside dsn, not necessary but good for enhancement
    $options = [
      PDO::ATTR_PERSISTENT => true, //persist db connection 
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //handles errors, allowing catch errors easily
      PDO::ATTR_EMULATE_PREPARES => false, //disable emulation for older MySQL compatibility, for injection prevention
    ];

    // create PDO instance
    try {
      $this->dbh = new PDO($dsn, $this->dbUser, $this->dbPass, $options);
    
    } catch (PDOException $e) {

      $this->err = $e->getMessage();
      echo $this->err;
    }
  }

  /**
   * Prepared statment
   *
   * @param string $sql
   * @return void
   */
  public function query(string $sql) 
  {
    $this->stmt = $this->dbh->prepare($sql);
  }

  /**
   * Binds the parameters to the values
   *
   * @param string $param
   * @param string $value
   * @param mixed $type
   * @return void
   */
  public function bind(string $param, string $value, mixed $type = null)
  {
    if (is_null($type)) {

      switch (true) {

        case is_int($value): 

          $type = PDO::PARAM_INT;
          break;

        case is_bool($value):

          $type = PDO::PARAM_BOOL;
          break;

        case is_null($value):

          $type = PDO::PARAM_NULL;
          break;

        default: 

          $type = PDO::PARAM_STR;
      }
    }

    // This function actually binds the values
    $this->stmt->bindValue($param, $value, $type);
  }

  /**
   * Execute the prepared statment
   *
   * @return string stmt
   */
  public function execute()
  {
    return $this->stmt->execute();
  }

  /**
   * Get result set as array of objects if more than one row
   *
   * @return array of objects - executed stmt
   */
  public function resultSet()
  {
    $this->execute();

    return $this->stmt->fetchAll(PDO::FETCH_OBJ);
  }

  /**
   * Get single record as object for single row
   *
   * @return object executed stmt
   */
  public function single()
  {
    $this->execute();

    return $this->stmt->fetch(PDO::FETCH_OBJ);
  }

  /**
   * Get row count
   *
   * @return int count of rows
   */
  public function rowCount()
  {
    return $this->stmt->rowCount();
  }
}


