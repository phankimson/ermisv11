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
        $this->arr = (['DDX'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.day').' (DDX)',
                       'XDD'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.day').' (XDD)',
                       'DD-X'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.day').' (DD-X)',
                       'X-DD'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.day').' (X-DD)',
                       'DD/X'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.day').' (DD/X)',
                       'X/DD'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.day').' (X/DD)',
                       'MMX'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' (MMX)', 
                       'XMM'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' (XMM)', 
                       'MM/X'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' (MM/X)',
                       'X/MM'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' (X/MM)',
                       'MM-X'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' (MM-X)', 
                       'X-MM'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' (X-MM)',      
                       'YYX'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.year').' (YYX)', 
                       'XYY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.year').' (XYY)',
                       'YY/X'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.year').' (YY/X)',
                       'X/YY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.year').' (X/YY)',    
                       'YY-X'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.year').' (YY-X)',
                       'X-YY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.year').' (X-YY)', 
                       'YYYYX'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.year_full').' (YYYYX)', 
                       'XYYYY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.year_full').' (XYYYY)',   
                       'YYYY/X'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.year_full').' (YYYY/X)',
                       'X/YYYY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.year_full').' (X/YYYY)',  
                       'YYYY-X'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.year_full').' (YYYY-X)',  
                       'X-YYYY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.year_full').' (X-YYYY)',  
                       'MMYYX'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (MMYYX)',
                       'XMMYY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (XMMYY)',
                       'MMXYY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (MMXYY)',
                       'MM/YY/X'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (MM/YY/X)', 
                       'X/MM/YY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (X/MM/YY)',   
                       'MM/X/YY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (MM/X/YY)',   
                       'MM-YY-X'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (MM-YY-X)',  
                       'X-MM-YY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (X-MM-YY)',  
                       'MM-X-YY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (MM-X-YY)', 
                       'YYMMX'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (YYMMX)',
                       'XYYMM'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (XYYMM)',  
                       'YYXMM'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (YYXMM)',
                       'YY/MM/X'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (YY/MM/X)',
                       'X/YY/MM'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (X/YY/MM)', 
                       'YY/X/MM'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (YY/X/MM)',   
                       'YY-MM-X'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (YY-MM-X)', 
                       'X-YY-MM'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (X-YY-MM)',
                       'YY-X-MM'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (YY-X-MM)',
                       'MMYYYYX'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (MMYYYYX)',
                       'XMMYYYY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (XMMYYYY)',
                       'MMXYYYY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (MMXYYYY)',
                       'MM-YYYY-X'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (MM-YYYY-X)',
                       'X-MM-YYYY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (X-MM-YYYY)',
                       'MM-X-YYYY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (MM-X-YYYY)',
                       'MM/YYYY/X'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (MM/YYYY/X)',
                       'X/MM/YYYY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (X/MM/YYYY)',
                       'MM/X/YYYY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (MM/X/YYYY)',
                       'YYYYMMX'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (YYYYMMX)',
                       'XYYYYMM'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (XYYYYMM)',
                       'YYYYXMM'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (YYYYXMM)',
                       'MM-YYYY-X'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (YYYY-MM-X)',
                       'X-MM-YYYY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (X-YYYY-MM)',
                       'MM-X-YYYY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (YYYY-X-MM)',
                       'MM/YYYY/X'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (YYYY/MM/X)',
                       'X/MM/YYYY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (X/YYYY/MM)',
                       'MM/X/YYYY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.month').' '.trans('acc_number_voucher.year').' (YYYY/X/MM)',
                       'DDMMYYX'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.date').' (DDMMYYX)', 
                       'XDDMMYY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.date').' (XDDMMYY)', 
                       'DD/MM/YY/X'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.date').' (DD/MM/YY/X)', 
                       'X/DD/MM/YY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.date').' (X/DD/MM/YY)',
                       'DD-MM-YY-X'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.date').' (DD-MM-YY-X)', 
                       'X-DD-MM-YY'=> trans('acc_number_voucher.for').' '.trans('acc_number_voucher.date').' (X-DD-MM-YY)', 
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
