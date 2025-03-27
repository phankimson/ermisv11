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
          <td class="row-label"><label>@lang('area.regions')</label></td>
          <td><select class="droplist read large" data-position="1" data-title="@lang('area.regions')" data-template="#= FormatDropListRead(regions,'regions') #" data-type="number" data-width="200px" data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.manage.'.env('URL_DROPDOWN').'.regions')}}" name="regions">
              </select>
          </td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('area.code') *</label></td>
          <td><input type="text" class="k-textbox medium" data-position="2" data-title="@lang('area.code')" maxlength="50" data-width="200px" maxlength="50" data-type="string" name="code" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('area.name') *</label></td>
          <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('area.name')" maxlength="100" data-width="200px" maxlength="100" data-type="string" name="name" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('area.name_en') *</label></td>
          <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('area.name_en')" maxlength="100" data-width="200px" maxlength="100" data-type="string" name="name_en" /></td>
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
      Ermis.paging = "{{$paging}}";
      Ermis.per = <?= json_encode($per);?>;
      Ermis.flag = 1;
      Ermis.link = "{{$key}}";
      Ermis.page_size = "{{$page_size}}";
      Ermis.export_limit = '{{ env("EXPORT_LIMIT") }}';
      Ermis.fieldload = '';
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "m.name as regions", column:  "@lang('area.regions')" },
                           {field : "t.code", column:  "@lang('area.code')" },
                           {field : "t.name", column:  "@lang('area.name')" },
                           {field : "t.name_en", column:  "@lang('area.name_en')" },
                           {field : "t.active", column:  "@lang('action.active')" }];
  });
  </script>
@endsection
@section('scripts_end')
<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.en-DE.min.js') }}"></script>
<script>kendo.culture('en-DE')</script>
<script src="{{ url('addon/scripts/ermis/ermis-form-1-scrolldf.js') }}"></script>
@endsection
