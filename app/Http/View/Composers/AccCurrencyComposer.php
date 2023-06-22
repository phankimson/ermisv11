<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Http\Model\AccCurrency;
use App\Http\Model\AccSystems;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\DropDownListResource;

class AccCurrencyComposer
{
    /**
     * The user repository implementation.
     *
     * @var AccCurrencyRepository
     */
    protected $data;
    protected $default;
    protected $rate;

    /**
     * Create a new profile composer.
     *
     * @param  AccCurrencyRepository  $users
     * @return void
     */
    public function __construct(Request $request)
    {
        // Dependencies automatically resolved by service container...
        $data = DropDownListResource::collection(AccCurrency::active()->get());
        $this->data = $data;
        $default = AccSystems::get_systems("CURRENCY_DEFAULT");
        $this->currency_default = $default;
        $rate = AccCurrency::get_code($default->value);
        $this->currency_rate = $rate->rate;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('currency',$this->data);
        $view->with('currency_default', $this->currency_default);
        $view->with('currency_rate',$this->currency_rate);
    }
}
