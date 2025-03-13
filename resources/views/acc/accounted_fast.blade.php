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
       <li class="uk-active"><a href="javascript:;">@lang('acc_accounted_fast.info')</a></li>
       <li><a href="javascript:;">@lang('global.expand')</a></li>
   </ul>
   <section id="tabs_anim" class="uk-switcher uk-margin">
       <div>
           <table>
               <tr>
                   <td class="row-label"><label>@lang('acc_accounted_fast.code') *</label></td>
                   <td><input type="text" class="k-textbox medium" data-position="1" data-title="@lang('acc_accounted_fast.code')" maxlength="50" data-width="200px" data-type="string" name="code" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_accounted_fast.name') *</label></td>
                   <td><input type="text" class="k-textbox large" data-position="2" data-title="@lang('acc_accounted_fast.name')" maxlength="100" data-width="200px" data-type="string" name="name" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_accounted_fast.debit') </label></td>
                   <td>
                     <select class="droplist read large" data-position="7" data-title="@lang('acc_accounted_fast.debit')" data-template="#= FormatDropListRead(debit,'debit') #" data-type="string" data-width="200px" data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.account')}}" name="debit">
                             
                     </select>
                   </td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_accounted_fast.credit') </label></td>
                   <td>
                     <select class="droplist read large" data-position="7" data-title="@lang('acc_accounted_fast.credit')" data-template="#= FormatDropListRead(credit,'credit') #" data-type="string" data-width="200px" data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.account')}}" name="credit">
                          
                         </select>
                   </td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_accounted_fast.subject_debit') </label></td>
                   <td>
                     <select class="droplist read large" data-position="7" data-title="@lang('acc_accounted_fast.subject_debit')" data-template="#= FormatDropListRead(subject_debit,'subject_debit') #" data-type="string" data-width="200px" data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.object')}}" name="subject_debit">
                     </select>
                   </td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_accounted_fast.subject_credit') </label></td>
                   <td>
                     <select class="droplist read large" data-position="7" data-title="@lang('acc_accounted_fast.subject_credit')" data-template="#= FormatDropListRead(subject_credit,'subject_credit') #" data-type="string" data-width="200px"  data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.object')}}" name="subject_credit">
                
                         </select>
                   </td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_accounted_fast.case_code') </label></td>
                   <td>
                     <select class="droplist read large" data-position="7" data-title="@lang('acc_accounted_fast.case_code')" data-template="#= FormatDropListRead(case_code,'case_code') #" data-type="string" data-width="200px" data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.case-code')}}" name="case_code">
                          
                     </select>
                   </td>
               </tr>

               <tr>
                   <td class="row-label"><label>@lang('acc_accounted_fast.cost_code')</label></td>
                   <td>
                     <select class="droplist read large" data-position="7" data-title="@lang('acc_accounted_fast.cost_code')" data-template="#= FormatDropListRead(cost_code,'cost_code') #" data-type="string" data-width="200px"  data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.cost-code')}}" name="cost_code">
                       
                     </select>
                   </td>
               </tr>

               <tr>
                   <td class="row-label"><label>@lang('acc_accounted_fast.statistical_code')</label></td>
                   <td>
                     <select class="droplist read large" data-position="7" data-title="@lang('acc_accounted_fast.statistical_code')" data-template="#= FormatDropListRead(statistical_code,'statistical_code') #" data-type="string" data-width="200px"  data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.statistical-code')}}"  name="statistical_code">
                           
                     </select>
                   </td>
               </tr>

               <tr>
                   <td class="row-label"><label>@lang('acc_accounted_fast.work_code')</label></td>
                   <td>
                     <select class="droplist read large" data-position="7" data-title="@lang('acc_accounted_fast.work_code')" data-template="#= FormatDropListRead(work_code,'work_code') #" data-type="string" data-width="200px"  data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.work-code')}}" name="work_code">
                             
                     </select>
                   </td>
               </tr>

               <tr>
                   <td class="row-label"><label>@lang('acc_accounted_fast.department') </label></td>
                   <td>
                     <select class="droplist read large" data-position="7" data-title="@lang('acc_accounted_fast.department')" data-template="#= FormatDropListRead(department,'department') #" data-type="string" data-width="200px"  data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.department')}}" name="department">
                          
                     </select>
                   </td>
               </tr>

               <tr>
                   <td class="row-label"><label>@lang('acc_accounted_fast.bank_account') </label></td>
                   <td>
                     <select class="droplist read large" data-position="7" data-title="@lang('acc_accounted_fast.bank_account')" data-template="#= FormatDropListRead(bank_account,'bank_account') #" data-type="string" data-width="200px" data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.bank-account')}}" name="bank_account">
                            
                     </select>
                   </td>
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
