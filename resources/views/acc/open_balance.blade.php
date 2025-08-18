
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
    <li>Content 1</li>
    <li>Content 2</li>
    <li>Content 3</li>
    <li>Content 4</li>
    <li>Content 5</li>
    <li>Content 6</li>
    <li>Content 7</li>
    <li>Content 8</li>
    <li>Content 9</li>
    <li>Content 10</li>
    <li>Content 11</li>
    <li>Content 12</li>
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
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
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
<script src="{{ url('addon/scripts/ermis/ermis-form-1-scrolltt.js') }}"></script>
@endsection
