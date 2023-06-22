<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Http\Model\Systems;
use App\Http\Model\Timeline;

class TimelineComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    /**protected $users;*/

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        // Dependencies automatically resolved by service container...
        $sys = Systems::get_systems('MAX_TIMELINE');
        $t = Timeline::get_timeline(0,$sys->value);
        $this->timeline = $t;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('timeline',$this->timeline);
    }
}
