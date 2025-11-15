<?php
namespace App\Http\Traits;
use App\Http\Model\AccGeneral;

trait ReferenceTraits
{
      public function saveReference($reference_by,$general_id)
      {
         // Ktra dòng dư tham chiếu
          if(isset($reference_by) && collect($reference_by)->count()>0){
            $rb = AccGeneral::get_reference_by_whereNotIn($reference_by);
            $rb->each(function ($item, $key) {
              $item->reference_by = 0;
              $item->save();
            });
          // Lưu tham chiếu
            foreach($reference_by as $s => $f){
              $general_reference = AccGeneral::find($f);
              if($general_reference->reference_by == 0){
                $general_reference-> reference_by = $general_id;
                $general_reference->save();
              }
            };
          }else{
              $rb = AccGeneral::get_reference_by($general_id);
              $rb->each(function ($item, $key) {
                $item->reference_by = 0;
                $item->save();
              });
          };
      }   

}
