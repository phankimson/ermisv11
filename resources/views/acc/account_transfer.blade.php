@extends('form.ermis_form_2')

@push('css_up')
 <link rel="stylesheet" href="{{ asset('library/handsontable/dist/handsontable.min.css') }}" media="all">
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
   <ul class="uk-tab" data-uk-tab="{connect:'#tabs_anim', animation:'slide-left', swiping: false}">
       <li class="uk-active"><a href="javascript:;">@lang('acc_account_transfer.info')</a></li>
       <li><a href="javascript:;">@lang('global.expand')</a></li>
   </ul>
   <section id="tabs_anim" class="uk-switcher uk-margin">
       <div>
           <table>
               <tr>
                   <td class="row-label"><label>@lang('acc_account_transfer.code') *</label></td>
                   <td><input type="text" class="k-textbox medium" data-position="1" data-title="@lang('acc_account_transfer.code')" maxlength="50" data-width="200px" data-type="string" name="code" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_account_transfer.name') *</label></td>
                   <td><input type="text" class="k-textbox large" data-position="2" data-title="@lang('acc_account_transfer.name')" maxlength="100" data-width="200px" data-type="string" name="name" /></td>
               </tr>
                <tr>
                   <td class="row-label"><label>@lang('acc_account_transfer.name_en') *</label></td>
                   <td><input type="text" class="k-textbox large" data-position="3" data-title="@lang('acc_account_transfer.name_en')" maxlength="100" data-width="200px" data-type="string" name="name_en" /></td>
               </tr>
               <tr>
                <td class="row-label"><label>@lang('acc_account_transfer.type')</label></td>
                <td>
                <select class="droplist large" data-position="1" data-title="@lang('acc_account_transfer.type')" data-template="#= FormatDropList(type,'type') #" data-type="number" data-width="200px" name="type">
                        <option readonly selected value="0">--Select--</option>
                        <option value="1">@lang('acc_account_transfer.debit_to_credit')</option>
                        <option value="2">@lang('acc_account_transfer.credit_to_debit')</option>
                </select>
                </td>
                </tr>              
               <tr>
                   <td class="row-label"><label>@lang('acc_account_transfer.debit') </label></td>
                   <td>
                     <select class="droplist read large" data-position="4" data-title="@lang('acc_account_transfer.debit')" data-template="#= FormatDropListRead(debit,'debit') #" data-type="string" data-width="200px" data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.account')}}" name="debit">
                             
                     </select>
                   </td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_account_transfer.credit') </label></td>
                   <td>
                     <select class="droplist read large" data-position="5" data-title="@lang('acc_account_transfer.credit')" data-template="#= FormatDropListRead(credit,'credit') #" data-type="string" data-width="200px" data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.account')}}" name="credit">
                          
                         </select>
                   </td>
               </tr>
               <tr>
                   <td><label>@lang('acc_account_transfer.object')</label></td>
                   <td class="row-height"><input type="checkbox" data-md-icheck="" data-position="10" data-title="@lang('acc_account_transfer.object')" data-value="1" data-width="100px" data-type="string" data-template="#= FormatCheckBox(object) #" name="object" /></td>
               </tr>
                <tr>
                   <td><label>@lang('acc_account_transfer.case_code')</label></td>
                   <td class="row-height"><input type="checkbox" data-md-icheck="" data-position="10" data-title="@lang('acc_account_transfer.case_code')" data-value="1" data-width="100px" data-type="string" data-template="#= FormatCheckBox(case_code) #" name="case_code" /></td>
               </tr>
                <tr>
                   <td><label>@lang('acc_account_transfer.cost_code')</label></td>
                   <td class="row-height"><input type="checkbox" data-md-icheck="" data-position="10" data-title="@lang('acc_account_transfer.cost_code')" data-value="1" data-width="100px" data-type="string" data-template="#= FormatCheckBox(cost_code) #" name="cost_code" /></td>
               </tr>
                 <tr>
                   <td><label>@lang('acc_account_transfer.statistical_code')</label></td>
                   <td class="row-height"><input type="checkbox" data-md-icheck="" data-position="10" data-title="@lang('acc_account_transfer.statistical_code')" data-value="1" data-width="100px" data-type="string" data-template="#= FormatCheckBox(statistical_code) #" name="statistical_code" /></td>
               </tr>
                 <tr>
                   <td><label>@lang('acc_account_transfer.work_code')</label></td>
                   <td class="row-height"><input type="checkbox" data-md-icheck="" data-position="10" data-title="@lang('acc_account_transfer.work_code')" data-value="1" data-width="100px" data-type="string" data-template="#= FormatCheckBox(work_code) #" name="work_code" /></td>
               </tr>
                <tr>
                   <td><label>@lang('acc_account_transfer.department')</label></td>
                   <td class="row-height"><input type="checkbox" data-md-icheck="" data-position="10" data-title="@lang('acc_account_transfer.department')" data-value="1" data-width="100px" data-type="string" data-template="#= FormatCheckBox(department) #" name="department" /></td>
               </tr>
                 <tr>
                    <td class="row-label"><label>@lang('acc_account_transfer.position')</label></td>
                    <td><input type="number" class="k-textbox medium" data-position="3" step="1" min="0" data-title="@lang('acc_account_transfer.position')" data-width="200px" data-type="string" name="position" /></td>
                </tr>
               <tr>
                   <td><label>@lang('action.active')</label></td>
                   <td class="row-height"><input type="checkbox" data-md-icheck="" data-position="10" data-title="@lang('action.active')" data-value="1" data-width="100px" data-type="string" data-template="#= FormatCheckBox(active) #" name="active" /></td>
               </tr>
           </table>
       </div>
       <div>
         <table>

         </table>
       </div>
   </div>
</section>
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
      Ermis.per = <?= json_encode($per);?>;
      Ermis.flag = 1;
      Ermis.link = "{{$key}}";
      Ermis.paging = "{{$paging}}";
      Ermis.page_size = "{{$page_size}}";
      Ermis.export_limit = '{{ env("EXPORT_LIMIT") }}';
      Ermis.fieldload = 'code';
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.image_upload = '#avatar';
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.decimal = "{{$decimal}}";
      Ermis.locales_hot = "{{ app()->getLocale() == 'vi' ? 'vi-VI' : 'en-US' }}";
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "t.code", column:  "@lang('acc_accounted_fast.code')" },
                           {field : "t.name", column:  "@lang('acc_accounted_fast.name')" },
                           {field : "u.name as profession", column:  "@lang('acc_accounted_fast.profession')" },
                           {field : "a.code as debit", column:  "@lang('acc_accounted_fast.debit')" },
                           {field : "b.code as credit", column:  "@lang('acc_accounted_fast.credit')" },
                           {field : "c.name as case_code", column:  "@lang('acc_accounted_fast.case_code')" },
                           {field : "d.name as cost_code", column:  "@lang('acc_accounted_fast.cost_code')" },
                           {field : "e.name as statistical_code", column:  "@lang('acc_accounted_fast.statistical_code')" },
                           {field : "f.name as work_code", column:  "@lang('acc_accounted_fast.work_code')" },
                           {field : "m.name as department", column:  "@lang('acc_accounted_fast.department')" },
                           {field : "n.bank_account as bank_account", column:  "@lang('acc_accounted_fast.bank_account')" },
                           {field : "o.name as subject_debit", column:  "@lang('acc_accounted_fast.subject_debit')" },
                           {field : "p.name as subject_credit", column:  "@lang('acc_accounted_fast.subject_credit')" },
                           {field : "t.active", column:  "@lang('action.active')" }];
  });
  </script>
@endsection
@section('scripts_end')
@if($decimal_symbol === ".")
<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.en-DE.min.js') }}"></script>
<script>kendo.culture('en-DE')</script>
@endif
<script src="{{ url('addon/scripts/ermis/ermis-form-2-page.js') }}"></script>
@endsection
