
@extends('form.ermis_form_1')

@push('css_up')

@endpush

@push('action')
@include('action.top_menu_1')
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
          <td class="row-label"><label>@lang('system.code') *</label></td>
          <td><input type="text" class="k-textbox medium" data-position="2" data-title="@lang('system.code')" data-width="200px" maxlength="50" data-type="string" name="code" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('system.name') *</label></td>
          <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('system.name')" data-width="200px" maxlength="100" data-type="string" name="name" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('system.value') *</label></td>
          <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('system.value')" data-width="200px" maxlength="100" data-type="string" name="value" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('system.value1')</label></td>
          <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('system.value1')" data-width="200px" maxlength="100" data-type="string" name="value1" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('system.value2')</label></td>
          <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('system.value2')" data-width="200px" maxlength="100" data-type="string" name="value2" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('system.value3')</label></td>
          <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('system.value3')" data-width="200px" maxlength="100" data-type="string" name="value3" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('system.value4')</label></td>
          <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('system.value4')" data-width="200px" maxlength="100" data-type="string" name="value4" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('system.value5')</label></td>
          <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('system.value5')" data-width="200px" maxlength="100" data-type="string" name="value5" /></td>
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
      Ermis.data = <?= json_encode($data);?>;
      Ermis.per = <?= json_encode($per);?>;
      Ermis.flag = 1;
      Ermis.link = "{{$key}}";
      Ermis.page_size = "{{$page_size}}";
      Ermis.fieldload = '';
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "code", column:  "@lang('system.code')" },
                           {field : "name", column:  "@lang('system.name')" },
                           {field : "value", column:  "@lang('system.value')" },
                           {field : "value1", column:  "@lang('system.value1')" },
                           {field : "value2", column:  "@lang('system.value2')"},
                           {field : "value3", column:  "@lang('system.value3')"},
                           {field : "value4", column:  "@lang('system.value4')"},
                           {field : "value5", column:  "@lang('system.value5')"},
                           {field : "active", column:  "@lang('action.active')" }];
  });
  </script>
@endsection
@section('scripts_end')
<script src="{{ url('addon/scripts/ermis/ermis-form-1-scrolldf.js') }}"></script>
@endsection
