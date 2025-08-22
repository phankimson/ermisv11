<?php

namespace App\Classes;
use Carbon\Carbon;

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

  static public function DateExcel($dateValue) 
  {
    $unixDate = ($dateValue - 25569) * 86400;
    return gmdate("Y-m-d", $unixDate); 
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
       $voucher = $data->prefix .$data->middle. str_repeat($char,$number - strlen($data->number."")) . $data->number . $data->suffixed;
     }else {
       $voucher = str_repeat($char,$number);
    }
    return $voucher;
  }
  
  static public function VoucherMasker1($data,$prefix){
    $char = "0";
    $voucher = "";  
    $number = $data->length_number;
    $replace_crt = array("DD", "MM", "YYYY","X");
    $replace_str = array($data->day, $data->month, $data->year,str_repeat($char,$number - strlen($data->number."")) . $data->number);
    $voucher = $prefix.str_replace($replace_crt,$replace_str,$data->format);
    return $voucher;
  }

  static public function dateformatArr($format,$date)
  {
    $obj = array();
    $obj['day_format'] = strpos($format, "DD")!== false ? Carbon::parse($date)->format('d') : '';            
    $obj['month_format']  = strpos($format, "MM")!== false ? Carbon::parse($date)->format('m') : '';            
    $obj['year_format'] = (strpos($format, "YYYY")!== false ? Carbon::parse($date)->format('Y') : (strpos($format, "YY")!== false ? Carbon::parse($date)->format('y') : ''));  
    return $obj;
  }

  static public function dateformatRange($format,$obj_date)
  {  
    $obj = array();
    $date_val = $obj_date->year.'-'.$obj_date->month.'-'.$obj_date->day;
    if(strpos($format, "DD")!== false){
      $obj['start_date'] = $date_val;
      $obj['end_date'] = $date_val;
    }else if(strpos($format, "MM")!== false){
      $obj['start_date'] = Carbon::parse($date_val)->format('Y-m-01');
      $obj['end_date'] = Carbon::parse($date_val)->format('Y-m-t');
    }else if(strpos($format, "YYYY")!== false || strpos($format, "YY")!== false){
      $obj['start_date'] = Carbon::parse($date_val)->format('Y-01-01');
      $obj['end_date'] = Carbon::parse($date_val)->format('Y-12-t');
    }else{
      
    }
    return $obj;    
  }

  static public function filterRow($filter){
    if(!$filter) return;
    $mappings = array(
      "eq"=>"=",
      "neq"=>"!=",
      "lt"=>'{0} < "{1}"',
      "lte"=>'{0} <= "{1}"',
      "gt"=>'{0} > "{1}"',
      "gte"=>'{0} >= "{1}"',
      "startswith"=>'({0} LIKE "{1}%")',
      "endswith"=>'({0} LIKE "%{1}")',
      "substringof"=>'({1} LIKE "%{0}%")',
      "notsubstringof"=>'({1} NOT LIKE "%{0}%")',
    );
    	// Remove all of the ' characters from the string.
		$filter = str_replace("'", '"',$filter);
    // Tìm notsubstringof
    $pattern = '/substringof\([^()]*\)\seq\sfalse/';
    $text_match = "";
    if (preg_match($pattern, $filter, $match)){
        if(preg_match('/\([^()]*\)/', $match[0], $match1)){
          $text_match = $match1[0];
        }      
    }
    $filter = preg_replace('/substringof\([^()]*\)\seq\sfalse/', 'notsubstringof'.$text_match, $filter);
		$arr = explode(' ', $filter);
    $array_mapping = array();
    foreach($mappings as $k=>$v){
      if(str_contains($filter, $k)){
        $array_mapping[$k] = $v;
      }
    }   
    foreach($arr as $k=>$key){
      if(array_key_exists($key,$mappings)){
          $arr[$k] = $mappings[$key];
      }else{
        // Tách chuỗi
          foreach($array_mapping as $l=>$a){
            if(str_contains($key, $l)){
              $key = str_replace('"', '',$key);
              $arr_con = explode(',', $key);            
              $arr_cont = explode($l.'(', $arr_con[0]);              
              $arr_con[1] = str_replace(")", "", $arr_con[1]);
              $val  =  $a;
              $val = str_replace('{1}', $arr_con[1], $val);
              $val = str_replace('{0}', $arr_cont[1], $val);
              $arr[$k] = $val;
            }
          }     
        }
      }   
          return join(" ",$arr);
    }  
}
