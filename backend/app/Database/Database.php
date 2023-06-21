<?php
namespace App\Database;

use PDOException;
use \PDO;

class Database {
   private static $dbHost = 'localhost';
   private static $dbName = 'tasks';
   private static $dbUser = 'root';
   private static $dbPass = '';

   private static $dbConnection = null;
   private static $dbStatement = null;

   private static function connect(): bool
   {
      try {
         self::$dbConnection = new PDO(
            "mysql:host=" . self::$dbHost . ";dbname=" . self::$dbName,
            self::$dbUser,
            self::$dbPass
         );
      } catch(PDOException $error) {
         return false;
      }

      return true;
   }

   public static function query($sql, $placeholders = []): bool
   {
      if(is_null(self::$dbConnection))
         if(!self::connect())
            return false;

      self::$dbStatement = self::$dbConnection->prepare($sql);
      self::$dbStatement->execute($placeholders);

      return true;
   }

   public static function get(): array
   {
      if (is_null(self::$dbConnection))
         if (!self::connect())
            return [];

      return self::$dbStatement->fetch(PDO::FETCH_ASSOC) ?? [];
   }

   public static function getAll(): array
   {
      if (is_null(self::$dbConnection))
         if (!self::connect())
            return [];

      return self::$dbStatement->fetchAll(PDO::FETCH_ASSOC) ?? [];
   }

   public static function lastId(): int
   {
      return self::$dbConnection->lastInsertId() ?? 0;
   }
}