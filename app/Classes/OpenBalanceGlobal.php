<?php

namespace App\Classes;

class OpenBalanceGlobal
{
  /**
  * Creates a new database schema.

  * @param  string $schemaName The new schema name.
  * @return bool
  */

  static public function convertType($type){
     if($type == "materials" || $type == "goods" || $type == "tools" || $type == "upfront_costs" || $type == "assets" || $type == "finished_product"){
         $type = 'stock';
    }else if($type == "suppliers" || $type == "customers" || $type == "employees" || $type == "others"){
          $type = 'object';
    }else{
          $type = $type;
    }
    return $type;
  }

  static public function convertSuppliesGoodsTypeFilter($type)
  {
   if($type == "materials"){
        $i = 1;
      }else if($type == "goods"){
        $i = 2;
      }else if($type == "tools"){
        $i = 3;
      }else if($type == "finished_product"){
        $i = 4;
      }else if($type == "upfront_costs"){
        $i = 6;
      }else if($type == "assets"){
        $i = 7;
      }else{
        $i = 0;
      } 
      return $i;
  }

  static public function convertObjectTypeFilter($type){
    if($type == "suppliers"){
        $i = 1;
      }else if($type == "customers"){
        $i = 2; 
      }else if($type == "employees"){ 
        $i = 3;
      } else if($type == "others"){
        $i = 4; 
      }else{
        $i = 0;
      }
      return $i;
  }

}
