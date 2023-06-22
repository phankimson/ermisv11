<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Http\Model\User;
use Illuminate\Support\Facades\Auth;

class UserComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $users;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        // Dependencies automatically resolved by service container...
        $user = Auth::user();
        $t = User::get_company($user->company_default,1);
        $this->user = $t;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('users_com',$this->user);
    }
}
