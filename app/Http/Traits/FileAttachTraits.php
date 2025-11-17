<?php
namespace App\Http\Traits;
use App\Http\Model\AccSystems;
use App\Http\Model\AccAttach;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

trait FileAttachTraits
{
      public function saveFile($request,$general_id,$path_system)
      {
        if($request->hasFile('files')) {
             $files = $request->file('files');
             $com = $request->session()->get('com');
             $sys = AccSystems::get_systems($path_system);
             $rs = collect();
             foreach($files as $file){          
               $filename = Str::random(10).'_'.$file->getClientOriginalName();           
               $path = public_path().'/'.$sys->value.'/'.$com->id.'/'. $general_id;
               $pathname = $sys->value . $com->id.'/'. $general_id.'/'.$filename;
               if(!File::isDirectory($path)){
               File::makeDirectory($path, 0777, true, true);
               }
               $file->move($path, $filename);
               // Lưu lại hình ảnh
               $attach = new AccAttach();
               $attach->general_id = $general_id;
               $attach->name = $filename;
               $attach->path = $pathname;
               $attach->active = 1;
               $attach->save();
               $rs->push($attach);
             }
             return $rs;
           }
      }   
      public function deleteFile($attach){
            foreach($attach as $a){
                //Xóa ảnh cũ
                if(File::exists(public_path($a->path))){
                  File::delete(public_path($a->path));
                };
                $a->delete();
              };
      }   

}
