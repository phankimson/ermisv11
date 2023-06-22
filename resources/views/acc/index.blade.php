@extends('layouts.default')
@section('title',  $title )
@push('css_up')
<!-- additional styles for plugins -->
    <!-- weather icons -->
    <link rel="stylesheet" href="{{ asset('library/weather-icons/css/weather-icons.min.css')}}" media="all">
    <!-- metrics graphics (charts) -->
    <link rel="stylesheet" href="{{ asset('library/metrics-graphics/dist/metricsgraphics.css')}}">
    <!-- chartist -->
    <link rel="stylesheet" href="{{ asset('library/chartist/dist/chartist.min.css')}}">

    <link rel="stylesheet" href="{{ asset('addon/css/index.css')}}">
@endpush

@push('css_down')

@endpush

@push('action')

@endpush

@section('content')
<div id="page_content">
       <div id="page_content_inner">
           <!-- statistics (small charts) -->
           <div class="md-card uk-margin-medium-bottom" id="all">
            <div class="md-card-content">
                <div class="uk-grid">
                    <div class="uk-width-1-1">
                        <ul class="uk-tab" data-uk-tab="{connect:'#tabs_1_content'}" id="tabs_1">
                            <li class="uk-active"><a href="#">@lang('index.diagrams')</a></li>
                            <li><a href="#">@lang('index.analytic')<span class="material-icons">clear</span></a></li>
                            <!-- <li class="named_tab"><a href="#">@lang('index.expand')</a></li> -->
                            <li class="uk-disabled"><a href="#">@lang('global.expand')</a></li>
                        </ul>
                        <ul id="tabs_1_content" class="uk-switcher uk-margin">
                            <div>
                                <div class="circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="4.93701in" height="4.93701in" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                                         viewBox="0 0 3937 3937"
                                         xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <defs>
                                    <style type="text/css">
                                        .str0 {
                                            stroke: #2B2A29;
                                            stroke-width: 6.94488;
                                        }

                                        .fil0 {
                                            fill: none;
                                        }

                                        .fil1 {
                                            fill: none;
                                            fill-rule: nonzero;
                                        }

                                        .fil2 {
                                            fill: #5CA595;
                                        }

                                        .fil3 {
                                            fill: #FEFEFE;
                                        }
        </style>
         </defs>
                                    <g id="Layer_x0020_1" >
                                    <metadata id="CorelCorpID_0Corel-Layer" />
                                    <circle class="fil0 str0" cx="1969" cy="1969" r="1182" />
                                    <circle class="fil0 str0" cx="1965" cy="1971" r="1083" />
                                    <path class="fil1 str0" d="M1877 792l0 -265 -96 0 192 -265 192 265 -96 0 0 265 -192 0zm0 -132m-48 -132m48 -132m192 0m48 132m-48 132m-96 132" />
                                    <path class="fil1 str0" d="M1174 1097l-170 -203 -74 62 -23 -326 317 80 -74 62 170 203 -147 123zm-85 -101m-122 -71m-48 -132m147 -123m122 71m48 132m12 163" />
                                    <path class="fil1 str0" d="M798 1828l-261 -46 -17 95 -228 -235 294 -143 -17 95 261 46 -33 189zm-130 -23m-139 24m-122 -70m33 -189m139 -24m122 70m114 118" />
                                    <path class="fil1 str0" d="M993 2635l-229 132 48 83 -325 -34 133 -299 48 83 229 -132 96 166zm-115 66m-91 108m-139 25m-96 -166m91 -108m139 -25m163 17" />
                                    <path class="fil1 str0" d="M1635 3099l-91 249 90 33 -271 183 -90 -315 90 33 91 -249 180 66zm-45 124m0 141m-90 108m-180 -66m0 -141m90 -108m135 -92" />
                                    <path class="fil1 str0" d="M2492 3030l91 249 90 -33 -90 315 -271 -183 90 -33 -91 -249 180 -66zm45 124m90 108m0 141m-180 66m-90 -108m0 -141m45 -157" />
                                    <path class="fil1 str0" d="M3030 2493l229 132 48 -83 133 299 -325 34 48 -83 -229 -132 96 -166zm115 66m139 25m91 108m-96 166m-139 -25m-91 -108m-67 -149" />
                                    <path class="fil1 str0" d="M3104 1618l261 -46 -17 -95 294 143 -228 235 -17 -95 -261 46 -33 -189zm130 -23m122 -70m139 24m33 189m-122 70m-139 -24m-147 -72" />
                                    <path class="fil1 str0" d="M2653 1000l170 -203 -74 -62 317 -80 -23 326 -74 -62 -170 203 -147 -123zm85 -101m48 -132m122 -71m147 123m-48 132m-122 71m-159 40" />
                                    <path class="fil2 str0" d="M2688 1031l78 65c166,152 289,351 347,577l24 135 3 0c7,53 11,107 11,162 0,195 -47,380 -131,542l-55 95c-117,183 -283,331 -480,427l-173 63 2 4c-109,33 -225,51 -344,51 -116,0 -229,-17 -335,-48l1 -3 -180 -65c-187,-90 -347,-229 -463,-399l1 0 -96 -166c-71,-152 -111,-321 -111,-500 0,-48 3,-95 8,-141l3 1 31 -173c60,-219 181,-412 344,-560l2 3 118 -99c168,-118 369,-193 585,-209l0 3 192 0 0 -2c232,20 445,106 619,240zm-1806 941c0,598 485,1083 1083,1083 598,0 1083,-485 1083,-1083 0,-598 -485,-1083 -1083,-1083 -598,0 -1083,485 -1083,1083z" />
                                    <path class="fil2 str0" d="M1004 894l168 200c25,-21 125,15 177,-70l-28 -51 -170 -203 74 -62 -317 -80 23 326 74 -62z" />
                                    <path class="fil2 str0" d="M1877 528l0 262c35,19 -7,87 88,99 95,12 142,-65 104,-98l0 -263 96 0 -192 -265 -192 265 96 0z" />
                                    <path class="fil2 str0" d="M2823 798l-170 203 20 89c1,77 41,98 127,34l0 0 170 -203 74 62 23 -326 -317 80 74 62z" />
                                    <path class="fil2 str0" d="M3365 1572l-261 46 -63 54c11,44 36,115 99,134l258 -46 17 95 228 -235 -294 -143 17 95z" />
                                    <path class="fil2 str0" d="M3259 2626l-292 -169 26 27c0,0 -51,55 -70,85l11 90 229 132 -48 83 325 -34 -133 -299 -48 83z" />
                                    <path class="fil2 str0" d="M2582 3279l-91 -249 -7 3c-55,-87 -112,5 -172,67l89 245 -90 33 271 183 90 -315 -90 33z" />
                                    <path class="fil2 str0" d="M1544 3348l89 -245c-6,-90 -80,-95 -179,-69l-1 0 -91 249 -90 -33 90 315 271 -183 -90 -33z" />
                                    <path class="fil2 str0" d="M763 2767l229 -132c27,-138 63,-114 -95,-167l-229 132 -48 -83 -133 299 325 34 -48 -83z" />
                                    <path class="fil2 str0" d="M537 1782l258 45c88,-59 104,-117 34,-173l3 -16 -261 -46 17 -95 -294 143 228 235 17 -95z" />
                                    <path class="fil2 str0" d="M2797 1125c138,136 244,306 302,497l5 -4 261 -46 -17 -95 294 143 -228 235 -17 -95 -258 46c7,53 11,107 11,162 0,188 -44,366 -122,524l230 133 48 -83 133 299 -325 34 48 -83 -229 -132 -1 -7c-115,161 -269,293 -448,380l7 -3 91 249 90 -33 -90 315 -271 -183 90 -33 -89 -245c-109,33 -225,51 -344,51 -116,0 -229,-17 -335,-48l-89 245 90 33 -271 183 -90 -315 90 33 91 -249 1 0c-187,-90 -347,-229 -463,-399l-229 132 48 83 -325 -34 133 -299 48 83 229 -132c-71,-152 -111,-321 -111,-500 0,-48 3,-95 8,-141l-258 -45 -17 95 -228 -235 294 -143 -17 95 261 46 -3 16c60,-219 181,-412 344,-560l-168 -200 -74 62 -23 -326 317 80 -74 62 170 203 2 4c162,-105 351,-172 554,-188l0 -262 -96 0 192 -265 192 265 -96 0 0 263c217,18 417,95 584,214l-1 -4 170 -203 -74 -62 317 -80 -23 326 -74 -62 -170 203c-1,1 -1,1 -2,2z" />
                                    <circle class="fil0 str0" cx="1965" cy="1971" r="1083" />
                                    <path class="fil3 str0" d="M3048 1971c0,-587 -467,-1065 -1050,-1082 -11,0 -22,0 -33,0 -598,0 -1083,485 -1083,1083 0,598 485,1083 1083,1083 598,0 1083,-485 1083,-1083z" />
         </g>
        </svg>

                                    <dl>
                                        <dd><a class="text-center lazyload change-map" data-uk-tooltip title="@lang('acc_index.general')" href="javascript:;" data-id="#general"><img src="{{url('addon/img/icon/book-icon.png')}}" /></a></dd>
                                        <dd><a class="text-center lazyload change-map" data-uk-tooltip title="@lang('acc_index.safe')" href="javascript:;" data-id="#safe"><img src="{{url('addon/img/icon/Safe-icon.png')}}" /></a></dd>
                                        <dd><a class="text-center lazyload change-map" data-uk-tooltip title="@lang('acc_index.material')" href="javascript:;" data-id="#material"><img src="{{url('addon/img/icon/barcode-icon.png')}}" /></a></dd>
                                        <dd><a class="text-center lazyload change-map" data-uk-tooltip title="@lang('acc_index.tools')" href="javascript:;" data-id="#tools"><img src="{{url('addon/img/icon/tools.png')}}" /></a></dd>
                                        <dd><a class="text-center lazyload change-map" data-uk-tooltip title="@lang('acc_index.fixed_assets')" href="javascript:;" data-id="#fixed_assets"><img src="{{url('addon/img/icon/Building-icon.png')}}" /></a></dd>
                                        <dd><a class="text-center lazyload change-map" data-uk-tooltip title="@lang('acc_index.payroll')" href="javascript:;" data-id="#payroll"><img src="{{url('addon/img/icon/payroll-icon.png')}}" /></a></dd>
                                        <dd><a class="text-center lazyload change-map" data-uk-tooltip title="@lang('acc_index.buy')" href="javascript:;" data-id="#buy"><img src="{{url('addon/img/icon/shopping-icon.png')}}" /></a></dd>
                                        <dd><a class="text-center lazyload change-map" data-uk-tooltip title="@lang('acc_index.sale')" href="javascript:;" data-id="#sale"><img src="{{url('addon/img/icon/sale-icon.png')}}" /></a></dd>
                                        <dd><a class="text-center lazyload change-map" data-uk-tooltip title="@lang('acc_index.tax')" href="javascript:;" data-id="#tax"><img src="{{url('addon/img/icon/Tax-icon.png')}}" /></a></dd>
                                        <dd><a class="text-center lazyload change-map" data-uk-tooltip title="@lang('acc_index.bank')" href="javascript:;" data-id="#bank"><img src="{{url('addon/img/icon/Bank-icon.png')}}" /></a></dd>
                                    </dl>
                                </div>

                            </div>
                            <li>Content 2</li>
                            <!--<li>Content 3</li>-->
                            <li>Content 4</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="md-card uk-margin-medium-bottom" style="display: none" id="bank">
            <div class="md-card-toolbar">
                <div class="md-card-toolbar-heading-text">
                    @lang('acc_index.bank')
                </div>
              @include('action.toolbar_3')
            </div>
            <div class="md-card-content">
                <div class="uk-grid">
                    <div class="uk-width-1-1">
                        <div class="content-svg">
                          <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="8.811in" height="5.00551in" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                               viewBox="0 0 11811 5906"
                               xmlns:xlink="http://www.w3.org/1999/xlink">
                          <defs>
                          <style type="text/css">
                              .str0 {
                                  stroke: #2B2A29;
                                  stroke-width: 3;
                              }

                              .fil1 {
                                  fill: #A2D9F7;
                              }

                              .fil0 {
                                  fill: #A2D9F7;
                                  fill-rule: nonzero;
                              }
                        </style>
                         </defs>
                                            <g id="Layer_x0020_1">
                                            <metadata id="CorelCorpID_0Corel-Layer" />
                                            <path class="fil0 str0" d="M664 2405l346 0 0 -249 693 498 -693 498 0 -249 -346 0 0 -498zm-208 0l139 0 0 498 -139 0 0 -498zm-139 0l69 0 0 498 -69 0 0 -498zm520 0m173 -124m346 124m0 498m-346 124m-173 -124m-173 -249m-139 -249m69 249m-69 249m-69 -249m-104 -249m35 249m-35 249m-35 -249" />
                                            <path class="fil0 str0" d="M6799 2457l346 0 0 -249 693 498 -693 498 0 -249 -346 0 0 -498zm-208 0l139 0 0 498 -139 0 0 -498zm-139 0l69 0 0 498 -69 0 0 -498zm520 0m173 -124m346 124m0 498m-346 124m-173 -124m-173 -249m-139 -249m69 249m-69 249m-69 -249m-104 -249m35 249m-35 249m-35 -249" />
                                            <rect class="fil1 str0" x="87" y="44" width="200" height="5777" />
                                            <rect class="fil1 str0" x="6189" y="67" width="200" height="5777" />
                         </g>
                        </svg>
                            <dl>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.receipt_bank_voucher')" href="{{url('')}}"><img src="{{url('addon/img/icon/notepad.png')}}" /></a><p>@lang('acc_index.receipt_bank_voucher')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.payment_bank_voucher')" href="{{url('')}}"><img src="{{url('addon/img/icon/notes.png')}}" /></a><p>@lang('acc_index.payment_bank_voucher')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.report_bank')" href="{{url('')}}"><img src="{{url('addon/img/icon/report.png')}}" /></a><p>@lang('acc_index.report_bank')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.report_general_bank')" href="{{url('')}}x"><img src="{{url('addon/img/icon/notebook.png')}}" /></a><p>@lang('acc_index.report_general_bank')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.bank')" href="{{url('')}}"><img src="{{url('addon/img/icon/checklist.png')}}" /></a><p>@lang('acc_index.bank')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.bank_account')" href="{{url('')}}"><img src="{{url('addon/img/icon/list.png')}}" /></a><p>@lang('acc_index.bank_account')</p></dd>
                            </dl>

                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="md-card uk-margin-medium-bottom" style="display: none" id="safe">
            <div class="md-card-toolbar">
                <div class="md-card-toolbar-heading-text">
                    @lang('acc_index.safe')
                </div>
              @include('action.toolbar_3')
            </div>
            <div class="md-card-content">
                <div class="uk-grid">
                    <div class="uk-width-1-1">
                        <div class="content-svg">
                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="8.811in" height="5.00551in" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                                 viewBox="0 0 11811 5906"
                                 xmlns:xlink="http://www.w3.org/1999/xlink">
                            <defs>
                            <style type="text/css">
                                .str0 {
                                    stroke: #2B2A29;
                                    stroke-width: 3;
                                }

                                .fil1 {
                                    fill: #A2D9F7;
                                }

                                .fil0 {
                                    fill: #A2D9F7;
                                    fill-rule: nonzero;
                                }
                          </style>
                           </defs>
                                              <g id="Layer_x0020_1">
                                              <metadata id="CorelCorpID_0Corel-Layer" />
                                              <path class="fil0 str0" d="M664 2405l346 0 0 -249 693 498 -693 498 0 -249 -346 0 0 -498zm-208 0l139 0 0 498 -139 0 0 -498zm-139 0l69 0 0 498 -69 0 0 -498zm520 0m173 -124m346 124m0 498m-346 124m-173 -124m-173 -249m-139 -249m69 249m-69 249m-69 -249m-104 -249m35 249m-35 249m-35 -249" />
                                              <path class="fil0 str0" d="M6799 2457l346 0 0 -249 693 498 -693 498 0 -249 -346 0 0 -498zm-208 0l139 0 0 498 -139 0 0 -498zm-139 0l69 0 0 498 -69 0 0 -498zm520 0m173 -124m346 124m0 498m-346 124m-173 -124m-173 -249m-139 -249m69 249m-69 249m-69 -249m-104 -249m35 249m-35 249m-35 -249" />
                                              <rect class="fil1 str0" x="87" y="44" width="200" height="5777" />
                                              <rect class="fil1 str0" x="6189" y="67" width="200" height="5777" />
                           </g>
                          </svg>
                            <dl>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.receipt_cash_voucher')" href="{{url('')}}"><img src="{{url('addon/img/icon/atm.png')}}" /></a><p>@lang('acc_index.receipt_cash_voucher')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.payment_cash_voucher')" href="{{url('')}}"><img src="{{url('addon/img/icon/cash.png')}}" /></a><p>@lang('acc_index.payment_cash_voucher')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.report_cash')" href="{{url('')}}"><img src="{{url('addon/img/icon/report.png')}}" /></a><p>@lang('acc_index.report_cash')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.report_cash_general')" href="{{url('')}}"><img src="{{url('addon/img/icon/notebook.png')}}" /></a><p>@lang('acc_index.report_cash_general')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.department')" href="{{url('')}}"><img src="{{url('addon/img/icon/checklist.png')}}" /></a><p>@lang('acc_index.department')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.employee')" href="{{url('')}}"><img src="{{url('addon/img/icon/list.png')}}" /></a><p>@lang('acc_index.employee')</p></dd>
                            </dl>

                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="md-card uk-margin-medium-bottom" style="display: none" id="buy">
            <div class="md-card-toolbar">
                <div class="md-card-toolbar-heading-text">
                    @lang('acc_index.buy')
                </div>
                  @include('action.toolbar_3')
            </div>
            <div class="md-card-content">
                <div class="uk-grid">
                    <div class="uk-width-1-1">
                        <div class="content-svg">
                          <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="8.811in" height="5.00551in" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                               viewBox="0 0 11811 5906"
                               xmlns:xlink="http://www.w3.org/1999/xlink">
                          <defs>
                          <style type="text/css">
                              .str0 {
                                  stroke: #2B2A29;
                                  stroke-width: 3;
                              }

                              .fil1 {
                                  fill: #A2D9F7;
                              }

                              .fil0 {
                                  fill: #A2D9F7;
                                  fill-rule: nonzero;
                              }
                      </style>
                       </defs>
                                          <g id="Layer_x0020_1">
                                          <metadata id="CorelCorpID_0Corel-Layer" />
                                          <path class="fil0 str0" d="M664 2405l346 0 0 -249 693 498 -693 498 0 -249 -346 0 0 -498zm-208 0l139 0 0 498 -139 0 0 -498zm-139 0l69 0 0 498 -69 0 0 -498zm520 0m173 -124m346 124m0 498m-346 124m-173 -124m-173 -249m-139 -249m69 249m-69 249m-69 -249m-104 -249m35 249m-35 249m-35 -249" />
                                          <path class="fil0 str0" d="M6799 2457l346 0 0 -249 693 498 -693 498 0 -249 -346 0 0 -498zm-208 0l139 0 0 498 -139 0 0 -498zm-139 0l69 0 0 498 -69 0 0 -498zm520 0m173 -124m346 124m0 498m-346 124m-173 -124m-173 -249m-139 -249m69 249m-69 249m-69 -249m-104 -249m35 249m-35 249m-35 -249" />
                                          <rect class="fil1 str0" x="87" y="44" width="200" height="5777" />
                                          <rect class="fil1 str0" x="6189" y="67" width="200" height="5777" />
                       </g>
                      </svg>
                            <dl>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.purchase_voucher')" href="{{url('')}}"><img src="{{url('addon/img/icon/invoice.png')}}" /></a><p>@lang('acc_index.purchase_voucher')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.return_purchase_voucher')" href="{{url('')}}"><img src="{{url('addon/img/icon/return.png')}}" /></a><p>@lang('acc_index.return_purchase_voucher')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.report_purchase')" href="{{url('')}}"><img src="{{url('addon/img/icon/report.png')}}" /></a><p>@lang('acc_index.report_purchase')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.report_general_purchase')" href="{{url('')}}"><img src="{{url('addon/img/icon/notebook.png')}}" /></a><p>@lang('acc_index.report_general_purchase')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.suplier')" href="{{url('')}}"><img src="{{url('addon/img/icon/checklist.png')}}" /></a><p>@lang('acc_index.suplier')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.marial_goods')" href="{{url('')}}"><img src="{{url('addon/img/icon/list.png')}}" /></a><p>@lang('acc_index.marial_goods')</p></dd>
                            </dl>

                        </div>
                    </div>

                </div>

            </div>
        </div>


        <div class="md-card uk-margin-medium-bottom" style="display: none" id="sale">
            <div class="md-card-toolbar">
                <div class="md-card-toolbar-heading-text">
                    @lang('acc_index.sale')
                </div>
                @include('action.toolbar_3')
            </div>
            <div class="md-card-content">
                <div class="uk-grid">
                    <div class="uk-width-1-1">
                        <div class="content-svg">
                          <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="8.811in" height="5.00551in" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                               viewBox="0 0 11811 5906"
                               xmlns:xlink="http://www.w3.org/1999/xlink">
                          <defs>
                          <style type="text/css">
                              .str0 {
                                  stroke: #2B2A29;
                                  stroke-width: 3;
                              }

                              .fil1 {
                                  fill: #A2D9F7;
                              }

                              .fil0 {
                                  fill: #A2D9F7;
                                  fill-rule: nonzero;
                              }
                      </style>
                       </defs>
                                          <g id="Layer_x0020_1">
                                          <metadata id="CorelCorpID_0Corel-Layer" />
                                          <path class="fil0 str0" d="M664 2405l346 0 0 -249 693 498 -693 498 0 -249 -346 0 0 -498zm-208 0l139 0 0 498 -139 0 0 -498zm-139 0l69 0 0 498 -69 0 0 -498zm520 0m173 -124m346 124m0 498m-346 124m-173 -124m-173 -249m-139 -249m69 249m-69 249m-69 -249m-104 -249m35 249m-35 249m-35 -249" />
                                          <path class="fil0 str0" d="M6799 2457l346 0 0 -249 693 498 -693 498 0 -249 -346 0 0 -498zm-208 0l139 0 0 498 -139 0 0 -498zm-139 0l69 0 0 498 -69 0 0 -498zm520 0m173 -124m346 124m0 498m-346 124m-173 -124m-173 -249m-139 -249m69 249m-69 249m-69 -249m-104 -249m35 249m-35 249m-35 -249" />
                                          <rect class="fil1 str0" x="87" y="44" width="200" height="5777" />
                                          <rect class="fil1 str0" x="6189" y="67" width="200" height="5777" />
                       </g>
                      </svg>
                            <dl>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.sell_voucher')" href="{{url('')}}"><img src="{{url('addon/img/icon/cash-register.png')}}" /></a><p>@lang('acc_index.sell_voucher')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.return_voucher')" href="{{url('')}}"><img src="{{url('addon/img/icon/return_s.png')}}" /></a><p>@lang('acc_index.return_voucher')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.report_sell')" href="{{url('')}}"><img src="{{url('addon/img/icon/report.png')}}" /></a><p>@lang('acc_index.report_sell')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.report_general_sell')" href="{{url('')}}"><img src="{{url('addon/img/icon/notebook.png')}}" /></a><p>@lang('acc_index.report_general_sell')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.customer')" href="{{url('')}}"><img src="{{url('addon/img/icon/checklist.png')}}" /></a><p>@lang('acc_index.customer')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.marial_goods')" href="{{url('')}}"><img src="{{url('addon/img/icon/list.png')}}" /></a><p>@lang('acc_index.marial_goods')</p></dd>
                            </dl>

                        </div>
                    </div>

                </div>

            </div>
        </div>


        <div class="md-card uk-margin-medium-bottom" style="display: none" id="material">
            <div class="md-card-toolbar">
                <div class="md-card-toolbar-heading-text">
                    @lang('acc_index.material')
                </div>
                @include('action.toolbar_3')
            </div>
            <div class="md-card-content">
                <div class="uk-grid">
                    <div class="uk-width-1-1">
                        <div class="content-svg">
                          <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="8.811in" height="5.00551in" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                               viewBox="0 0 11811 5906"
                               xmlns:xlink="http://www.w3.org/1999/xlink">
                          <defs>
                          <style type="text/css">
                              .str0 {
                                  stroke: #2B2A29;
                                  stroke-width: 3;
                              }

                              .fil1 {
                                  fill: #A2D9F7;
                              }

                              .fil0 {
                                  fill: #A2D9F7;
                                  fill-rule: nonzero;
                              }
                      </style>
                       </defs>
                                          <g id="Layer_x0020_1">
                                          <metadata id="CorelCorpID_0Corel-Layer" />
                                          <path class="fil0 str0" d="M664 2405l346 0 0 -249 693 498 -693 498 0 -249 -346 0 0 -498zm-208 0l139 0 0 498 -139 0 0 -498zm-139 0l69 0 0 498 -69 0 0 -498zm520 0m173 -124m346 124m0 498m-346 124m-173 -124m-173 -249m-139 -249m69 249m-69 249m-69 -249m-104 -249m35 249m-35 249m-35 -249" />
                                          <path class="fil0 str0" d="M6799 2457l346 0 0 -249 693 498 -693 498 0 -249 -346 0 0 -498zm-208 0l139 0 0 498 -139 0 0 -498zm-139 0l69 0 0 498 -69 0 0 -498zm520 0m173 -124m346 124m0 498m-346 124m-173 -124m-173 -249m-139 -249m69 249m-69 249m-69 -249m-104 -249m35 249m-35 249m-35 -249" />
                                          <rect class="fil1 str0" x="87" y="44" width="200" height="5777" />
                                          <rect class="fil1 str0" x="6189" y="67" width="200" height="5777" />
                       </g>
                      </svg>
                            <dl>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.receipt_warehouse_voucher')" href="{{url('')}}"><img src="{{url('addon/img/icon/trolley.png')}}" /></a><p>@lang('acc_index.receipt_warehouse_voucher')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.issue_warehouse_voucher')" href="{{url('')}}"><img src="{{url('addon/img/icon/caravan.png')}}" /></a><p>@lang('acc_index.issue_warehouse_voucher')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.report_stock')" href="{{url('')}}"><img src="{{url('addon/img/icon/report.png')}}" /></a><p>@lang('acc_index.report_stock')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.report_general_stock')" href="{{url('')}}"><img src="{{url('addon/img/icon/notebook.png')}}" /></a><p>@lang('acc_index.report_general_stock')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.inventory')" href="{{url('')}}"><img src="{{url('addon/img/icon/checklist.png')}}" /></a><p>@lang('acc_index.inventory')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.marial_goods')" href="{{url('')}}"><img src="{{url('addon/img/icon/list.png')}}" /></a><p>@lang('acc_index.marial_goods')</p></dd>
                            </dl>

                        </div>
                    </div>

                </div>

            </div>
        </div>



        <div class="md-card uk-margin-medium-bottom" style="display: none" id="fixed_assets">
            <div class="md-card-toolbar">
                <div class="md-card-toolbar-heading-text">
                    @lang('acc_index.fixed_assets')
                </div>
              @include('action.toolbar_3')
            </div>
            <div class="md-card-content">
                <div class="uk-grid">
                    <div class="uk-width-1-1">
                        <div class="content-svg">
                          <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="8.811in" height="5.00551in" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                               viewBox="0 0 11811 5906"
                               xmlns:xlink="http://www.w3.org/1999/xlink">
                          <defs>
                          <style type="text/css">
                              .str0 {
                                  stroke: #2B2A29;
                                  stroke-width: 3;
                              }

                              .fil1 {
                                  fill: #A2D9F7;
                              }

                              .fil0 {
                                  fill: #A2D9F7;
                                  fill-rule: nonzero;
                              }
                      </style>
                       </defs>
                                          <g id="Layer_x0020_1">
                                          <metadata id="CorelCorpID_0Corel-Layer" />
                                          <path class="fil0 str0" d="M664 2405l346 0 0 -249 693 498 -693 498 0 -249 -346 0 0 -498zm-208 0l139 0 0 498 -139 0 0 -498zm-139 0l69 0 0 498 -69 0 0 -498zm520 0m173 -124m346 124m0 498m-346 124m-173 -124m-173 -249m-139 -249m69 249m-69 249m-69 -249m-104 -249m35 249m-35 249m-35 -249" />
                                          <path class="fil0 str0" d="M6799 2457l346 0 0 -249 693 498 -693 498 0 -249 -346 0 0 -498zm-208 0l139 0 0 498 -139 0 0 -498zm-139 0l69 0 0 498 -69 0 0 -498zm520 0m173 -124m346 124m0 498m-346 124m-173 -124m-173 -249m-139 -249m69 249m-69 249m-69 -249m-104 -249m35 249m-35 249m-35 -249" />
                                          <rect class="fil1 str0" x="87" y="44" width="200" height="5777" />
                                          <rect class="fil1 str0" x="6189" y="67" width="200" height="5777" />
                       </g>
                      </svg>
                            <dl>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.increase_fixed_assets_voucher')" href="{{url('')}}"><img src="{{url('addon/img/icon/price-tag.png')}}" /></a><p>@lang('acc_index.increase_fixed_assets_voucher')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.decrease_fixed_assets_voucher')" href="{{url('')}}"><img src="{{url('addon/img/icon/price-tag (1).png')}}" /></a><p>@lang('acc_index.decrease_fixed_assets_voucher')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.auto_depreciation')" href="{{url('')}}"><img src="{{url('addon/img/icon/notebook.png')}}" /></a><p>@lang('acc_index.auto_depreciation')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.report_depreciation')" href="{{url('')}}"><img src="{{url('addon/img/icon/report.png')}}" /></a><p>@lang('acc_index.report_depreciation')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.type_fixed_assets')" href="{{url('')}}"><img src="{{url('addon/img/icon/checklist.png')}}" /></a><p>@lang('acc_index.type_fixed_assets')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.fixed_assets')" href="{{url('')}}"><img src="{{url('addon/img/icon/list.png')}}" /></a><p>@lang('acc_index.fixed_assets')</p></dd>
                            </dl>

                        </div>
                    </div>

                </div>

            </div>
        </div>


        <div class="md-card uk-margin-medium-bottom" style="display: none" id="tools">
            <div class="md-card-toolbar">
                <div class="md-card-toolbar-heading-text">
                    @lang('acc_index.tools')
                </div>
                @include('action.toolbar_3')
            </div>
            <div class="md-card-content">
                <div class="uk-grid">
                    <div class="uk-width-1-1">
                        <div class="content-svg">
                          <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="8.811in" height="5.00551in" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                               viewBox="0 0 11811 5906"
                               xmlns:xlink="http://www.w3.org/1999/xlink">
                          <defs>
                          <style type="text/css">
                              .str0 {
                                  stroke: #2B2A29;
                                  stroke-width: 3;
                              }

                              .fil1 {
                                  fill: #A2D9F7;
                              }

                              .fil0 {
                                  fill: #A2D9F7;
                                  fill-rule: nonzero;
                              }
                      </style>
                       </defs>
                                          <g id="Layer_x0020_1">
                                          <metadata id="CorelCorpID_0Corel-Layer" />
                                          <path class="fil0 str0" d="M664 2405l346 0 0 -249 693 498 -693 498 0 -249 -346 0 0 -498zm-208 0l139 0 0 498 -139 0 0 -498zm-139 0l69 0 0 498 -69 0 0 -498zm520 0m173 -124m346 124m0 498m-346 124m-173 -124m-173 -249m-139 -249m69 249m-69 249m-69 -249m-104 -249m35 249m-35 249m-35 -249" />
                                          <path class="fil0 str0" d="M6799 2457l346 0 0 -249 693 498 -693 498 0 -249 -346 0 0 -498zm-208 0l139 0 0 498 -139 0 0 -498zm-139 0l69 0 0 498 -69 0 0 -498zm520 0m173 -124m346 124m0 498m-346 124m-173 -124m-173 -249m-139 -249m69 249m-69 249m-69 -249m-104 -249m35 249m-35 249m-35 -249" />
                                          <rect class="fil1 str0" x="87" y="44" width="200" height="5777" />
                                          <rect class="fil1 str0" x="6189" y="67" width="200" height="5777" />
                       </g>
                      </svg>
                            <dl>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.increase_tools_voucher')" href="{{url('')}}"><img src="{{url('addon/img/icon/price-tag.png')}}" /></a><p>@lang('acc_index.increase_tools_voucher')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.decrease_tools_voucher')" href="{{url('')}}"><img src="{{url('addon/img/icon/price-tag (1).png')}}" /></a><p>@lang('acc_index.decrease_tools_voucher')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.auto_allocation')" href="{{url('')}}"><img src="{{url('addon/img/icon/notebook.png')}}" /></a><p>@lang('acc_index.auto_allocation')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.report_allocation')" href="{{url('')}}"><img src="{{url('addon/img/icon/report.png')}}" /></a><p>@lang('acc_index.report_allocation')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.department')" href="{{url('')}}"><img src="{{url('addon/img/icon/checklist.png')}}" /></a><p>@lang('acc_index.department')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.tools')" href="{{url('')}}"><img src="{{url('addon/img/icon/list.png')}}" /></a><p>@lang('acc_index.tools')</p></dd>
                            </dl>

                        </div>
                    </div>

                </div>

            </div>
        </div>



        <div class="md-card uk-margin-medium-bottom" style="display: none" id="general">
            <div class="md-card-toolbar">
                <div class="md-card-toolbar-heading-text">
                    @lang('acc_index.general')
                </div>
                @include('action.toolbar_3')
            </div>
            <div class="md-card-content">
                <div class="uk-grid">
                    <div class="uk-width-1-1">
                        <div class="content-svg">
                          <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="8.811in" height="5.00551in" version="1.1" style="shape-rendering:geometricPrecision; text-rendering:geometricPrecision; image-rendering:optimizeQuality; fill-rule:evenodd; clip-rule:evenodd"
                               viewBox="0 0 11811 5906"
                               xmlns:xlink="http://www.w3.org/1999/xlink">
                          <defs>
                          <style type="text/css">
                              .str0 {
                                  stroke: #2B2A29;
                                  stroke-width: 3;
                              }

                              .fil1 {
                                  fill: #A2D9F7;
                              }

                              .fil0 {
                                  fill: #A2D9F7;
                                  fill-rule: nonzero;
                              }
                      </style>
                       </defs>
                                <g id="Layer_x0020_1">
                                <metadata id="CorelCorpID_0Corel-Layer" />
                                <path class="fil0 str0" d="M664 2405l346 0 0 -249 693 498 -693 498 0 -249 -346 0 0 -498zm-208 0l139 0 0 498 -139 0 0 -498zm-139 0l69 0 0 498 -69 0 0 -498zm520 0m173 -124m346 124m0 498m-346 124m-173 -124m-173 -249m-139 -249m69 249m-69 249m-69 -249m-104 -249m35 249m-35 249m-35 -249" />
                                <path class="fil0 str0" d="M6799 2457l346 0 0 -249 693 498 -693 498 0 -249 -346 0 0 -498zm-208 0l139 0 0 498 -139 0 0 -498zm-139 0l69 0 0 498 -69 0 0 -498zm520 0m173 -124m346 124m0 498m-346 124m-173 -124m-173 -249m-139 -249m69 249m-69 249m-69 -249m-104 -249m35 249m-35 249m-35 -249" />
                                <rect class="fil1 str0" x="87" y="44" width="200" height="5777" />
                                <rect class="fil1 str0" x="6189" y="67" width="200" height="5777" />
                       </g>
                      </svg>
                            <dl>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.general_voucher')" href="{{url('')}}"><img src="{{url('addon/img/icon/pie-chart.png')}}" /></a><p>@lang('acc_index.general_voucher')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.auto_account_transfer')" href="{{url('')}}"><img src="{{url('addon/img/icon/diagram.png')}}" /></a><p>@lang('acc_index.auto_account_transfer')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.general')" href="{{url('')}}"><img src="{{url('addon/img/icon/notebook.png')}}" /></a><p>@lang('acc_index.general')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.ledger')" href="{{url('')}}"><img src="{{url('addon/img/icon/report.png')}}" /></a><p>@lang('acc_index.ledger')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.account_transfer')" href="{{url('')}}"><img src="{{url('addon/img/icon/checklist.png')}}" /></a><p>@lang('acc_index.account_transfer')</p></dd>
                                <dd><a class="text-center lazyload" data-uk-tooltip title="@lang('acc_index.account')" href="{{url('')}}"><img src="{{url('addon/img/icon/list.png')}}" /></a><p>@lang('acc_index.account')</p></dd>
                            </dl>

                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="md-card uk-margin-medium-bottom" style="display: none" id="payroll">
            <div class="md-card-toolbar">
                <div class="md-card-toolbar-heading-text">
                    @lang('acc_index.payroll')
                </div>
                @include('action.toolbar_3')
            </div>
            <div class="md-card-content">
                <div class="uk-grid">
                    <div class="uk-width-1-1">
                        <div class="content-svg">


                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="md-card uk-margin-medium-bottom" style="display: none" id="tax">
            <div class="md-card-toolbar">
                <div class="md-card-toolbar-heading-text">
                    @lang('acc_index.tax')
                </div>
                @include('action.toolbar_3')
            </div>
            <div class="md-card-content">
                <div class="uk-grid">
                    <div class="uk-width-1-1">
                        <div class="content-svg">


                        </div>
                    </div>

                </div>

            </div>
        </div>
       </div>
   </div>
@endsection
@push('js_up')

@endpush

@push('js_down')
<!-- page specific plugins -->
    <!-- d3 -->
    <script src="{{ asset('library/d3/d3.min.js') }}"></script>
    <!-- metrics graphics (charts) -->
    <script src="{{ asset('library/metrics-graphics/dist/metricsgraphics.min.js') }}"></script>
    <!-- chartist (charts) -->
    <script src="{{ asset('library/chartist/dist/chartist.min.js') }}"></script>
    <!-- maplace (google maps) -->
    <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
    <script src="{{ asset('library/maplace-js/dist/maplace.min.js') }}"></script>
    <!-- peity (small charts) -->
    <script src="{{ asset('library/peity/jquery.peity.min.js') }}"></script>
    <!-- easy-pie-chart (circular statistics) -->
    <script src="{{ asset('library/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js') }}"></script>
    <!-- countUp -->
    <script src="{{ asset('library/countUp.js/countUp.min.js') }}"></script>
    <!-- handlebars.js -->
    <script src="{{ asset('library/handlebars/handlebars.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/handlebars_helpers.min.js') }}"></script>
    <!-- CLNDR -->
    <script src="{{ asset('library/clndr/src/clndr.js') }}"></script>
    <!-- fitvids -->
    <script src="{{ asset('library/fitvids/jquery.fitvids.js') }}"></script>

    <!--  dashbord functions -->
    <script src="{{ asset('assets/js/pages/dashboard.min.js') }}"></script>
    <script src="{{ asset('addon/scripts/acc/index.js') }}"></script>
    <script src="{{ asset('library/lazyload/lazyload.js') }}"></script>
@endpush
