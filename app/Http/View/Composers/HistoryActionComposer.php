<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Http\Model\HistoryAction;
use App\Http\Model\AccHistoryAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HistoryActionComposer
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
        $type = $request->session()->get('type');
        // Dependencies automatically resolved by service container...
        $user = Auth::id();
        if($type==1){
          $data = HistoryAction::get_count($user);
          $this->data = $data;
        }else{
          $data = AccHistoryAction::get_count($user);
          $this->data = $data;
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
        $view->with('count_history_action',$this->data);
    }
}
