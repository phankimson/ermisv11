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
    }
}
