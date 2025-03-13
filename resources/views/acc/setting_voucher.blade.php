@extends('form.ermis_form_2')

@push('css_up')
 <link rel="stylesheet" href="{{ asset('library/handsontable/dist/handsontable.min.css') }}" media="all">
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
       <li class="uk-active" ><a href="javascript:;">@lang('acc_setting_voucher.info')</a></li>
       <li><a href="javascript:;">@lang('global.expand')</a></li>
   </ul>
   <section id="tabs_anim" class="uk-switcher uk-margin">
       <div>
           <table>
             <tr>
             <td class="row-label"><label>@lang('acc_setting_voucher.menu')</label></td>
             <td>
             <select class="droplist read large" data-position="1" data-title="@lang('acc_setting_voucher.menu')" data-template="#= FormatDropListRead(menu_id,'menu_id') #" data-type="number" data-width="200px" data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.menu')}}"  name="menu_id">                    
             </select>
             </td>
             </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_setting_voucher.code') *</label></td>
                   <td><input type="text" class="k-textbox medium" data-position="1" data-title="@lang('acc_setting_voucher.code')" maxlength="50" data-width="200px" data-type="string" name="code" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_setting_voucher.name') *</label></td>
                   <td><input type="text" class="k-textbox large" data-position="2" data-title="@lang('acc_setting_voucher.name')" maxlength="100" data-width="200px" data-type="string" name="name" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_setting_voucher.debit') </label></td>
                   <td>
                     <select class="droplist read large" data-position="7" data-title="@lang('acc_setting_voucher.debit')" data-template="#= FormatDropListRead(debit,'debit') #" data-type="string" data-width="200px" data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.account')}}" name="debit">
                           
                     </select>
                   </td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_setting_voucher.credit') </label></td>
                   <td>
                     <select class="droplist read large" data-position="7" data-title="@lang('acc_setting_voucher.credit')" data-template="#= FormatDropListRead(credit,'credit') #" data-type="string" data-width="200px" data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.account')}}" name="credit">
                            
                         </select>
                   </td>
               </tr>

               <tr>
                   <td class="row-label"><label>@lang('acc_setting_voucher.vat_account') </label></td>
                   <td>
                     <select class="droplist read large" data-position="7" data-title="@lang('acc_setting_voucher.vat_account')" data-template="#= FormatDropListRead(vat_account,'vat_account') #" data-type="string" data-width="200px" data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.setting-account-group').'?code=VAT'}}" name="vat_account">
                            
                     </select>
                   </td>
               </tr>

               <tr>
                   <td class="row-label"><label>@lang('acc_setting_voucher.discount_account') *</label></td>
                   <td>
                     <select class="droplist read large" data-position="7" data-title="@lang('acc_setting_voucher.discount_account')" data-template="#= FormatDropListRead(discount_account,'discount_account') #" data-type="string" data-width="200px" data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.setting-account-group').'?code=GG'}}" name="discount_account">
                           
                     </select>
                   </td>
               </tr>

               <tr>
                   <td class="row-label"><label>@lang('acc_setting_voucher.debit_filter') </label></td>
                   <td>
                     <select class="multiselect read large" data-position="7" data-type="arr" data-hidden="true" name="debit_filter" multiple="multiple" data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.account').'?multiple=true'}}" data-placeholder="Select">
                           
                      </select>
                   </td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_setting_voucher.credit_filter') </label></td>
                   <td>
                    <select class="multiselect read large" data-position="7" data-type="arr"  data-hidden="true" name="credit_filter" multiple="multiple" data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.account').'?multiple=true'}}" data-placeholder="Select">
                           
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
      Ermis.fieldload = 'keygen';
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.image_upload = '#avatar';
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.decimal = "{{$decimal}}";
      Ermis.locales_hot = "{{ app()->getLocale() == 'vi' ? 'vi-VI' : 'en-US' }}";
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "t.code", column:  "@lang('acc_setting_voucher.code')" },
                           {field : "t.name", column:  "@lang('acc_setting_voucher.name')" },
                           {field : "c.name as menu", column:  "@lang('acc_setting_voucher.menu')" },
                           {field : "a.code as debit", column:  "@lang('acc_setting_voucher.debit')" },
                           {field : "b.code as credit", column:  "@lang('acc_setting_voucher.credit')" },
                           {field : "d.code as vat_account", column:  "@lang('acc_setting_voucher.vat_account')" },
                           {field : "e.code as discount_account", column:  "@lang('acc_setting_voucher.discount_account')" },
                           {field : "t.debit_filter", column:  "@lang('acc_setting_voucher.debit_filter')" },
                           {field : "t.credit_filter", column:  "@lang('acc_setting_voucher.credit_filter')" },
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
