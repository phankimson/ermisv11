@extends('form.ermis_form_2')

@push('css_up')

@endpush

@push('action')
@include('action.top_menu_2')
@endpush

@push('export_extra')
@include('window.export_extra')
@endpush

@push('toolbar_action')
@include('action.toolbar_1')
@endpush
@section('content_add')
<div class="uk-grid uk-margin-left-10 uk-grid-medium">
           <table>
               <tr>
                   <td class="row-label"><label>@lang('document.type')</label></td>
                   <td>
                     <select class="droplist read large" data-position="1" data-title="@lang('document.type')" data-template="#= FormatDropListRead(type,'type') #" data-type="number" data-width="200px"  data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.manage.'.env('URL_DROPDOWN').'.document-type')}}"  name="type">                             
                     </select>
                   </td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('document.code') *</label></td>
                   <td><input type="text" class="k-textbox medium" data-position="3" data-title="@lang('document.code')" maxlength="50" data-width="200px" data-type="string" name="code" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('document.name') *</label></td>
                   <td><input type="text" class="k-textbox large" data-position="4" data-title="@lang('document.name')" maxlength="100" data-width="200px" data-type="string" name="name" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('document.name_en') *</label></td>
                   <td><input type="text" class="k-textbox large" data-position="4" data-title="@lang('document.name_en')" maxlength="100" data-width="200px" data-type="string" name="name_en" /></td>
               </tr>
               <tr>
                 <td><label>@lang('document.date_start') *</label></td>
                 <td><input type="text" id="start" class="k-widget k-datepicker k-header k-textbox" data-position="8" data-title="@lang('document.date_start')" data-template="#= FormatDate(date_start) #" data-width="200px" data-type="date" name="date_start" /></td>
             </tr>
             <tr>
                 <td><label>@lang('document.date_start') *</label></td>
                 <td><input type="text" id="end" class="k-widget k-datepicker k-header k-textbox" data-position="9" data-title="@lang('document.date_end')" data-template="#= FormatDate(date_end) #" data-width="200px" data-type="date" name="date_end" /></td>
             </tr>
               <tr>
                   <td class="row-label"><label>@lang('document.description')</label></td>
                   <td><textarea type="text" class="k-textbox medium" data-position="8" data-title="@lang('document.description')" data-width="200px" data-hidden="true" data-type="string" name="description" /></textarea></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('document.content')</label></td>
                   <td><textarea type="text" class="k-textbox editor" style="width:600px" data-position="8" data-title="@lang('document.content')" data-hidden="true" data-type="string" name="content" /></textarea></td>
               </tr>

               <tr>
                   <td><label>@lang('action.active')</label></td>
                   <td class="row-height"><input type="checkbox" data-md-icheck="" data-position="10" data-title="@lang('action.active')" data-value="1" data-width="100px" data-type="string" data-template="#= FormatCheckBox(active) #" name="active" /></td>
               </tr>
           </table>

</div>
<div class="uk-margin" style="float : right">
   <a href="javascript:;" class="k-button k-primary save" data-uk-tooltip title="@lang('action.save')  ({{ config('app.short_key')}}S)"><i class="md-18 material-icons md-color-white">save</i>@lang('action.save')</a>
   <a href="javascript:;" class="k-button k-primary cancel" data-uk-tooltip title="@lang('action.cancel')  ({{ config('app.short_key')}}C)"><i class="md-18 material-icons md-color-white">cancel</i>@lang('action.cancel')</a>
</div>
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
      Ermis.fieldload = 'keygen';
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "t.code", column:  "@lang('document.code')" },
                           {field : "t.name", column:  "@lang('document.name')" },
                           {field : "t.name_en", column:  "@lang('document.name_en')" },
                           {field : "m.name as type", column:  "@lang('document.type')" },
                           {field : "t.date_start", column:  "@lang('document.date_start')" },
                           {field : "t.date_end", column:  "@lang('document.date_end')" },
                           {field : "t.description", column:  "@lang('document.description')" },
                           {field : "t.content", column:  "@lang('document.content')" },
                           {field : "t.active", column:  "@lang('action.active')" }];
  });
  </script>
@endsection
@section('scripts_end')
<script src="{{ url('addon/scripts/ermis/ermis-form-2-page.js') }}"></script>
@endsection
