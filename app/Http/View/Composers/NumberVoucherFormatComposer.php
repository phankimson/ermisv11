<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Http\Request;

class NumberVoucherFormatComposer
{
    /**
     * The user repository implementation.
     *
     * @var GlobalRepository
     */
    protected $arr;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct(Request $request)
    {
        // Dependencies automatically resolved by service container...
        $this->arr = (['DD'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.day'),
                       'MM'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month'),  
                       'YY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.year'),  
                       'YYYY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.year_full'),  
                       'MMYY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year'),  
                       'DDMMYY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.date'), 
                      ]);
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('number_voucher_format',$this->arr);       
    }
}
