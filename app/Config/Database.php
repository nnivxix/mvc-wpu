<?php

namespace Hanasa\MVC\Config;

class Database
{
  private static ?\PDO $pdo = null;
  public static function getConnection(string $env = "test"): \PDO
  {
    if (self::$pdo == null) {
      // create new PDO

      // import
      require_once __DIR__ . '/../../config/database.php';

      $config = getDatabaseConfig();
      self::$pdo = new \PDO(
        $config['database'][$env]['url'],
        $config['database'][$env]['username'],
        $config['database'][$env]['password'],
      );
    }
    return self::$pdo;
  }
}