
@extends('form.ermis_form_2')

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
<div class="uk-grid uk-margin-left-10 uk-grid-medium">
   <ul class="uk-tab" data-uk-tab="{connect:'#tabs_anim', animation:'slide-left', swiping: false}">
       <li class="uk-active"><a href="javascript:;">@lang('acc_natural_resources.info')</a></li>
       <li><a href="javascript:;">@lang('global.expand')</a></li>
   </ul>
   <ul id="tabs_anim" class="uk-switcher uk-margin">
       <li>
           <table>
             <tr>
             <td class="row-label"><label>@lang('acc_natural_resources.parent')</label></td>
             <td>
             <select class="droplist large load_droplist"  id="parent_id" add-option="true" data-position="2" data-title="@lang('acc_natural_resources.parent')" data-template="#= FormatDropList(parent_id,'parent_id') #" data-type="number" data-nullable ="true" data-width="200px" name="parent_id">
                     <option readonly selected value="0">--Select--</option>
                       @foreach($parent as $m)
                          <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                       @endforeach
             </select>
             </td>
             </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_natural_resources.code') *</label></td>
                   <td>
                     <span class="k-textbox k-space-right medium">
                           <input type="text" data-position="1" data-title="@lang('acc_natural_resources.code')" data-width="200px" maxlength="50" data-type="string" name="code" id="icon-right">
                           <a href="javascript:;" class="k-icon k-i-rotate load">&nbsp;</a>
                     </span>
                   </td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_natural_resources.name') *</label></td>
                   <td><input type="text" class="k-textbox large" data-position="4" data-title="@lang('acc_natural_resources.name')" maxlength="100" data-width="200px" data-type="string" name="name" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_natural_resources.name_en')</label></td>
                   <td><input type="text" class="k-textbox large" data-position="5" data-title="@lang('acc_natural_resources.name_en')" maxlength="100" data-width="200px" data-type="string" name="name_en" /></td>
               </tr>
               <tr>
               <td class="row-label"><label>@lang('acc_natural_resources.unit')</label></td>
               <td>
               <select class="droplist load_droplist large"  data-position="6" data-title="@lang('acc_natural_resources.unit')" data-hidden="true"  data-template="#= FormatDropList(unit_id,'unit_id') #" data-type="number" data-width="200px" name="unit_id">
                       <option readonly selected value="0">--Select--</option>
                         @foreach($unit as $m)
                            <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                         @endforeach
               </select>
               </td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_natural_resources.natural_tax') </label></td>
                   <td><input type="number" class="k-textbox xxlarge" step="1" max="100" min="0" data-position="3" data-title="@lang('acc_natural_resources.natural_tax')" data-width="200px" maxlength="3" data-type="string" name="natural_tax" /></td>
               </tr>
               <tr>
                   <td><label>@lang('action.active')</label></td>
                   <td class="row-height"><input type="checkbox" data-md-icheck="" data-position="10" data-title="@lang('action.active')" data-value="1" data-width="100px" data-type="string" data-template="#= FormatCheckBox(active) #" name="active" /></td>
               </tr>
           </table>
       </li>
       <li>

       </li>
   </ul>
</div>
<div class="uk-margin" style="float : right">
   <a href="javascript:;" class="k-button k-primary save" data-uk-tooltip title="@lang('action.save')  ({{ config('app.short_key')}}S)"><i class="md-18 material-icons md-color-white">save</i>@lang('action.save')</a>
   <a href="javascript:;" class="k-button k-primary cancel" data-uk-tooltip title="@lang('action.cancel')  ({{ config('app.short_key')}}C)"><i class="md-18 material-icons md-color-white">cancel</i>@lang('action.cancel')</a>
</div>

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
      Ermis.fieldload = 'code';
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.image_upload = '#avatar';
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.decimal = "{{$decimal}}";
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "t.code", column:  "@lang('acc_natural_resources.code')" },
                           {field : "t.name", column:  "@lang('acc_natural_resources.name')" },
                           {field : "t.name_en", column:  "@lang('acc_natural_resources.name_en')" },
                           {field : "t.natural_tax", column:  "@lang('acc_natural_resources.natural_tax')" },
                           {field : "p.name as parent", column:  "@lang('acc_natural_resources.parent')" },
                           {field : "n.name as unit", column:  "@lang('acc_natural_resources.unit')" },
                           {field : "t.active", column:  "@lang('action.active')" }];
  });
  </script>
@endsection
@section('scripts_end')
@if($decimal_symbol === ".")
document.write('<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.de-DE.min.js') }}"></script>')
document.write('<script>kendo.culture('de-DE')</script>')
@endif
<script src="{{ url('addon/scripts/ermis/ermis-form-3-tree.js') }}"></script>
@endsection
