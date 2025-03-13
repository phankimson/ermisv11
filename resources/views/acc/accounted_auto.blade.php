@extends('form.ermis_form_2')

@push('css_up')
 <link rel="stylesheet" href="{{ asset('library/handsontable/dist/handsontable.min.css') }}" media="all">
 <link rel="stylesheet" href="{{ asset('library/chosen/chosen.min.css') }}" media="all">
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
  <ul id="uk_tab_modal" class="uk-tab" data-uk-tab>
      <li class="uk-active" data-id ="0"><a href="javascript:;">@lang('acc_accounted_auto.info')</a></li>
      <li data-id ="1"><a href="javascript:;">@lang('acc_accounted_auto.account')</a></li>
      <li data-id ="4"><a href="javascript:;">@lang('global.expand')</a></li>
  </ul>


   <section id="tabs_content" style="width : 100%" class="uk-switcher-hot uk-margin">
       <div class="uk-tab-content">
           <table>
               <tr>
                   <td class="row-label"><label>@lang('acc_accounted_auto.code') *</label></td>
                   <td>
                     <span class="k-textbox k-space-right medium">
                           <input type="text" data-position="1" data-title="@lang('acc_accounted_auto.code')" data-width="200px" maxlength="50" data-type="string" name="code" id="icon-right">
                           <a href="javascript:;" class="k-icon k-i-rotate load">&nbsp;</a>
                     </span>
                   </td>
               </tr>
                 <tr>
                     <td class="row-label"><label>@lang('acc_accounted_auto.name') *</label></td>
                     <td><input type="text" class="k-textbox large" data-position="2" data-title="@lang('acc_accounted_auto.name')" maxlength="100" data-width="200px" data-type="string" name="name" /></td>
                 </tr>
                 <tr>
                     <td class="row-label"><label>@lang('acc_accounted_auto.name_en')</label></td>
                     <td><input type="text" class="k-textbox large" data-position="4" data-title="@lang('acc_accounted_auto.name_en')" maxlength="100" data-width="200px" data-type="string" name="name_en" /></td>
                 </tr>

                 <tr>
                     <td class="row-label"><label>@lang('acc_accounted_auto.description')</label></td>
                     <td><textarea type="text" class="k-textbox medium" data-position="8" data-title="@lang('acc_accounted_auto.description')" data-width="200px" data-hidden="true" data-type="string" name="description" /></textarea></td>
                 </tr>

                 <tr>
                     <td><label>@lang('action.active')</label></td>
                     <td class="row-height"><input type="checkbox" data-md-icheck="" data-position="14" data-title="@lang('action.active')" data-value="1" data-width="100px" data-type="string" data-template="#= FormatCheckBox(active) #" name="active" /></td>
                 </tr>
           </table>
       </div>
       <div class="uk-tab-content">
           <div id="ermis-hot"></div>
       </div>
       <div class="uk-tab-content">

           <table>

           </table>

       </div>
   </section>
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
<div id="accounted_fast_dropdown_list" class="hidden" data-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.accounted-fast')}}" data-json=""></div>
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
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.data = [];
      Ermis.fieldload = 'code';
      Ermis.decimal = "{{$decimal}}";
      Ermis.locales_hot = "{{ app()->getLocale() == 'vi' ? 'vi-VI' : 'en-US' }}";
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "code", column:  "@lang('acc_accounted_auto.code')" },
                           {field : "name", column:  "@lang('acc_accounted_auto.name')" },
                           {field : "name_en", column:  "@lang('acc_accounted_auto.name_en')" },
                           {field : "description", column:  "@lang('acc_accounted_auto.description')" },
                           {field : "active", column:  "@lang('action.active')" }];
     Ermis.hot_field = "accounted_auto_detail";
     Ermis.ArrayColumn = [{data:'id',title:"@lang('global.column_name')", readOnly:true },
                         //{data:'accounted_fast',editor: 'chosen', chosenOptions: {data: jQuery('#accounted_fast_dropdown_list').data('json')} ,key : "afterChange",renderer: customDropdownRenderer, title:"@lang('acc_voucher.accounted_fast')",width : ( 0.1 * $(window).width() )},
                         {data:'accounted_fast',editor: 'chosen', chosenOptions: {data: initDropDownListAjaxLoad("accounted-fast","{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.accounted-fast')}}")} ,key : "afterChange",renderer: customAjaxDropdownRenderer, title:"@lang('acc_voucher.accounted_fast')",width : ( 0.1 * $(window).width() )},
                         {data:'description',title:"@lang('acc_voucher.description')",width : ( 0.2 * $(window).width() ), set : "1"},
                         {data:'debit',editor: 'chosen', chosenOptions: {data: initDropDownListAjaxLoad("account","{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.account')}}")},key : true ,renderer: customAjaxDropdownRenderer,title:"@lang('acc_voucher.debt_account')",width : ( 0.1 * $(window).width() ) , set : "2"},
                         {data:'credit',editor: 'chosen', chosenOptions: {data: initDropDownListAjaxLoad("account","{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.account')}}")} ,key : true ,renderer: customAjaxDropdownRenderer,title:"@lang('acc_voucher.credit_account')",width : ( 0.1 * $(window).width() ), set : "2"},
                         {data:'subject_debit',editor: 'chosen', chosenOptions: {data: initDropDownListAjaxLoad("object","{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.object')}}")} ,renderer: customAjaxDropdownRenderer,title:"@lang('acc_accounted_fast.subject_debit')",width : ( 0.1 * $(window).width() ) , set : "2"},
                         {data:'subject_credit',editor: 'chosen', chosenOptions: {data: initDropDownListAjaxLoad("object","{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.object')}}")} ,renderer: customAjaxDropdownRenderer,title:"@lang('acc_accounted_fast.subject_credit')",width : ( 0.1 * $(window).width() ), set : "2"},
                         {data:'department',editor: 'chosen', chosenOptions: {data: initDropDownListAjaxLoad("department","{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.department')}}")} ,renderer: customAjaxDropdownRenderer,title:"@lang('acc_voucher.department')",width : ( 0.1 * $(window).width() ), set : "2"},
                         {data:'bank_account',editor: 'chosen', chosenOptions: {data: initDropDownListAjaxLoad("bank-account","{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.bank-account')}}")} ,renderer: customAjaxDropdownRenderer ,title:"@lang('acc_voucher.bank_account')",width : ( 0.1 * $(window).width() ), set : "2"},
                         {data:'cost_code',editor: 'chosen', chosenOptions: {data: initDropDownListAjaxLoad("cost-code","{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.cost-code')}}")} ,renderer: customAjaxDropdownRenderer,title:"@lang('acc_voucher.cost_code')",width : ( 0.1 * $(window).width() ), set : "2"},
                         {data:'case_code',editor: 'chosen', chosenOptions: {data: initDropDownListAjaxLoad("case-code","{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.case-code')}}")} ,renderer: customAjaxDropdownRenderer, title:"@lang('acc_voucher.case_code')",width : ( 0.1 * $(window).width() ), set : "2"},
                         {data:'statistical_code',editor: 'chosen', chosenOptions: {data: initDropDownListAjaxLoad("statistical-code","{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.statistical-code')}}")} ,renderer: customAjaxDropdownRenderer,title:"@lang('acc_voucher.statistical_code')",width : ( 0.1 * $(window).width() ), set : "2"},
                         {data:'work_code',editor: 'chosen', chosenOptions: {data: initDropDownListAjaxLoad("work-code","{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.work-code')}}")} ,renderer: customAjaxDropdownRenderer,title:"@lang('acc_voucher.work_code')",width : ( 0.1 * $(window).width() ), set : "2"}  ];
  });
  </script>
@endsection
@section('scripts_end')
@if($decimal_symbol === ".")
<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.en-DE.min.js') }}"></script>
<script>kendo.culture('en-DE')</script>
@endif
<script src="{{ url('library/handsontable/dist/handsontable.full.min.js') }}"></script>
<script src="{{ url('library/chosen/chosen.jquery.js') }}"></script>
<script src="{{ url('library/handsontable/dist/handsontable-chosen-editor-ajax.js') }}"></script>
<script src="{{ url('library/handsontable/dist/numbro/languages.min.js') }}"></script>
@if(app()->getLocale() == 'vi')
<script src="{{ url('library/handsontable/dist/languages/vi-VI.js') }}"></script>
@endif
<script src="{{ url('addon/scripts/ermis/ermis-form-2-pagehotread.js') }}"></script>
@endsection
