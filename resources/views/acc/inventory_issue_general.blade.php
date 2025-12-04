
@extends('form.ermis_form_3')

@push('css_up')

@endpush

@push('action')
@include('action.top_menu_5')
@endpush

@push('toolbar_action')
@include('action.toolbar_4')
@endpush


@section('content_add')
@include('window.window_7')
@include('form.form_search_2',['group' => $group])
<div id="print"></div>
<div class="uk-grid">
        <div class="uk-width-medium-4-4">
            <div id="grid"></div>
        </div>
    </div>
    <div class="uk-grid">
        <div class="uk-width-medium-4-4">
            <div class="search-table-outter" id="grid-detail"></div>
        </div>
    </div>
@endsection

@push('context_action')
@include('action.context_3')
@endpush

@section('scripts_up')
<script>
  jQuery(document).ready(function () {
      Ermis.data = <?= json_encode($data);?>;
      Ermis.per = <?= json_encode($per);?>;
      Ermis.flag = 1;
      Ermis.link = "{{$key}}";
      Ermis.page_size = "{{$page_size}}";
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.decimal = "{{$decimal}}";
      Ermis.group = <?= json_encode($group);?>;;
      Ermis.column_grid = [{ "field" : "id",hidden: true },
                                 { "field" : "voucher_date","title" : "@lang('acc_voucher.voucher_date') ", type:"date" ,template: "#=FormatDate(voucher_date)#" ,width : '150px'},
                                 { "field" : "voucher","title" : "@lang('acc_general.voucher') "  ,width : '150px'},
                                 { "field" : "description","title" : "@lang('acc_general.description') ",width : '200px' },
                                 { "field" : "total_amount","title" : "@lang('acc_general.total_amount') " , template: '#= FormatNumberDecimal(total_amount,{{$decimal}}) #' ,width : '150px'},
                                 { "field" : "active","title" : "@lang('action.active') ",template: '#= FormatCheckBoxBoolean(active)#' ,width : '100px'}];
            Ermis.field = {
                                voucher_date :   {type : "date"},
                                total_amount:    {type:"number" },
                                active:    {type:"boolean" }
                            };
            Ermis.columns    =   [{ "field" : "id",hidden: true },
                                { "field" : "item_code","title" : "@lang('acc_voucher.item_code') " ,"width" : "100px" },
                                { "field" : "item_name","title" : "@lang('acc_voucher.item_name') " ,"width" : "200px" ,aggregates: ['count'], footerTemplate: "<p>@lang('acc_voucher.total_count'): #=count#</p>" },
                                { "field" : "unit","title" : "@lang('acc_voucher.unit') " ,"width" : "100px" },
                                { "field" : "stock_issue","title" : "@lang('acc_voucher.stock_issue') " ,"width" : "100px" },
                                { "field" : "quantity","title" : "@lang('acc_voucher.quantity') " ,"width" : "150px", template: '#= FormatNumberDecimal(quantity,{{$decimal}}) #' ,aggregates: ['sum'] , footerTemplate:"#= FormatNumberDecimal(sum,{{$decimal}}) #"},
                                { "field" : "price","title" : "@lang('acc_voucher.price') " ,"width" : "150px" ,template: '#= FormatNumberDecimal(price,{{$decimal}}) #'},
                                { "field" : "amount","title" : "@lang('acc_voucher.amount') " ,"width" : "150px" ,template: '#= FormatNumberDecimal(amount,{{$decimal}}) #' ,aggregates: ['sum'] , footerTemplate:"#= FormatNumberDecimal(sum,{{$decimal}}) #"},
                                { "field" : "lot_number","title" : "@lang('acc_general.lot_number') " ,"width" : "100px" },
                                { "field" : "contract","title" : "@lang('acc_general.contract') " ,"width" : "100px" },
                                { "field" : "expiry_date","title" : "@lang('acc_voucher.expiry_date') " ,"width" : "100px"} ]

            Ermis.aggregate = [ { field: "item_name", aggregate: "count" },
                                { field: "quantity", aggregate: "sum" },
                                { field: "amount", aggregate: "sum" }];
            Ermis.columns_voucher = [{"field" : "voucher_date","title" : "@lang('acc_voucher.voucher_date')" ,template : "#=FormatDate(voucher_date)#" ,editor: initReadOnlyGrid},
                                    {"field" : "voucher","title" : "@lang('acc_voucher.voucher')",editor: initReadOnlyGrid },
                                    {"field" : "revoucher","title" : "@lang('global.re_voucher')" },
                                    {"field" : "description","title" : "@lang('acc_voucher.description')",editor: initReadOnlyGrid },
                                    {"field" : "total_amount","title" :  "@lang('acc_voucher.total_amount')" ,template: '#= FormatNumberDecimal(total_amount, {{$decimal}} )#' ,editor: initReadOnlyGrid} ];
            Ermis.field_voucher = {
                revoucher :   {field : "revoucher"},
                voucher_date : {field : "voucher_date"},
                voucher : {field : "voucher"},
                description : {field : "description"},
                total_amount : {field : "total_amount"}
            };
                                    
      Ermis.action = <?= json_encode($action);?>;
  });
  </script>
@endsection
@section('scripts_end')
@if($decimal_symbol === ".")
<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.en-DE.min.js') }}"></script>
<script>kendo.culture('en-DE')</script>
@endif
<script src="{{ url('addon/scripts/ermis/ermis-form-4-general.js') }}"></script>
@endsection
