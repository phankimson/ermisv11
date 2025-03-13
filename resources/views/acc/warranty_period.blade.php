
@extends('form.ermis_form_1')

@push('css_up')

@endpush

@push('action')
@include('action.top_menu_4')
@endpush

@push('export_extra')
@include('window.export_extra')
@endpush

@push('toolbar_action')
@include('action.toolbar_1')
@endpush
@section('content_add')
    <div id="notification"></div>
    <table>
      <tr>
          <td class="row-label"><label>@lang('acc_warranty_period.code') *</label></td>
          <td>
            <span class="k-textbox k-space-right medium">
                  <input type="text" data-position="1" data-title="@lang('acc_warranty_period.code')" data-width="200px" maxlength="50" data-type="string" name="code" id="icon-right">
                  <a href="javascript:;" class="k-icon k-i-rotate load">&nbsp;</a>
            </span>
          </td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('acc_warranty_period.name') </label></td>
          <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('acc_warranty_period.name')" data-width="200px" maxlength="100" data-type="string" name="name" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('acc_warranty_period.name_en') </label></td>
          <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('acc_warranty_period.name_en')" data-width="200px" maxlength="100" data-type="string" name="name_en" /></td>
      </tr>
        <tr>
            <td><label>@lang('action.active')</label></td>
            <td class="row-height"><input type="checkbox" data-md-icheck="" data-position="10" data-value="1"  data-title="@lang('action.active')" data-width="100px" data-type="number" data-template="#= FormatCheckBox(active) #" name="active" /></td>
        </tr>
    </table>
@endsection

@push('context_action')
@include('action.context_1')
@endpush

@section('tabs')

@endsection
@section('scripts_up')
<script>
  jQuery(document).ready(function () {
      Ermis.per = <?= json_encode($per);?>;
      Ermis.flag = 1;
      Ermis.link = "{{$key}}";
      Ermis.paging = "{{$paging}}";
      Ermis.page_size = "{{$page_size}}";
      Ermis.export_limit = '{{ env("EXPORT_LIMIT") }}';
      Ermis.fieldload = 'code';
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.decimal = "{{$decimal}}";
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "code", column:  "@lang('acc_warranty_period.code')" },
                           {field : "name", column:  "@lang('acc_warranty_period.name')" },
                           {field : "name_en", column:  "@lang('acc_warranty_period.name_en')" },
                           {field : "active", column:  "@lang('action.active')" }];
  });
  </script>
@endsection
@section('scripts_end')
@if($decimal_symbol === ".")
<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.en-DE.min.js') }}"></script>
<script>kendo.culture('en-DE')</script>
@endif
<script src="{{ url('addon/scripts/ermis/ermis-form-1-scroll.js') }}"></script>
@endsection
