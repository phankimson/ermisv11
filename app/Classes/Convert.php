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
    return ($format =='' || !$format) ? 0 : $format;
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

   static public function VoucherMasker2($data,$prefix,$char = "X"){
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
    $conditions = self::parseFilterConditions($filter);
    if($conditions === null || empty($conditions)){
      return null;
    }

    $sql = '';
    foreach($conditions as $index => $condition){
      $boolean = $index === 0 ? '' : (' ' . strtoupper($condition['boolean']) . ' ');
      $column = '`' . str_replace('.', '`.`', $condition['column']) . '`';
      $value = addslashes((string) $condition['value']);

      switch ($condition['operator']) {
        case 'eq':
          $segment = $column . ' = "' . $value . '"';
          break;
        case 'neq':
          $segment = $column . ' != "' . $value . '"';
          break;
        case 'lt':
          $segment = $column . ' < "' . $value . '"';
          break;
        case 'lte':
          $segment = $column . ' <= "' . $value . '"';
          break;
        case 'gt':
          $segment = $column . ' > "' . $value . '"';
          break;
        case 'gte':
          $segment = $column . ' >= "' . $value . '"';
          break;
        case 'startswith':
          $segment = $column . ' LIKE "' . $value . '%"';
          break;
        case 'endswith':
          $segment = $column . ' LIKE "%' . $value . '"';
          break;
        case 'substringof':
          $segment = $column . ' LIKE "%' . $value . '%"';
          break;
        case 'notsubstringof':
          $segment = $column . ' NOT LIKE "%' . $value . '%"';
          break;
        default:
          return null;
      }

      $sql .= $boolean . '(' . $segment . ')';
    }

    return $sql;
  }

  static public function parseFilterConditions($filter, array $allowedColumns = []): ?array
  {
    if(!$filter || !is_string($filter)){
      return null;
    }

    $filter = trim($filter);
    if($filter === ''){
      return null;
    }

    $parts = preg_split('/\s+(and|or)\s+/i', $filter, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    if(!$parts){
      return null;
    }

    $conditions = [];
    $pendingBoolean = 'and';

    foreach($parts as $part){
      $token = trim($part);
      if($token === ''){
        continue;
      }

      if(preg_match('/^(and|or)$/i', $token)){
        $pendingBoolean = strtolower($token);
        continue;
      }

      $parsed = self::parseFilterToken($token, $allowedColumns);
      if($parsed === null){
        return null;
      }

      $parsed['boolean'] = $pendingBoolean;
      $conditions[] = $parsed;
      $pendingBoolean = 'and';
    }

    return empty($conditions) ? null : $conditions;
  }

  static public function applyFilterConditions($query, ?array $conditions)
  {
    if(!$query || empty($conditions)){
      return $query;
    }

    foreach($conditions as $index => $condition){
      $boolean = $index === 0 ? 'and' : ($condition['boolean'] ?? 'and');
      $method = $boolean === 'or' ? 'orWhere' : 'where';
      $column = $condition['column'];
      $value = (string) $condition['value'];

      if(in_array($condition['operator'], ['startswith', 'endswith', 'substringof', 'notsubstringof'], true)){
        $pattern = $value;
        if($condition['operator'] === 'startswith'){
          $pattern = $value . '%';
          $query->{$method}($column, 'like', $pattern);
        }elseif($condition['operator'] === 'endswith'){
          $pattern = '%' . $value;
          $query->{$method}($column, 'like', $pattern);
        }elseif($condition['operator'] === 'substringof'){
          $pattern = '%' . $value . '%';
          $query->{$method}($column, 'like', $pattern);
        }else{
          $pattern = '%' . $value . '%';
          $query->{$method}($column, 'not like', $pattern);
        }
        continue;
      }

      $operators = [
        'eq' => '=',
        'neq' => '!=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
      ];

      $query->{$method}($column, $operators[$condition['operator']], $value);
    }

    return $query;
  }

  private static function parseFilterToken(string $token, array $allowedColumns = []): ?array
  {
    $token = trim($token);

    if(preg_match('/^substringof\(\s*([\'\"])(.*?)\1\s*,\s*([a-zA-Z0-9_\.]+)\s*\)\s*(?:eq\s+(true|false))?$/i', $token, $m)){
      $column = self::normalizeFilterColumn($m[3], $allowedColumns);
      if($column === null){
        return null;
      }

      $operator = isset($m[4]) && strtolower($m[4]) === 'false' ? 'notsubstringof' : 'substringof';
      return ['column' => $column, 'operator' => $operator, 'value' => $m[2]];
    }

    if(preg_match('/^(startswith|endswith)\(\s*([a-zA-Z0-9_\.]+)\s*,\s*([\'\"])(.*?)\3\s*\)$/i', $token, $m)){
      $column = self::normalizeFilterColumn($m[2], $allowedColumns);
      if($column === null){
        return null;
      }

      return ['column' => $column, 'operator' => strtolower($m[1]), 'value' => $m[4]];
    }

    if(preg_match('/^([a-zA-Z0-9_\.]+)\s+(eq|neq|lt|lte|gt|gte)\s+(.+)$/i', $token, $m)){
      $column = self::normalizeFilterColumn($m[1], $allowedColumns);
      if($column === null){
        return null;
      }

      return [
        'column' => $column,
        'operator' => strtolower($m[2]),
        'value' => self::normalizeFilterValue($m[3]),
      ];
    }

    return null;
  }

  private static function normalizeFilterColumn(string $column, array $allowedColumns = []): ?string
  {
    $column = trim($column);
    if(!preg_match('/^[a-zA-Z0-9_\.]+$/', $column)){
      return null;
    }

    if(!empty($allowedColumns) && !in_array($column, $allowedColumns, true)){
      return null;
    }

    return $column;
  }

  private static function normalizeFilterValue(string $value): string
  {
    $value = trim($value);
    if(preg_match('/^([\'\"])(.*)\\1$/', $value, $m)){
      return $m[2];
    }

    return $value;
  }
  static public function Array_convert_supplies_goods($data,$price){
      $rs_convert = collect([]);
      $co = collect(['id' =>  '0',
                'item_id' => '',
                'code' => '',
                'name' => "--Select--",
                'name_en' => "--Select--",
                'unit_id' => '',
                'stock' => '',
                'unit' => '',
                'unit_en' => '',
                'quantity' => '',
                'price' => '',
                'debit' => '',
                'credit' => '',
              ]);
      $rs_convert->push($co);
      $data->each(function ($item, int $key) use($rs_convert,$price){
          $item->stock_check->each(function ($it, int $k) use($item,$rs_convert,$price){
          if($price == "NK"){
            $item_price = $item->price_purchase;
            $debit = $item->stock_account;
            $credit = '';
          }else if($price == "XK"){
            $item_price = $item->price;
            $debit = $item->cost_account;
            $credit =  $it->type;
          }else if($price == "CK"){
            $item_price = $item->price_purchase;
            $debit = '';
            $credit = $it->type;
          }else{
            $item_price = 0;
            $debit = '';
            $credit = '';
          }
          $co = collect(['id' =>  $it->id,
                'item_id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'name_en' => $item->name_en,
                'unit_id' => $item->unit_id,
                'stock' => $it->stock,
                'unit' => optional($item->unit)->name?optional($item->unit)->name:'',
                'unit_en' => optional($item->unit)->name_en?optional($item->unit)->name_en:'',
                'quantity' => $it->quantity,
                'price' => $item_price,
                'debit' => $debit,
                'credit' => $credit,
              ]);
         $rs_convert->push($co);
        });         
      });
      return $rs_convert;
    }
}
