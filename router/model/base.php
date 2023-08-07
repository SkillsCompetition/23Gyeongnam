<?php
  class DB{
    public static $pdo = false;

    public static function mq($sql, $arr = []){
      if(!self::$pdo){
        self::$pdo = new PDO("mysql:host=localhost;charset=utf8;dbname=23gyeongbuk", "root", "", [
          19 => 2,
          3 => 2
        ]);
      }

      $q = self::$pdo->prepare($sql);
      $q->execute(is_array($arr) ? $arr : [$arr]);

      return $q;
    }

    public static function all($sql = "", $arr = []){
      $table = get_called_class();
      return self::mq("SELECT * FROM $table $sql", $arr)->fetchAll();
    }

    public static function find($sql, $arr = []){
      $table = get_called_class();
      return self::mq("SELECT * FROM $table WHERE $sql", $arr)->fetch();
    }

    public static function findAll($sql, $arr = []){
      $table = get_called_class();
      return self::mq("SELECT * FROM $table WHERE $sql", $arr)->fetchAll();
    }

    public static function insert($data){
      $table = get_called_class();

      $sql = implode(" = ?, ", array_keys($data))." = ?";

      self::mq("INSERT INTO $table SET $sql", array_values($data));

      return self::$pdo->lastinsertid();
    }

    public static function update($update, $arr, $data){
      $table = get_called_class();

      $sql = implode(" = ?, ", array_keys($data))." = ?";

      self::mq("UPDATE $table SET $sql WHERE $update", [...array_values($data), $arr]);
    }

    public static function delete($sql, $arr = []){
      $table = get_called_class();
      self::mq("DELETE FROM $table WHERE $sql", $arr);
    }

  }

  class attraction extends DB {}
  class review extends DB {}
  class record extends DB {}
?>