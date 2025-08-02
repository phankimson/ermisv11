@extends('form.ermis_form_4')

@push('css_up')

@endpush

@push('action')
@include('action.top_menu_7')
@endpush

@push('toolbar_action')
@include('action.toolbar_6')
@endpush


@section('form_window')
<div id="print"></div>
@include('window.window_2')
@include('window.window_4')
@include('window.window_5')
@include('window.window_6')
@endsection

@section('uktab')
<div class="uk-grid uk-tab uk-width-1-1">
    @foreach($menu_tab as $m)
    <li class={{($m->id== $menu )? 'uk-active' : ''}}><a href="{{url($lang.'/'.$m->link)}}">{{ $lang=='vi'? $m->name : $m->name_en}}</a></li>
    @endforeach
   
    <li class="uk-disabled"><a href="#">@lang('global.expand')</a></li>
</div>      
@endsection

@section('content_add')
<div class="uk-width-medium-4-4 search-table-outter">
    @include('action.content_6')
</div>
@endsection

@section('tabs')
<div id="tabstrip">
    <ul style="display:none">
        <li class="k-state-active">@lang('acc_voucher.detail')</li>
        <li>@lang('global.expand')</li>
    </ul>
    <div><div id="grid"></div> </div>
    <div>
        <table>
            <tr>
                <td class="row-label-responsive"><label>@lang('global.expand')</label></td>
                <td colspan="2"><input type="text" class="k-textbox large" /></td>

                <td class="row-label-responsive"></td>
                <td><label>@lang('global.expand')</label></td>
                <td colspan="2"><input type="text" class="k-textbox large" /></td>
            </tr>

            <tr>
                <td><label>@lang('global.expand')</label></td>
                <td colspan="2"><input type="text" class="k-textbox large" /></td>

                <td class="row-label-responsive"></td>
                <td><label>@lang('global.expand')</label></td>
                <td colspan="2"><input type="text" class="k-textbox large" /></td>
            </tr>

            <tr>
                <td><label>@lang('global.expand')</label></td>
                <td colspan="2"><input type="text" class="k-textbox large" /></td>

                <td class="row-label-responsive"></td>
                <td><label>@lang('global.expand')</label></td>
                <td colspan="2"><input type="text" class="k-textbox large" /></td>
            </tr>

        </table>
    </div>

</div>
<!--<div class="uk-width-medium-4-4">
    <div id="grid"></div>
</div>-->
@endsection

@push('context_action')
@include('action.context_5')
@endpush

@push('context_action_grid')
@include('action.context_6')
@endpush

@section('scripts_up')
<!--<div id="tax_dropdown_list" class="hidden" data-json=""></div>-->
<script>
    jQuery(document).ready(function () {
        Ermis.data = [];
        Ermis.link = "{{$key}}";
        Ermis.type = "{{$menu}}";
        Ermis.page_size = "{{$page_size}}";
        Ermis.page_size_1 = "{{$page_size_1}}";
        Ermis.per = <?= json_encode($per);?>;
        Ermis.short_key = "{{ config('app.short_key')}}";
        Ermis.voucher = <?= json_encode($voucher);?>;
        Ermis.decimal = "{{$decimal}}";
        Ermis.decimal_symbol = "{{$decimal_symbol}}";
        Ermis.columns_voucher = [{"field" : "voucher_date","title" : "@lang('acc_voucher.voucher_date')" ,template : "#=FormatDate(voucher_date)#" },
                                {"field" : "voucher","title" : "@lang('acc_voucher.voucher')" },
                                {"field" : "description","title" : "@lang('acc_voucher.description')" },
                                {"field" : "total_amount","title" :  "@lang('acc_voucher.total_amount')" ,template: '#= FormatNumberDecimal(total_amount, {{$decimal}} )#' } ];

        Ermis.columns_reference = [{title: 'Select All',headerTemplate: '<input type="checkbox" id="header-chb-reference" class="k-checkbox reference"><label class="k-checkbox-label" for="header-chb-reference"></label>',template: function(dataItem){return '<input type="checkbox" id="'+ dataItem.id+'" class="k-checkbox reference"><label class="k-checkbox-label" for="'+ dataItem.id +'"></label>'},width: 80},
                                                {"field" : "voucher_date","title" : "@lang('acc_voucher.voucher_date')",template: '#= FormatDate(voucher_date)#' },
                                                {"field" : "voucher","title" : "@lang('acc_voucher.voucher')" },
                                                {"field" : "description","title" : "@lang('acc_voucher.description')" },
                                                {"field" : "total_amount","title" :  "@lang('acc_voucher.total_amount')" ,template: '#= FormatNumberDecimal(total_amount, {{$decimal}} )#' } ];

        Ermis.columns_barcode = [{title: 'Select All',headerTemplate: '<input type="checkbox" id="header-chb-barcode" class="k-checkbox barcode"><label class="k-checkbox-label" for="header-chb-barcode"></label>',template: function(dataItem){return '<input type="checkbox" id="'+ dataItem.id+'" class="k-checkbox barcode"><label class="k-checkbox-label" for="'+ dataItem.id +'"></label>'},width: 80},
                                                {"field" : "date_voucher","title" : "@lang('acc_voucher.date_voucher')" },
                                                {"field" : "voucher","title" : "@lang('acc_voucher.voucher')" },
                                                {"field" : "description","title" : "@lang('acc_voucher.description')" },
                                                {"field" : "bank_account","title" :  "@lang('acc_voucher.bank_account')" } ]


        Ermis.columns    = [{"field" :"id", hidden : true},
                            { "field" : "accounted_fast","title" :"@lang('acc_voucher.accounted_fast')","url" : "{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.accounted-fast')}}" ,editor: ItemsReadDropDownEditor , "select" : "OnchangeCancel" ,template : "#=getUrlAjaxItemName(accounted_fast,'accounted_fast')#","width" : "150px" ,"set" : "5" },
                            { "field" : "description","title" : "@lang('acc_voucher.description')" ,"width" : "200px" ,aggregates: ['count'], footerTemplate: "<p>@lang('acc_voucher.total_count'): #=count#</p>","set" : "6"  },
                            { "field" : "debit","title" :"@lang('acc_voucher.debt_account')" ,"url" : "{!!route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.account-voucher-filter').'?menu='.$menu.'&type=1&option=true'!!}",editor: ItemsReadDropDownEditor , "select" : "OnchangeItem" ,template : "#=getUrlAjaxItemName(debit,'debit')#" ,"width" : "150px" ,"set" : "2" , "key" :true },
                            { "field" : "credit","title" :"@lang('acc_voucher.credit_account')" ,"url" : "{!!route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.account-voucher-filter').'?menu='.$menu.'&type=2&option=true'!!}",editor: ItemsReadDropDownEditor , "select" : "OnchangeItem" ,template : "#=getUrlAjaxItemName(credit,'credit')#" ,"width" : "150px","set" : "2" , "key" :true},
                            { "field" : "amount","title" : "@lang('acc_voucher.amount')" ,"width" : "200px",format: "{0:n{{$decimal}}}",decimals: "{{$decimal}}" ,aggregates: ['sum'],footerTemplate: "<p id='amount_total'>#=FormatNumberDecimal(sum,{{$decimal}})#</p>" },
                            { "field" : "rate","title" :"@lang('acc_voucher.rate')",format: "{0:n{{$decimal}}}",decimals: "{{$decimal}}","width" : "150px"  },
                            { "field" : "amount_rate","title" : "@lang('acc_voucher.amount_rate')" ,"width" : "200px",format: "{0:n{{$decimal}}}",decimals: "{{$decimal}}" ,aggregates: ['sum']  ,footerTemplate: "<p id='amount_rate_total'>#=calculateTotalRateAggregate({{$decimal}})#</p>" },
                           ];


        Ermis.field = {
            id : {field :"id" ,defaultValue: 0},
            description:     {field : "description"},
            amount:     {field : "amount",type:"number" , defaultValue : 0 , validation: { min: 1, required: true }},
            rate:     {field : "rate",type:"number", defaultValue : parseInt(jQuery(".rate").val()) , validation: { min: 0, required: true }},
            amount_rate:     {field : "amount_rate",type:"number" , defaultValue : 0 , validation: { min: 1, required: true }},           
            debit: { field : "debit", defaultValue: RequestURL("{!!route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.account-voucher-default').'?menu='.$menu.'&type=1'!!}"), validation: { min: 1 ,required: true }},
            credit: { field : "credit", defaultValue: RequestURL("{!!route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.account-voucher-default').'?menu='.$menu.'&type=2'!!}"), validation: { min: 1, required: true }},
            accounted_fast: { field : "accounted_fast",defaultValue: DefaultReadValueField() },
        };

        Ermis.aggregate = [ { field: "description", aggregate: "count" },
                            { field: "amount", aggregate: "sum" },
                            { field: "amount_rate", aggregate: "sum" }];

                                                    
    });
</script>


@endsection
@section('scripts_end')
@if($decimal_symbol === ".")
<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.en-DE.min.js') }}"></script>
<script>kendo.culture('en-DE')</script>
@endif
<script src="{{ url('addon/scripts/ermis/ermis-form-global-detail.js') }}"></script>
<script src="{{ url('addon/scripts/ermis/ermis-form-6-detail-dom-file.js') }}"></script>
@endsection
