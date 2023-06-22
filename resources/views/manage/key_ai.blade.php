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
          <td class="row-label"><label>@lang('key_ai.code') *</label></td>
          <td><input type="text" class="k-textbox medium" data-position="2" data-title="@lang('key_ai.code')" maxlength="50" data-width="200px"data-type="string" name="code" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('key_ai.name') *</label></td>
          <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('key_ai.name')" maxlength="100" data-width="200px" data-type="string" name="name" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('key_ai.name_en') *</label></td>
          <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('key_ai.name_en')" maxlength="100" data-width="200px" data-type="string" name="name_en" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('key_ai.count') *</label></td>
          <td><input type="number" class="k-textbox large" data-position="4" data-title="@lang('key_ai.count')" maxlength="11" data-width="100px" data-type="number" name="count" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('key_ai.content') *</label></td>
          <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('key_ai.content')" maxlength="100" data-width="200px"  data-type="string" name="content" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('key_ai.field') *</label></td>
          <td><input type="text" class="k-textbox large" data-position="3" data-title="@lang('key_ai.field')" maxlength="50" data-width="200px" data-type="string" name="field" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('key_ai.crit')</label></td>
          <td><input type="text" class="k-textbox xlarge" data-position="3" data-title="@lang('key_ai.crit')" maxlength="80" data-width="200px" data-type="string" name="crit" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('key_ai.crit_en')</label></td>
          <td><input type="text" class="k-textbox xlarge" data-position="3" data-title="@lang('key_ai.crit_en')" maxlength="80" data-width="200px" data-type="string" name="crit_en" /></td>
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
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "t.code", column:  "@lang('key_ai.code')" },
                           {field : "t.name", column:  "@lang('key_ai.name')" },
                           {field : "t.name_en", column:  "@lang('key_ai.name_en')" },
                           {field : "t.field", column:  "@lang('key_ai.field')" },
                           {field : "t.crit", column:  "@lang('key_ai.crit')" },
                           {field : "t.crit_en", column:  "@lang('key_ai.crit_en')" },
                           {field : "t.content", column:  "@lang('key_ai.content')" },
                           {field : "t.active", column:  "@lang('action.active')" }];
  });
  </script>
@endsection
@section('scripts_end')
<script src="{{ url('addon/scripts/ermis/ermis-form-1-scrolldf.js') }}"></script>
@endsection
