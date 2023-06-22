<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Http\Model\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TitleComposer
{
    /**
     * The user repository implementation.
     *
     * @var Title
     */
    protected $data;
    protected $toolbar;

    /**
     * Create a new profile composer.
     *
     * @param  Title  $users
     * @return void
     */
    public function __construct(Request $request)
    {
        // Dependencies automatically resolved by service container...
        $type = $request->session()->get('type');
        // Lấy thông tin công ty
        $com_name = '';
        $com = $request->session()->get('com');
          if($com){
            $com_name = $com->name.' - ';
          }
        $segments = $request->segments();
        if(count($segments)>2){
          $locale = $segments[0];
          $data = Menu::get_menu_by_url($type,$segments[1].'/'.$segments[2]);
          if($data){
              $this->data = ($locale == 'vi' ? $com_name.$data->sw_name.' - '.$data->name : $com_name.$data->sw_name_en.' - '.$data->name_en);
              $this->toolbar = ($locale == 'vi' ? $data->sw_name.' - '.$data->name : $data->sw_name_en.' - '.$data->name_en);
          }else{
              $this->data = 'Ermis';
              $this->toolbar = 'Ermis';
          }
        }else if(count($segments)>1){
              $locale = $segments[0];
              $this->data =  ($locale == 'vi' ? $com_name.'Ermis - Trang chủ' : $com_name.'Ermis - Index');
              $this->toolbar = ($locale == 'vi' ? 'Ermis - Trang chủ' : 'Ermis - Index');
        }
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('title',$this->data);
        $view->with('toolbar',$this->toolbar);
    }
}
