<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Http\Model\Systems;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GlobalManagerComposer
{
    /**
     * The user repository implementation.
     *
     * @var GlobalManagerRepository
     */
    protected $pagesize;

    /**
     * Create a new profile composer.
     *
     * @param  GlobalManagerRepository  $users
     * @return void
     */
    public function __construct(Request $request)
    {
        // Dependencies automatically resolved by service container...
        $arr = ['PAGESIZE'];
        $sys = Systems::get_systems_whereIn($arr);
        $sys->each(function ($item, $key) {
            $name = strtolower($item->code);
            $this->{$name} = $item->value;
        });
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
       $view->with('page_size',$this->pagesize);
    }
}
