<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Http\Model\AccSystems;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GlobalComposer
{
    /**
     * The user repository implementation.
     *
     * @var GlobalRepository
     */
    protected $decimal;
    protected $decimal_symbol;
    protected $pagesize;
    protected $pagesize_1;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct(Request $request)
    {
        // Dependencies automatically resolved by service container...
        $arr = ['DECIMAL','DECIMAL_SYMBOL','PAGESIZE','PAGESIZE_1'];
        $sys = AccSystems::get_systems_whereIn($arr);
        $sys->each(function ($item, $key) {
            $name = strtolower($item->code);
            $this->{$name} = $item->value;
        });
        $request->session()->put('decimal',$this->decimal);
        $request->session()->put('decimal_symbol',$this->decimal_symbol);
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('decimal',$this->decimal);
        $view->with('decimal_symbol',$this->decimal_symbol);
        $view->with('page_size',$this->pagesize);
        $view->with('page_size_1',$this->pagesize_1);
    }
}
