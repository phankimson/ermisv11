<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Http\Model\CompanySoftware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DatabaseComposer
{
    /**
     * The user repository implementation.
     *
     * @var CompanySoftwareRepository
     */
    protected $data;

    /**
     * Create a new profile composer.
     *
     * @param  CompanySoftwareRepository  $users
     * @return void
     */
    public function __construct(Request $request)
    {
        $type = $request->session()->get('type');
        // Dependencies automatically resolved by service container...
        $data = CompanySoftware::get_company_software_all($type);
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
        $view->with('database',$this->data);
    }
}
