<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Http\Model\CompanySoftware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DatabaseAllComposer
{
    /**
     * The user repository implementation.
     *
     * @var DatabaseAllRepository
     */
    protected $data;

    /**
     * Create a new profile composer.
     *
     * @param  DatabaseAllRepository  $users
     * @return void
     */
    public function __construct(Request $request)
    {
        $data = CompanySoftware::get_company_software_get();
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
