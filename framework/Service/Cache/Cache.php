<?php
declare(strict_types=1);

namespace framework\Service\Cache;

use framework\Database\Orm\Orm;

class Cache extends Orm
{
  private $tableName = "caches";

  function __construct()
  {
    parent::__construct($this->tableName);
  }

  public function getCache($cacheKey)
  {
    $sql = 'SELECT * FROM ' . $this->tableName . 'WHERE cache_key=:cacheKey;';

    $params = [':cacheKey' => $cacheKey];

    $results = $this->execute($sql, $params);

    $cache = false;

    if (! empty($results) && is_array($results)) {

      $cache = array_pop($results);
    }

    if ($cache && isset($cache->value)) {
      $now = strtotime('now');

      if ($cache->expire > 0) {

        if ($now > $cache->expire) {

          $this->dropByKey($cacheKey);
          return false;
        }
      }
      return unserialize($cache->value);
    }
    return false;
  }

  public function setCache($cacheKey, $value, $expire = 0)
  {
    $now = strtotime('now');

    if ($expire > 0) {
      $expire = $now + $expire;
    
    } else {
      $expire = 0;
    }

    $this->dropByKey($cacheKey);

    $sql = 'INSERT INTO ' . $this->tableName . '(cache_key, `value`, expire VALUES (:cacheKey, :cacheValue, :expire);';

    $params = [
      ':cacheKey'   => $cacheKey,
      ':cacheValue' => serialize($value),
      ':expire'     => $expire,
    ];

    return $this->execute($sql, $params);
  }

  public function dropAllCache()
  {
    $sql = 'DELETE FROM ' . $this->tableName . ' WHERE 1;';

    return $this->execute($sql);
  }

  public function dropByKey($cacheKey)
  {
    $sql = 'DELETE FROM ' . $this->tableName . 'WHERE cache_key = :cacheKey;';

    $params = [':cacheKey' => $cacheKey];

    return $this->execute($sql, $params);
  }
}