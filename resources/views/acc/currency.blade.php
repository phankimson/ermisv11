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
   <ul id="uk_tab_modal" class="uk-tab" data-uk-tab>
       <li class="uk-active" data-id ="0"><a href="javascript:;">@lang('acc_currency.info')</a></li>
       <li data-id ="1"><a href="javascript:;">@lang('acc_currency.read')</a></li>
       <li data-id ="2"><a href="javascript:;">@lang('acc_currency.denominations')</a></li>
       <li data-id ="3"><a href="javascript:;">@lang('global.expand')</a></li>
   </ul>
   <section id="tabs_content" style="width : 100%" class="uk-switcher-hot uk-margin">
       <div class="uk-tab-content">
           <table>
               <tr>
                   <td class="row-label"><label>@lang('acc_currency.code') *</label></td>
                   <td>
                     <span class="k-textbox k-space-right medium">
                           <input type="text" data-position="1" data-title="@lang('acc_currency.code')" data-width="200px" maxlength="50" data-type="string" name="code" id="icon-right">
                           <a href="javascript:;" class="k-icon k-i-rotate load">&nbsp;</a>
                     </span>
                   </td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_currency.name') *</label></td>
                   <td><input type="text" class="k-textbox large" data-position="2" data-title="@lang('acc_currency.name')" maxlength="100" data-width="200px" data-type="string" name="name" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_currency.name_en')</label></td>
                   <td><input type="text" class="k-textbox large" data-position="3" data-title="@lang('acc_currency.name_en')" data-width="200px" data-type="string" name="name_en" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_currency.conversion_calculation') *</label></td>
                   <td>
                     <select class="droplist large" data-position="7" data-title="@lang('acc_currency.conversion_calculation')" data-template="#= FormatDropList(conversion_calculation,'conversion_calculation') #" data-type="string" data-width="200px" name="conversion_calculation">
                             <option readonly selected value="0">--Select--</option>
                             <option value="1">:</option>
                             <option value="2">x</option>
                         </select>
                   </td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_currency.rate')</label></td>
                   <td><input type="number" step="0.01" max="100" class="k-textbox large" data-position="3" data-title="@lang('acc_currency.rate')" data-width="200px" data-type="string" name="rate" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_currency.account_bank') *</label></td>
                   <td>
                     <select class="droplist large" data-position="7" data-title="@lang('acc_currency.account_bank')" data-template="#= FormatDropList(account_bank,'account_bank') #" data-type="string" data-width="200px" name="account_bank">
                             <option readonly selected value="0">--Select--</option>
                             @foreach($account as $c)
                               <option value="{{ $c->id }}">{{ $c->code }} - {{ $c->name }}</option>
                             @endforeach
                         </select>
                   </td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_currency.account_cash') *</label></td>
                   <td>
                     <select class="droplist large" data-position="7" data-title="@lang('acc_currency.account_cash')" data-template="#= FormatDropList(account_cash,'account_cash') #" data-type="string" data-width="200px" name="account_cash">
                             <option readonly selected value="0">--Select--</option>
                             @foreach($account as $c)
                               <option value="{{ $c->id }}">{{ $c->code }} - {{ $c->name }}</option>
                             @endforeach
                         </select>
                   </td>
               </tr>
               <tr>
                   <td><label>@lang('action.active')</label></td>
                   <td class="row-height"><input type="checkbox" data-md-icheck="" data-position="10" data-title="@lang('action.active')" data-value="1" data-width="100px" data-type="string" data-template="#= FormatCheckBox(active) #" name="active" /></td>
               </tr>
           </table>
       </div>
       <div class="uk-tab-content">
         <table>
           <tr>
               <td class="row-label"><label>@lang('acc_currency.conversion_rate_vi')</label></td>
               <td><input type="number" step="0.01" class="k-textbox large" data-position="3" value="100" data-title="@lang('acc_currency.conversion_rate_vi')" data-width="200px" data-type="string" name="conversion_rate_vi" /></td>
           </tr>
           <tr>
               <td class="row-label"><label>@lang('acc_currency.currency_1_vi')</label></td>
               <td><input type="text"  class="k-textbox large" data-position="3" data-title="@lang('acc_currency.currency_1_vi')" data-width="200px" data-type="string" name="currency_1_vi" /></td>
           </tr>
           <tr>
               <td class="row-label"><label>@lang('acc_currency.currency_2_vi')</label></td>
               <td><input type="text"  class="k-textbox large" data-position="3" data-title="@lang('acc_currency.currency_2_vi')" data-width="200px" data-type="string" name="currency_2_vi" /></td>
           </tr>
           <tr>
               <td class="row-label"><label>@lang('acc_currency.currency_3_vi')</label></td>
               <td><input type="text"  class="k-textbox large" data-position="3" data-title="@lang('acc_currency.currency_3_vi')" data-width="200px" data-type="string" name="currency_3_vi" /></td>
           </tr>
           <tr>
               <td class="row-label"><label>@lang('acc_currency.conversion_rate_en')</label></td>
               <td><input type="number" step="0.01" class="k-textbox large" data-position="3" value="100" data-title="@lang('acc_currency.conversion_rate_en')" data-width="200px" data-type="string" name="conversion_rate_en" /></td>
           </tr>
           <tr>
               <td class="row-label"><label>@lang('acc_currency.currency_1_en')</label></td>
               <td><input type="text"  class="k-textbox large" data-position="3" data-title="@lang('acc_currency.currency_1_en')" data-width="200px" data-type="string" name="currency_1_en" /></td>
           </tr>
           <tr>
               <td class="row-label"><label>@lang('acc_currency.currency_2_en')</label></td>
               <td><input type="text"  class="k-textbox large" data-position="3" data-title="@lang('acc_currency.currency_2_en')" data-width="200px" data-type="string" name="currency_2_en" /></td>
           </tr>
           <tr>
               <td class="row-label"><label>@lang('acc_currency.currency_3_en')</label></td>
               <td><input type="text"  class="k-textbox large" data-position="3" data-title="@lang('acc_currency.currency_3_en')" data-width="200px" data-type="string" name="currency_3_en" /></td>
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
      Ermis.locales_hot = "{{ app()->getLocale() == 'vi' ? 'vi-VI' : 'en-US' }}";
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "t.code", column:  "@lang('acc_currency.code')" },
                           {field : "t.name", column:  "@lang('acc_currency.name')" },
                           {field : "t.name_en", column:  "@lang('acc_currency.name_en')" },
                           {field : "t.conversion_calculation", column:  "@lang('acc_currency.conversion_calculation')" },
                           {field : "t.rate", column:  "@lang('acc_currency.rate')" },
                           {field : "t.conversion_rate_vi", column:  "@lang('acc_currency.conversion_rate_vi')" },
                           {field : "t.conversion_rate_en", column:  "@lang('acc_currency.conversion_rate_en')" },
                           {field : "t.currency_1_vi", column:  "@lang('acc_currency.currency_1_vi')" },
                           {field : "t.currency_1_en", column:  "@lang('acc_currency.currency_1_en')" },
                           {field : "t.currency_2_vi", column:  "@lang('acc_currency.currency_2_vi')" },
                           {field : "t.currency_2_en", column:  "@lang('acc_currency.currency_2_en')" },
                           {field : "t.currency_3_vi", column:  "@lang('acc_currency.currency_3_vi')" },
                           {field : "t.currency_3_en", column:  "@lang('acc_currency.currency_3_en')" },
                           {field : "a.code as account_bank", column:  "@lang('acc_currency.account_bank')" },
                           {field : "b.code as account_cash", column:  "@lang('acc_currency.account_cash')" },
                           {field : "t.active", column:  "@lang('action.active')" }];
      Ermis.hot_field = "denominations";
      Ermis.ArrayColumn = [{data:'id',title:"@lang('global.column_name')", readOnly:true },
      {data:'price',type: 'numeric',default : 0,numericFormat: {pattern: '0,0',culture: 'de-DE'},title:"@lang('acc_currency.price')",width : ( 0.2 * $(window).width() )},
      {data:'description',renderer : 'my.custom',default : '',title:"@lang('acc_currency.description')",width : ( 0.2 * $(window).width() )}];

  });
  </script>
@endsection
@section('scripts_end')
@if($decimal_symbol === ".")
document.write('<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.de-DE.min.js') }}"></script>')
document.write('<script>kendo.culture('de-DE')</script>')
@endif
@if(app()->getLocale() == 'vi')
document.write('<script src="{{ url('library/handsontable/dist/languages/vi-VI.js') }}"></script>');
@endif
<script src="{{ url('library/handsontable/dist/handsontable.full.min.js') }}"></script>
<script src="{{ url('library/handsontable/dist/numbro/languages.min.js') }}"></script>
<script src="{{ url('addon/scripts/ermis/ermis-form-2-pagehot.js') }}"></script>
@endsection
