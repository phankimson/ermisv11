<?php

namespace App\Classes;


class Replace
{
  /**
  * Creates a new database schema.

  * @param  string $schemaName The new schema name.
  * @return bool
  */

  static public function Array( $format, array $data)
  {
      preg_match_all( '{[a-zA-Z]*}', $format, $match, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
      $offset = 0;
      $keys = array_keys($data);
      foreach ( $match as &$value )
      {
          if ( ( $key = array_search( $value[1][0], $keys) ) !== FALSE || ( is_numeric( $value[1][0]) && ( $key = array_search( (int)$value[1][0], $keys) ) !== FALSE ) ) {
              $len = strlen( $value[1][0]);
              $format = substr_replace( $format, 1 + $key, $offset + $value[1][1], $len);
              $offset -= $len - strlen( $key);
          }
      }
      return ReplaceArray( $format, $data);
  }


}
