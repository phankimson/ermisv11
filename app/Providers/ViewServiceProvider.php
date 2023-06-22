<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers...
        View::composer('includes.chat', 'App\Http\View\Composers\TimelineComposer');
        View::composer('includes.chat', 'App\Http\View\Composers\UserComposer');
        View::composer('global.permission', 'App\Http\View\Composers\UserComposer');
        View::composer('global.permission', 'App\Http\View\Composers\MenuComposer');
        View::composer('global.profile', 'App\Http\View\Composers\HistoryActionComposer');
        View::composer('includes.menu', 'App\Http\View\Composers\MenuComposer');
        View::composer('manage.*', 'App\Http\View\Composers\TitleComposer');
        View::composer('manage.*', 'App\Http\View\Composers\GlobalManagerComposer');
        View::composer('acc.*', 'App\Http\View\Composers\TitleComposer');
        View::composer('global.*', 'App\Http\View\Composers\TitleComposer');
        View::composer('global.*', 'App\Http\View\Composers\GlobalManagerComposer');
        View::composer('acc.*', 'App\Http\View\Composers\GlobalComposer');
        View::composer('action.top_menu_4', 'App\Http\View\Composers\DatabaseComposer');
        View::composer('action.top_menu_3', 'App\Http\View\Composers\DatabaseAllComposer');
        View::composer('window.window_1', 'App\Http\View\Composers\AccObjectTypeComposer');
        View::composer('window.window_3', 'App\Http\View\Composers\AccSupplierGoodsTypeComposer');
        View::composer('action.content_*', 'App\Http\View\Composers\AccAccountedAutoComposer');
        View::composer('action.content_*', 'App\Http\View\Composers\AccCurrencyComposer');
        View::composer('action.content_*', 'App\Http\View\Composers\AccCurrencyComposer');
        View::composer('acc.number_voucher', 'App\Http\View\Composers\NumberVoucherFormatComposer');
        // Using Closure based composers...
        //View::composer('dashboard', function ($view) {
            //
        //});
    }
}
