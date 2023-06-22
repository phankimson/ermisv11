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
                   <td class="row-label"><label>@lang('acc_print_template.menu')</label></td>
                   <td>
                     <select class="droplist large" data-position="1" data-title="@lang('acc_print_template.menu')" data-template="#= FormatDropList(menu,'menu') #" data-type="number" data-width="200px" name="menu">
                             <option readonly selected value="0">--Select--</option>
                               @foreach($menu as $m)
                                  <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                               @endforeach
                     </select>
                   </td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_print_template.code') *</label></td>
                   <td><input type="text" class="k-textbox medium" data-position="3" data-title="@lang('acc_print_template.code')" maxlength="50" data-width="200px" data-type="string" name="code" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_print_template.name') *</label></td>
                   <td><input type="text" class="k-textbox large" data-position="4" data-title="@lang('acc_print_template.name')" maxlength="100" data-width="200px" data-type="string" name="name" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_print_template.name_en') *</label></td>
                   <td><input type="text" class="k-textbox large" data-position="4" data-title="@lang('acc_print_template.name_en')" maxlength="100" data-width="200px" data-type="string" name="name_en" /></td>
               </tr>
               <tr>
                 <td><label>@lang('acc_print_template.date_print') *</label></td>
                 <td><input type="text" id="start" class="k-widget k-datepicker k-header k-textbox" data-position="8" data-title="@lang('acc_print_template.date_print')" data-template="#= FormatDate(date_print) #" data-width="200px" data-type="date" name="date_print" /></td>
             </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_print_template.content')</label></td>
                   <td><textarea type="text" class="k-textbox editor" style="width:600px" data-position="8" data-title="@lang('acc_print_template.content')" data-hidden="true" data-type="string" name="content" /></textarea></td>
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
      Ermis.data = <?= json_encode($data);?>;
      Ermis.per = <?= json_encode($per);?>;
      Ermis.flag = 1;
      Ermis.link = "{{$key}}";
      Ermis.page_size = "{{$page_size}}";
      Ermis.fieldload = 'keygen';
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.decimal = "{{$decimal}}";
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "t.code", column:  "@lang('acc_print_template.code')" },
                           {field : "t.name", column:  "@lang('acc_print_template.name')" },
                           {field : "t.name_en", column:  "@lang('acc_print_template.name_en')" },
                           {field : "m.name as menu", column:  "@lang('acc_print_template.menu')" },
                           {field : "t.date_print", column:  "@lang('acc_print_template.date_print')" },
                           {field : "t.content", column:  "@lang('acc_print_template.content')" },
                           {field : "t.active", column:  "@lang('action.active')" }];
  });
  </script>
@endsection
@section('scripts_end')
@if($decimal_symbol === ".")
document.write('<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.de-DE.min.js') }}"></script>')
document.write('<script>kendo.culture('de-DE')</script>')
@endif
<script src="{{ url('addon/scripts/ermis/ermis-form-2-page.js') }}"></script>
@endsection
