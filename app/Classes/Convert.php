<?php

namespace App\Classes;


class Convert
{
  /**
  * Creates a new database schema.

  * @param  string $schemaName The new schema name.
  * @return bool
  */

  static public function dateDefaultformat($date,$format)
  {
    $d = strpos($date,"/");
    if($d>0){
      $origDate = str_replace('/', '-', $date );
      return date($format, strtotime($origDate));
    }else{
      return date($format, strtotime($d));
    }
  }

  static public function intDefaultformat($format)
  {
    return $format ==''? 0 : $format;
  }

  static public function intDefaultNumberformat($format,$number)
  {
    return $format ==''? $number : $format;
  }

  static public function StringDefaultformat($format)
  {
    return $format ==''? "" : $format;
  }

  static public function StringDefaultformatNull($format)
  {
    return ($format == '' || $format == 0 || $format == '0') ? null : $format;
  }

  static public function dateDefaultNull($value)
  {
    return $value == '' ? null : $value;
  }

  static public function VoucherMasker($data){
    $char = "0";
    $voucher = "";
    $number = $data->length_number;
     if ($data->number) {
       return $voucher = $data->prefix . str_repeat($char,$number - strlen($data->number."")) . $data->number . $data->suffixed;
     }else {
       return $voucher = str_repeat($char,$number);
    }
  }

}
