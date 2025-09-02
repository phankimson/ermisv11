
@extends('form.ermis_form_6')

@push('css_up')

@endpush

@push('action')
@include('action.top_menu_9')
@endpush

@push('export_extra')
@include('window.export_extra')
@endpush

@push('toolbar_action')
@include('action.toolbar_9')
@endpush
@section('tab_add')
<li class="uk-active" data-key="account"><a href="javascript:;">@lang('acc_open_balance.account')</a></li>
<li data-key="bank"><a href="javascript:;">@lang('acc_open_balance.bank')</a></li>
<li data-key="goods"><a href="javascript:;">@lang('acc_open_balance.goods')</a></li>
<li data-key="materials"><a href="javascript:;">@lang('acc_open_balance.materials')</a></li>
<li data-key="upfront_costs"><a href="javascript:;">@lang('acc_open_balance.upfront_costs')</a></li>
<li data-key="tools"><a href="javascript:;">@lang('acc_open_balance.tools')</a></li>
<li data-key="asset"><a href="javascript:;">@lang('acc_open_balance.asset')</a></li>
<li data-key="supplier"><a href="javascript:;">@lang('acc_open_balance.supplier')</a></li>
<li data-key="customer"><a href="javascript:;">@lang('acc_open_balance.customer')</a></li>
<li data-key="employee"><a href="javascript:;">@lang('acc_open_balance.employee')</a></li>
<li data-key="other"><a href="javascript:;">@lang('acc_open_balance.other')</a></li>
<li class="uk-disabled"><a href="javascript:;">Disabled</a></li>
@endsection

@section('content_tab_add')
    <li><div id="grid_tab1"></div></li>
    <li><div id="grid_tab2"></div></li>
    <li><div id="grid_tab3"></div></li>
    <li><div id="grid_tab4"></div></li>
    <li><div id="grid_tab5"></div></li>
    <li><div id="grid_tab6"></div></li>
    <li><div id="grid_tab7"></div></li>
    <li><div id="grid_tab8"></div></li>
    <li><div id="grid_tab9"></div></li>
    <li><div id="grid_tab10"></div></li>
    <li><div id="grid_tab11"></div></li>
    <li><div id="grid_tab12"></div></li>
@endsection

@push('context_action')
@include('action.context_9')
@endpush

@section('tabs')

@endsection
@section('scripts_up')
<script>
  jQuery(document).ready(function () {
      Ermis.per = <?= json_encode($per);?>;
      Ermis.flag = 1;
      Ermis.paging = "{{$paging}}";
      Ermis.link = "{{$key}}";
      Ermis.page_size = "{{$page_size}}";
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.export_limit = '{{ env("EXPORT_LIMIT") }}';
      Ermis.fieldload = '';
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.decimal = "{{$decimal}}";

      Ermis.columns_account = [{"field" : "id",hidden: true },
                               {"field" : "balance_id",hidden: true },
                               {"field" : "code","title" : "@lang('acc_account_systems.code')" },
                               {"field" : "name","title" : "@lang('acc_account_systems.name')" ,  footerTemplate: "<p>@lang('acc_voucher.total'):</p>" },
                               {"field" : "name_en","title" : "@lang('acc_account_systems.name_en')" },
                               {"field" : "parent_id",hidden: true  },
                               {"field" : "debit_balance","title" :  "@lang('acc_voucher.debit_balance')" ,format: "{0:n{{$decimal}}}",decimals: "{{$decimal}}" ,template: '#= FormatNumberDecimal(debit_balance, {{$decimal}} )#',aggregates: ['sum'] ,footerTemplate: "#=FormatNumberDecimal(typeof sum !== 'undefined'?sum:0,{{$decimal}})#"},
                               {"field" : "credit_balance","title" :  "@lang('acc_voucher.credit_balance')" ,format: "{0:n{{$decimal}}}",decimals: "{{$decimal}}" ,template: '#= FormatNumberDecimal(credit_balance, {{$decimal}} )#' ,aggregates: ['sum'] ,footerTemplate: "#=FormatNumberDecimal(typeof sum !== 'undefined'?sum:0,{{$decimal}})#"}
                            ];

      Ermis.aggregates = [
          { field: "debit_balance", aggregate: "sum" },
          { field: "credit_balance", aggregate: "sum" }
      ];                      

      Ermis.fields_account = {
              id : {field :"id",nullable: false ,editable : false},
              balance_id : {field : "balance_id", defaultValue: 0 ,editable : false},
              code : {field : "code" ,editable : false},
              name : {field : "name" ,editable : false},
              name_en :{field : "name_en" ,editable : false },
              parent_id :{field : "parent_id",nullable: true, type:"string" ,editable : false},
              debit_balance :{field : "debit_balance" , defaultValue : 0 , type: "number" },
              credit_balance :{field : "credit_balance" , defaultValue : 0 , type: "number" },
    },

     Ermis.columns_bank = [{"field" : "id",hidden: true },
                          {"field" : "balance_id",hidden: true },
                          {"field" : "bank_name","title" : "@lang('acc_bank_account.bank_name')" },
                          {"field" : "bank_account","title" : "@lang('acc_bank_account.bank_account')" ,  footerTemplate: "<p>@lang('acc_voucher.total'):</p>" },
                          {"field" : "bank","title" : "@lang('acc_bank_account.bank')" },
                          {"field" : "branch","title" : "@lang('acc_bank_account.branch')" },
                          {"field" : "account_default","title" : "@lang('acc_bank_account.account_default')" },
                          {"field" : "debit_balance","title" :  "@lang('acc_voucher.debit_balance')" ,format: "{0:n{{$decimal}}}",decimals: "{{$decimal}}" ,template: '#= FormatNumberDecimal(debit_balance, {{$decimal}} )#',aggregates: ['sum'] ,footerTemplate: "#=FormatNumberDecimal(sum,{{$decimal}})#"},
                          {"field" : "credit_balance","title" :  "@lang('acc_voucher.credit_balance')" ,format: "{0:n{{$decimal}}}",decimals: "{{$decimal}}" ,template: '#= FormatNumberDecimal(credit_balance, {{$decimal}} )#' ,aggregates: ['sum'] ,footerTemplate: "#=FormatNumberDecimal(sum,{{$decimal}})#"}
                      ];

      Ermis.fields_bank = {
              id : {field :"id" ,editable : false},
              balance_id : {field : "balance_id", defaultValue: 0 ,editable : false},
              bank_name : {field : "bank_name" ,editable : false},
              bank_account : {field : "bank_account" ,editable : false},
              bank :{field : "bank" ,editable : false },
              branch :{field : "branch" ,editable : false },
              account_default :{field : "account_default" ,editable : false },
              debit_balance :{field : "debit_balance" , defaultValue : 0 , type: "number" },
              credit_balance :{field : "credit_balance" , defaultValue : 0 , type: "number" },
      },
                              
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend_account = [{field : "t.code", column:  "@lang('acc_account_systems.code')" },
                                   {field : "t.name", column:  "@lang('acc_account_systems.name')" },
                                   {field : "t.name_en", column:  "@lang('acc_account_systems.name_en')" },
                                   {field : "s.debit_close", column:  "@lang('acc_voucher.debit_balance')" },
                                   {field : "s.credit_close", column:  "@lang('acc_voucher.credit_balance')" }];

      Ermis.data_expend_bank = [{field : "t.bank_name", column:  "@lang('acc_bank_account.bank_name')" },
                                   {field : "t.bank_account", column:  "@lang('acc_bank_account.bank_account')" },
                                   {field : "c.name as bank", column:  "@lang('acc_bank_account.bank')" },
                                   {field : "t.branch", column:  "@lang('acc_bank_account.branch')" },
                                   {field : "a.code as account_default", column:  "@lang('acc_bank_account.account_default')" },
                                   {field : "s.debit_close", column:  "@lang('acc_voucher.debit_balance')" },
                                   {field : "s.credit_close", column:  "@lang('acc_voucher.credit_balance')" }];                             
  });
  </script>
@endsection
@section('scripts_end')
@if($decimal_symbol === ".")
<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.en-DE.min.js') }}"></script>
<script>kendo.culture('en-DE')</script>
@endif
<script src="{{ url('addon/scripts/ermis/ermis-form-8-balance.js') }}"></script>
@endsection
