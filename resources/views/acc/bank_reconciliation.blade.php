
@extends('form.ermis_form_5')

@push('css_up')

@endpush

@push('action')
@include('action.top_menu_8')
@endpush

@push('toolbar_action')
@include('action.toolbar_8')
@endpush

@section('form_window')
 @include('window.window_9')   
@endsection

@section('content_add')

@endsection

@push('context_action')
@include('action.context_8')
@endpush

@section('tabs')

@endsection
@section('scripts_up')

@endsection
@section('scripts_end')
@if($decimal_symbol === ".")
<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.en-DE.min.js') }}"></script>
<script>kendo.culture('en-DE')</script>
@endif
<script id="template-dialog" type="text/x-kendo-template">
  <form id="import-form" enctype="multipart/form-data" role="form" method="post"><div class="uk-width-medium-4-4"> <span>@lang('acc_voucher.bank_account') :</span><select class="droplist read large" data-title="@lang('acc_voucher.bank_account')" data-nullable="true" data-type="string" data-width="200px" data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.bank-account')}}" name="bank_account"></select></div></br><input name="files" id="files" type="file" /></form>
</script>
<script>
  jQuery(document).ready(function () { 
      Ermis.data = [];
      Ermis.per = <?= json_encode($per);?>;
      Ermis.flag = 1;
      Ermis.link = "{{$key}}";
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.columns_tab1 = [ {  "title": 'Select All',headerTemplate: '<input type="checkbox" id="header-chb-tab1" class="k-checkbox"><label class="k-checkbox-label" for="header-chb-tab1"></label>',template: function(dataItem){                   
                                    return '<input type="checkbox" id="'+ dataItem.id+'" '+dataItem.checkbox+'  class="k-checkbox tab1"><label class="k-checkbox-label" for="'+ dataItem.id +'"></label>'
                                },width: 50},
                                {"field" :"id", hidden : true},
                                {"field" : "voucher_date","title" : "@lang('acc_voucher.voucher_date')" ,template : "#=FormatDate(voucher_date)#", footerTemplate: "@lang('acc_voucher.total_amount')" },
                                {"field" : "voucher","title" : "@lang('acc_voucher.voucher')" },
                                {"field" : "description","title" : "@lang('acc_voucher.description')",aggregates: ['count'] },
                                {"field" : "debit_amount","title" :  "@lang('acc_voucher.debit_amount')" ,template: '#= FormatNumberDecimal(debit_amount, {{$decimal}} )#',aggregates: ['sum'] , footerTemplate:"#= FormatNumberDecimal(sum,{{$decimal}}) #"},
                                {"field" : "credit_amount","title" :  "@lang('acc_voucher.credit_amount')" ,template: '#= FormatNumberDecimal(credit_amount, {{$decimal}} )#',aggregates: ['sum'] , footerTemplate:"#= FormatNumberDecimal(sum,{{$decimal}}) #" },
                                {"field" : "subject","title" :  "@lang('acc_voucher.subject')" } ];

        Ermis.columns_tab2 = [ {  "title": 'Select All',headerTemplate: '<input type="checkbox" id="header-chb-tab2" class="k-checkbox"><label class="k-checkbox-label" for="header-chb-tab2"></label>',template: function(dataItem){                   
                                    return '<input type="checkbox" id="'+ dataItem.id+'" '+dataItem.checkbox+'  class="k-checkbox tab2"><label class="k-checkbox-label" for="'+ dataItem.id +'"></label>'
                                },width: 50},
                                {"field" :"id", hidden : true},
                                {"field" : "voucher_date","title" : "@lang('acc_voucher.voucher_date')" ,template : "#=FormatDate(voucher_date)#" , footerTemplate: "@lang('acc_voucher.total_amount')" },
                                {"field" : "description","title" : "@lang('acc_voucher.description')",aggregates: ['count'],aggregates: ['sum'] , footerTemplate:"#= count #" },
                                {"field" : "debit_amount","title" :  "@lang('acc_voucher.debit_amount')" ,template: '#= FormatNumberDecimal(debit_amount, {{$decimal}} )#',aggregates: ['sum'] ,footerTemplate:"#= FormatNumberDecimal(sum,{{$decimal}}) #" },
                                {"field" : "credit_amount","title" :  "@lang('acc_voucher.credit_amount')" ,template: '#= FormatNumberDecimal(credit_amount, {{$decimal}} )#',aggregates: ['sum'] ,footerTemplate:"#= FormatNumberDecimal(sum,{{$decimal}}) #" },
                                {"field" : "subject","title" :  "@lang('acc_voucher.subject')" },
                                {"field" : "action","title" :  "@lang('action.processed')" } ];
      Ermis.field = {
            checkbox:     {field : "checkbox",defaultValue: ""},
            id : {field :"id",editable: false},
            voucher_date:     {field : "voucher_date",editable: false},
            voucher:     {field : "voucher",editable: false},
            description:     {field : "description",editable: false},
            amount_debit:     {field : "amount_debit",editable: false,type:"number" , defaultValue : 0 ,editable: false},
            amount_credit:     {field : "amount_credit",editable: false,type:"number" , defaultValue : 0 ,editable: false},
            subject:     {field : "subject", editable: false},                     
        };
        Ermis.aggregate = [ { field: "description", aggregate: "count" },
                            { field: "debit_amount", aggregate: "sum" },
                            { field: "credit_amount", aggregate: "sum" }]
   });
  </script>
<script src="{{ url('addon/scripts/ermis/ermis-form-7-reconciliation.js') }}"></script>
@endsection
