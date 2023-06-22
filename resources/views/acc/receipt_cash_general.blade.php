
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
@include('form.form_search_1')
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
                                { "field" : "description","title" : "@lang('acc_general.description') " ,"width" : "200px" ,aggregates: ['count'], footerTemplate: "<p>Total Count: #=count#</p>" },
                                { "field" : "amount","title" : "@lang('acc_general.amount') " ,"width" : "150px", template: '#= FormatNumberDecimal(amount,{{$decimal}}) #'},
                                { "field" : "rate","title" : "@lang('acc_voucher.rate') " ,"width" : "150px" ,template: '#= FormatNumberDecimal(amount,{{$decimal}}) #'},
                                { "field" : "amount_rate","title" : "@lang('acc_voucher.amount_rate') " ,"width" : "150px" ,template: '#= FormatNumberDecimal(amount,{{$decimal}}) #'},
                                { "field" : "lot_number","title" : "@lang('acc_general.lot_number') " ,"width" : "150px" },
                                { "field" : "contract","title" : "@lang('acc_general.contract') " ,"width" : "150px" },
                                { "field" : "order","title" : "@lang('acc_general.order') " ,"width" : "200px"} ]

            Ermis.aggregate = [ { field: "description", aggregate: "count" },
                                { field: "amount", aggregate: "sum" }];
      Ermis.action = <?= json_encode($action);?>;
  });
  </script>
@endsection
@section('scripts_end')
@if($decimal_symbol === ".")
document.write('<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.de-DE.min.js') }}"></script>')
document.write('<script>kendo.culture('de-DE')</script>')
@endif
<script src="{{ url('addon/scripts/ermis/ermis-form-4-general.js') }}"></script>
@endsection
