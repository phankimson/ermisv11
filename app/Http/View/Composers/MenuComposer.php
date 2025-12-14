<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Http\Model\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MenuComposer
{
    /**
     * The user repository implementation.
     *
     * @var MenuRepository
     */
    protected $data;
    protected $current;

    /**
     * Create a new profile composer.
     *
     * @param  MenuRepository  $users
     * @return void
     */
    public function __construct(Request $request)
    {
        // Dependencies automatically resolved by service container...
        $type = $request->session()->get('type');
        $data = Menu::get_menu_by_type($type,"0");
        $this->data = $data;
        $menu_current = Menu::get_menu_by_code(request()->segment(3));
        if(optional($menu_current)->group>0){
          $this->current = optional($menu_current)->parent_id;  
        }else{
          $this->current = optional($menu_current)->id;
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
        $view->with('menu',$this->data);
        $view->with('menu_current',$this->current);
    }
}
