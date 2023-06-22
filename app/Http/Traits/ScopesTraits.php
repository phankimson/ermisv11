<?php
namespace App\Http\Traits;
use DB;

trait ScopesTraits
{
      public function scopeWithRowNumber($query, $column = 'created_at', $order = 'asc')
    {              
        //DB::statement(DB::raw('set @i=0')); version laravel 9 under
        DB::statement(DB::raw('set @i=0')->getValue(DB::connection()->getQueryGrammar()));// version laravel 10
        
        $sub = static::selectRaw('*, @i:=@i+1 as row_number')
            ->orderBy($column, $order)->toSql();
        
        $query->from(DB::raw("({$sub}) as t"));
    
    }

    public function scopeWithRowNumberWhereColumn($query,$column_where, $value_where ,$column = 'created_at', $order = 'asc')
  {
      DB::statement(DB::raw('set @i=0')->getValue(DB::connection()->getQueryGrammar()));

      $sub = static::selectRaw('*, @i:=@i+1 as row_number')
          ->where($column_where, $value_where)
          ->orderBy($column, $order);

      $query->from(DB::raw("({$sub->toSql()}) as t"))
            ->mergeBindings($sub->getQuery()) ;
  }

  public function scopeWithRowNumberDb($query,$db ,$column = 'created_at', $order = 'asc')
  {
      DB::connection($db)->statement(DB::raw('set @i=0')->getValue(DB::connection()->getQueryGrammar()));
  
      $sub = static::selectRaw('*, @i:=@i+1 as row_number')
          ->orderBy($column, $order)->toSql();
    
      $query->from(DB::raw("({$sub}) as t"));
     
  }

  public function scopeWithRowNumberWhereColumnDb($query, $db, $column_where, $value_where ,$column = 'created_at', $order = 'asc')
  {
      DB::connection($db)->statement(DB::raw('set @i=0')->getValue(DB::connection()->getQueryGrammar()));

      $sub = static::selectRaw('*, @i:=@i+1 as row_number')
          ->where($column_where, $value_where)
          ->orderBy($column, $order);

      $query->from(DB::raw("({$sub->toSql()}) as t"))
            ->mergeBindings($sub->getQuery()) ;
  }

      public function scopeActive($query)
      {
          return $query->where('active', 1);
      }

      public function scopeActiveValue($query, $value = 1)
      {
          return $query->where('active', $value);
      }

      public function scopeOfOrderBy($query,$field,$orderBy)
      {
          return $query->orderBy($field, $orderBy);
      }

      public function scopeWhereDefault($query, $column = 'code', $value)
      {
          return $query->where($column,$value);
      }

      public function scopeWhereNotValue($query, $column = 'code', $value)
      {
          return $query->where($column,'!=',$value);
      }

      public function scopeWhereCheck($query, $column = 'code', $value ,$column1 = 'id', $value1)
      {
        return $query->where($column,$value)->WhereNotValue($column1,$value1);
      }

      public function scopeWhereCheck1($query, $column = 'code', $value ,$column1 = 'link', $value1,$column2 = 'id', $value2)
      {
        return $query->where($column,$value)->orWhere(function($query) use ($column1,$value1) {$query->where($column1,$value1)->where($column1,"!=","");})->WhereNot($column2,$value2);
      }
    
}
