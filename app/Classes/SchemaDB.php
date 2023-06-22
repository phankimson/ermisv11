<?php

namespace App\Classes;
use DB;
use Config;

class SchemaDB
{
  /**
  * Creates a new database schema.

  * @param  string $schemaName The new schema name.
  * @return bool
  */
  public static function setConnection($params)
    {
        config(['database.connections.'.$params['database'] => [
            'driver' => $params['driver'],
            'host' => $params['host'],
            'database' => $params['database'],
            'username' => $params['username'],
            'password' => $params['password'],
            'charset' => $params['charset'],
            'collation' => $params['collation'],
            'prefix' => $params['prefix'],
        ]]);

        return DB::connection($params['database']);
    }

  static public function connectDB($DB)
  {
    // We will use the `statement` method from the connection class so that
    // we have access to parameter binding.
    return DB::connection($DB);
  }

  static public function checkDB($DB)
  {
    $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?";
      $db = DB::select($query, [$DB]);
      if (empty($db)) {
          return 0;
      } else {
          return 1;
      }
  }

  static public function createDB($DB)
  {
    // We will use the `statement` method from the connection class so that
    // we have access to parameter binding.
    return SchemaDB::connectDB('mysql')->statement("CREATE DATABASE IF NOT EXISTS $DB DEFAULT CHARACTER SET utf8");
  }
  static public function copyTableDB($DB_old,$DB_new,$table)
  {
    // We will use the `statement` method from the connection class so that
    // we have access to parameter binding.
    return SchemaDB::connectDB('mysql')->statement("CREATE TABLE $DB_new.$table SELECT * FROM $DB_old.$table");
  }

  static public function getAllTable($db){
    return SchemaDB::connectDB('mysql')->select('SHOW TABLES FROM '.$db);
  }

}
