
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
          <td class="row-label"><label>@lang('acc_setting_account_group.code') *</label></td>
          <td><input type="text" class="k-textbox medium" data-position="1" data-title="@lang('acc_setting_account_group.code')" maxlength="50" data-width="200px" data-type="string" name="code" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('acc_setting_account_group.name') *</label></td>
          <td><input type="text" class="k-textbox large" data-position="2" data-title="@lang('acc_setting_account_group.name')" maxlength="100" data-width="200px" data-type="string" name="name" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('acc_setting_account_group.account_group') *</label></td>
          <td><input type="text" class="k-textbox large" data-position="2" data-title="@lang('acc_setting_account_group.account_group')" maxlength="100" data-width="200px" data-type="string" name="account_group" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('acc_setting_account_group.account_filter') </label></td>
          <td>
           <select class="multiselect large" data-position="7" data-hidden="true" name="account_filter" data-type="arr" multiple="multiple" data-placeholder="Select">
                    @foreach($account as $c)
                      <option value="{{ $c->id }}">{{ $c->code }} - {{ $c->name }}</option>
                    @endforeach
                </select>
          </td>
      </tr>
      <tr>
          <td><label>@lang('action.active')</label></td>
          <td class="row-height"><input type="checkbox" data-md-icheck="" data-position="10" data-title="@lang('action.active')" data-value="1" data-width="100px" data-type="string" data-template="#= FormatCheckBox(active) #" name="active" /></td>
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
      Ermis.data = <?= json_encode($data);?>;
      Ermis.per = <?= json_encode($per);?>;
      Ermis.flag = 1;
      Ermis.link = "{{$key}}";
      Ermis.page_size = "{{$page_size}}";
      Ermis.fieldload = '';
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.decimal = "{{$decimal}}";
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "t.code", column:  "@lang('acc_setting_account_group.code')" },
                           {field : "t.name", column:  "@lang('acc_setting_account_group.name')" },
                           {field : "t.account_group", column:  "@lang('acc_setting_account_group.account_group')" },
                           {field : "t.account_filter", column:  "@lang('acc_setting_account_group.account_filter')" },
                           {field : "t.active", column:  "@lang('action.active')" }];
  });
  </script>
@endsection
@section('scripts_end')
@if($decimal_symbol === ".")
document.write('<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.de-DE.min.js') }}"></script>')
document.write('<script>kendo.culture('de-DE')</script>')
@endif
<script src="{{ url('addon/scripts/ermis/ermis-form-1-scroll.js') }}"></script>
@endsection
