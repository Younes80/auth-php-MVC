<?php

namespace Models;

use PDO;

require_once './.env';

class Database
{

  private static $pdo;

  private static function setBdd()
  {

    // host -> si c'est en local (sur la machine) c'est localhost
    // dbname -> c'est le nom de votre base de données
    $dns = 'mysql:host=localhost;dbname=' . $_ENV['DB_NAME'];
    // Nom d'utilisateur (en général en local, c'est root)
    $user = $_ENV['DB_USER'];
    // votre mot de passe
    $pwd = $_ENV['DB_PWD'];

    self::$pdo = new PDO($dns, $user, $pwd, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
    ]);
    self::$pdo->exec("SET CHARACTER SET utf8");
  }

  protected function getBdd()
  {
    if (self::$pdo === null) {
      self::setBdd();
    }
    return self::$pdo;
  }
}
