
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
            <td class="row-label"><label>@lang('country.code') *</label></td>
            <td><input type="text" class="k-textbox medium" data-position="1" data-title="@lang('country.code')" maxlength="3" data-width="200px" data-type="string" name="code" /></td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('country.name') *</label></td>
            <td><input type="text" class="k-textbox xxlarge" data-position="2" data-title="@lang('country.name')" maxlength="150" data-width="200px" data-type="string" name="name" /></td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('country.phonecode')</label></td>
            <td><input type="number" class="k-textbox large" data-position="4" data-title="@lang('country.phonecode')" maxlength="11" data-width="200px" data-type="string" name="phonecode" /></td>
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
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.fieldload = '';
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "code", column:  "@lang('country.code')" },
                           {field : "name", column:  "@lang('country.name')" },
                           {field : "phonecode", column:  "@lang('country.phonecode')" },
                           {field : "active", column:  "@lang('action.active')" }];
  });
  </script>
@endsection
@section('scripts_end')
<script src="{{ url('addon/scripts/ermis/ermis-form-1-scrolltt.js') }}"></script>
@endsection
