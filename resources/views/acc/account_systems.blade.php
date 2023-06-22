
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
       <li class="uk-active"><a href="javascript:;">@lang('acc_account_systems.info')</a></li>
       <li><a href="javascript:;">@lang('acc_account_systems.detail')</a></li>
       <li><a href="javascript:;">@lang('global.expand')</a></li>
   </ul>
   <ul id="tabs_anim" class="uk-switcher uk-margin">
       <li>
           <table>
             <tr>
             <td class="row-label"><label>@lang('acc_account_systems.type')</label></td>
             <td>
             <select class="droplist large" data-position="2" data-title="@lang('acc_account_systems.type')" data-template="#= FormatDropList(type,'type') #" data-type="number" data-width="200px" name="type">
                     <option readonly selected value="0">--Select--</option>
                       @foreach($type_account as $m)
                          <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                       @endforeach
             </select>
             </td>
             </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_account_systems.code') *</label></td>
                   <td><input type="text" class="k-textbox medium" data-position="1" data-title="@lang('acc_account_systems.code')" maxlength="50" data-width="200px" data-type="string" name="code" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_account_systems.name') *</label></td>
                   <td><input type="text" class="k-textbox large" data-position="4" data-title="@lang('acc_account_systems.name')" maxlength="100" data-width="200px" data-type="string" name="name" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_account_systems.name_en')</label></td>
                   <td><input type="text" class="k-textbox large" data-position="5" data-title="@lang('acc_account_systems.name_en')" maxlength="100" data-width="200px" data-type="string" name="name_en" /></td>
               </tr>
               <tr>
               <td class="row-label"><label>@lang('acc_account_systems.parent')</label></td>
               <td>
               <select class="droplist load_droplist large"  id="parent_id"  data-position="6" data-title="@lang('acc_account_systems.parent')" data-hidden="true"  add-option="true" data-template="#= FormatDropList(parent_id,'parent_id') #" data-type="number" data-nullable ="true"  data-width="200px" name="parent_id">
                       <option readonly selected value="0">--Select--</option>
                         @foreach($parent as $m)
                            <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                         @endforeach
               </select>
               </td>
               </tr>
               <tr>
               <td class="row-label"><label>@lang('acc_account_systems.nature')</label></td>
               <td>
               <select class="droplist large" data-position="7" data-title="@lang('acc_account_systems.nature')" data-template="#= FormatDropList(nature,'nature') #" data-type="number" data-width="200px" name="nature">
                       <option readonly selected value="0">--Select--</option>
                         @foreach($nature as $m)
                            <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                         @endforeach
               </select>
               </td>
               </tr>
               <tr>
                 <td><label>@lang('acc_account_systems.date_start') *</label></td>
                 <td><input type="text" data-type="date" id="start" class="k-widget k-datepicker k-header k-textbox" data-position="8" data-title="@lang('acc_account_systems.date_start')" data-template="#= FormatDate(date_start) #" data-width="200px" data-type="date" name="date_start" /></td>
             </tr>
             <tr>
                 <td><label>@lang('acc_account_systems.date_end') *</label></td>
                 <td><input type="text" data-type="date" id="end" class="k-widget k-datepicker k-header k-textbox" data-position="9" data-title="@lang('acc_account_systems.date_end')" data-template="#= FormatDate(date_end) #" data-width="200px" data-type="date" name="date_end" /></td>
             </tr>
             <tr>
                    <td><label>@lang('acc_account_systems.description')</label></td>
                    <td>
                        <textarea class="k-textbox large" data-title="@lang('acc_account_systems.description')" data-position="6" data-hidden="true" name="description" cols="30" rows="4"></textarea>
                    </td>
                </tr>
               <tr>
                   <td><label>@lang('action.active')</label></td>
                   <td class="row-height"><input type="checkbox" data-md-icheck="" data-position="10" data-title="@lang('action.active')" data-value="1" data-width="100px" data-type="string" data-template="#= FormatCheckBox(active) #" name="active" /></td>
               </tr>
           </table>
       </li>
       <li>
         <table>
              <tr>
                  <td  class="row-label"><label>@lang('acc_account_systems.detail_object')</label></td>
                  <td class="row-height"><input type="checkbox" data-md-icheck="" data-value="0"  data-title="@lang('acc_account_systems.detail_object')"  data-position="8" data-type="string" data-template="#= FormatCheckBox(detail_object) #"  data-hidden="true" name="detail_object" /></td>
              </tr>
              <tr>
                  <td><label>@lang('acc_account_systems.detail_bank_account')</label></td>
                  <td class="row-height"><input type="checkbox" data-md-icheck="" data-value="0"  data-title="@lang('acc_account_systems.detail_bank_account')" data-position="9" data-type="string" data-template="#= FormatCheckBox(detail_bank_account) #"  data-hidden="true" name="detail_bank_account" /></td>
              </tr>
              <tr>
                  <td><label>@lang('acc_account_systems.detail_cost')</label></td>
                  <td class="row-height"><input type="checkbox" data-md-icheck="" data-value="0"   data-title="@lang('acc_account_systems.detail_cost')" data-position="10" data-type="string" data-template="#= FormatCheckBox(detail_cost) #" data-hidden="true" name="detail_cost" /></td>
              </tr>
              <tr>
                  <td><label>@lang('acc_account_systems.detail_case')</label></td>
                  <td class="row-height"><input type="checkbox" data-md-icheck="" data-value="0"   data-title="@lang('acc_account_systems.detail_case')" data-position="10" data-type="string" data-template="#= FormatCheckBox(detail_case) #" data-hidden="true" name="detail_case" /></td>
              </tr>
              <tr>
                  <td><label>@lang('acc_account_systems.detail_work')</label></td>
                  <td class="row-height"><input type="checkbox" data-md-icheck="" data-value="0"  data-title="@lang('acc_account_systems.detail_work')" data-position="11" data-type="string" data-template="#= FormatCheckBox(detail_work) #" data-hidden="true" name="detail_work" /></td>
              </tr>
              <tr>
                  <td><label>@lang('acc_account_systems.detail_statistical')</label></td>
                  <td class="row-height"><input type="checkbox" data-md-icheck="" data-value="0"  data-title="@lang('acc_account_systems.detail_statistical')" data-position="12" data-type="string" data-template="#= FormatCheckBox(detail_statistical) #" data-hidden="true" name="detail_statistical" /></td>
              </tr>
              <tr>
                  <td><label>@lang('acc_account_systems.detail_orders')</label></td>
                  <td class="row-height"><input type="checkbox" data-md-icheck="" data-value="0"   data-title="@lang('acc_account_systems.detail_orders')" data-position="13" data-type="string" data-template="#= FormatCheckBox(detail_orders) #"  data-hidden="true" name="detail_orders" /></td>
              </tr>
              <tr>
                  <td><label>@lang('acc_account_systems.detail_contract')</label></td>
                  <td class="row-height"><input type="checkbox" data-md-icheck="" data-value="0" data-title="@lang('acc_account_systems.detail_contract')" data-position="14" data-type="string" data-template="#= FormatCheckBox(detail_contract) #"  data-hidden="true" name="detail_contract" /></td>
              </tr>
              <tr>
                  <td><label>@lang('acc_account_systems.detail_depreciation')</label></td>
                  <td class="row-height"><input type="checkbox" data-md-icheck="" data-value="0" data-title="@lang('acc_account_systems.detail_depreciation')" data-position="14" data-type="string" data-template="#= FormatCheckBox(detail_depreciation) #"  data-hidden="true" name="detail_depreciation" /></td>
              </tr>
              <tr>
                  <td><label>@lang('acc_account_systems.detail_attribution')</label></td>
                  <td class="row-height"><input type="checkbox" data-md-icheck="" data-value="0" data-title="@lang('acc_account_systems.detail_attribution')" data-position="14" data-type="string" data-template="#= FormatCheckBox(detail_attribution) #"  data-hidden="true" name="detail_attribution" /></td>
              </tr>
          </table>
       </li>
       <li>
         <table>
           <tr>
           <td class="row-label"><label>@lang('acc_account_systems.document')</label></td>
           <td>
           <select class="droplist large" data-position="7" data-title="@lang('acc_account_systems.document')" data-template="#= FormatDropList(document_id,'document_id') #" data-type="number" data-width="200px" name="document_id">
                   <option readonly selected value="0">--Select--</option>
                     @foreach($document as $m)
                        <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                     @endforeach
           </select>
           </td>
           </tr>
         </table>
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
      Ermis.fieldload = 'keygen';
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.image_upload = '#avatar';
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.decimal = "{{$decimal}}";
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "t.code", column:  "@lang('acc_account_systems.code')" },
                           {field : "t.name", column:  "@lang('acc_account_systems.name')" },
                           {field : "t.name_en", column:  "@lang('acc_account_systems.name_en')" },
                           {field : "t.date_start", column:  "@lang('acc_account_systems.date_start')" },
                           {field : "t.date_end", column:  "@lang('acc_account_systems.date_end')" },
                           {field : "t.description", column:  "@lang('acc_account_systems.description')" },
                           {field : "t.detail_object", column:  "@lang('acc_account_systems.detail_object')" },
                           {field : "t.detail_bank_account", column:  "@lang('acc_account_systems.detail_bank_account')" },
                           {field : "t.detail_work", column:  "@lang('acc_account_systems.detail_work')" },
                           {field : "t.detail_cost", column:  "@lang('acc_account_systems.detail_cost')" },
                           {field : "t.detail_statistical", column:  "@lang('acc_account_systems.detail_statistical')" },
                           {field : "t.detail_orders", column:  "@lang('acc_account_systems.detail_orders')" },
                           {field : "t.detail_contract", column:  "@lang('acc_account_systems.detail_contract')" },
                           {field : "t.detail_depreciation", column:  "@lang('acc_account_systems.detail_depreciation')" },
                           {field : "t.detail_attribution", column:  "@lang('acc_account_systems.detail_attribution')" },
                           {field : "a.name as type", column:  "@lang('acc_account_systems.type')" },
                           {field : "p.name as parent", column:  "@lang('acc_account_systems.parent')" },
                           {field : "n.name as nature", column:  "@lang('acc_account_systems.nature')" },
                           {field : "d.name as document", column:  "@lang('acc_account_systems.document')" },
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
