
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
            <td class="row-label"><label>@lang('software.name') *</label></td>
            <td><input type="text" class="k-textbox xxlarge" data-position="1" data-title="@lang('software.name')" maxlength="100" data-width="200px" data-type="string" name="name" /></td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('software.name_en')</label></td>
            <td><input type="text" class="k-textbox xxlarge" data-position="2" data-title="@lang('software.name_en')" data-width="200px" data-type="string" name="name_en" /></td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('software.url') *</label></td>
            <td><input type="text" class="k-textbox large" data-position="4" data-title="@lang('software.url')" maxlength="60" data-width="200px" data-type="string" name="url" /></td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('software.database_temp')</label></td>
            <td><input type="text" class="k-textbox large" data-position="4" data-title="@lang('software.database_temp')" maxlength="100" data-width="200px" data-type="string" name="database_temp" /></td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('software.username_temp')</label></td>
            <td><input type="text" class="k-textbox large" data-position="4" data-title="@lang('software.username_temp')" maxlength="100" data-width="200px" data-type="string" name="username_temp" /></td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('software.password_temp')</label></td>
            <td><input type="password" class="k-textbox large" data-position="4" data-hidden="true" maxlength="100" data-width="200px" data-type="string" name="password_temp" /></td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('software.note')</label></td>
            <td><textarea type="text" class="k-textbox large" data-position="5" data-title="@lang('software.note')" data-width="200px" data-type="string" name="note" /></textarea></td>
        </tr>
        <tr>
           <td class="row-label"><label>@lang('software.image')</label></td>
           <td class="row-height">
               <input type="file" data-title="@lang('software.image')" data-width="120px"  data-position="3" data-template="#= FormatImageTooltip(image) #" id="image" aria-label="files" name="image" />
               <img id="image_preview" class="max-width-100" src="{{ url('addon/img/placehold/100.png') }}" alt="Image Preview" />
           </td>
        </tr>
        <tr>
            <td><label>@lang('action.active')</label></td>
            <td class="row-height"><input type="checkbox" data-md-icheck="" data-position="10" data-value="1"  data-title="@lang('action.active')" data-width="100px" data-type="number" data-template="#= FormatCheckBox(active) #" name="active" /></td>
        </tr>
    </table>

      <script id="template_img" type="text/x-kendo-template">
      <img class="img-thumbnail" alt="Ermis" src="#=value#">
      </script>
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
      Ermis.image_upload = '#image';
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "t.name", column:  "@lang('software.name')" },
                           {field : "t.name_en", column:  "@lang('software.name_en')" },
                           {field : "t.image", column:  "@lang('software.image')" },
                           {field : "t.url", column:  "@lang('software.url')" },
                           {field : "t.database_temp", column:  "@lang('software.database_temp')" },
                           {field : "t.username_temp", column:  "@lang('software.username_temp')" },
                           {field : "t.password_temp", column:  "@lang('software.password_temp')" },
                           {field : "t.note", column:  "@lang('software.note')"},
                           {field : "t.active", column:  "@lang('action.active')" }];
  });
  </script>
@endsection
@section('scripts_end')
<script src="{{ url('addon/scripts/ermis/ermis-form-1-scrollup.js') }}"></script>
@endsection
