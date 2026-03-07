<?php
namespace App\Http\Traits;
use App\Http\Model\HistoryAction;

trait HistoryTraits
{
      public function create_history($type,$user_id,$menu_id,$url,$data)
      {
          // Luu lich su thao tac
          $h = new HistoryAction();
          $h ->create([
            'type' => $type, // Add : 2 , Edit : 3 , Delete : 4
            'user' => $user_id,
            'menu' => $menu_id,
            'url'  => $url,
            'dataz' => \json_encode($data)]);

      }   

}
