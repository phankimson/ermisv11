
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
<li class="uk-active"><a href="javascript:;">@lang('acc_open_balance.account')</a></li>
<li><a href="javascript:;">@lang('acc_open_balance.bank')</a></li>
<li><a href="javascript:;">@lang('acc_open_balance.goods')</a></li>
<li><a href="javascript:;">@lang('acc_open_balance.materials')</a></li>
<li><a href="javascript:;">@lang('acc_open_balance.upfront_costs')</a></li>
<li><a href="javascript:;">@lang('acc_open_balance.tools')</a></li>
<li><a href="javascript:;">@lang('acc_open_balance.asset')</a></li>
<li><a href="javascript:;">@lang('acc_open_balance.supplier')</a></li>
<li><a href="javascript:;">@lang('acc_open_balance.customer')</a></li>
<li><a href="javascript:;">@lang('acc_open_balance.employee')</a></li>
<li><a href="javascript:;">@lang('acc_open_balance.other')</a></li>
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
                               {"field" : "name","title" : "@lang('acc_account_systems.name')" },
                               {"field" : "name_en","title" : "@lang('acc_account_systems.name_en')" },
                               {"field" : "parent_id",hidden: true  },
                               {"field" : "debit_amount","title" :  "@lang('acc_voucher.debit_amount')" ,template: '#= FormatNumberDecimal(debit_amount, {{$decimal}} )#' },
                               {"field" : "credit_amount","title" :  "@lang('acc_voucher.credit_amount')" ,template: '#= FormatNumberDecimal(credit_amount, {{$decimal}} )#' }];

      Ermis.fields_account = {
              id : {field :"id",nullable: false, editable : false},
              balance_id : {field : "balance_id", defaultValue: 0},
              code : {field : "code", editable : false},
              name : {field : "name" , editable : false },
              name_en :{field : "name_en" , editable : false },
              parent_id :{field : "parent_id",nullable: true, type:"string" },
              debit_amount :{field : "debit_amount" , type: "number" },
              credit_amount :{field : "credit_amount" , type: "number"},
    },

      Ermis.data_expend = [{field : "t.code", column:  "@lang('group_users.code')" },
                           {field : "t.name", column:  "@lang('group_users.name')" },
                           {field : "t.active", column:  "@lang('action.active')" }];
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
