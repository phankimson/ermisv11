<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Http\Model\AccAccountedAuto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\DropDownListResource;

class AccAccountedAutoComposer
{
    /**
     * The user repository implementation.
     *
     * @var AccountedAutoRepository
     */
    protected $data;

    /**
     * Create a new profile composer.
     *
     * @param  AccountedAutoRepository  $users
     * @return void
     */
    public function __construct(Request $request)
    {
        // Dependencies automatically resolved by service container...
        $data = DropDownListResource::collection(AccAccountedAuto::active()->get());
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
        $view->with('accounted_auto',$this->data);
    }
}
