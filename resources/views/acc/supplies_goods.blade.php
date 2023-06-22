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
      <li class="uk-active" data-id ="0"><a href="javascript:;">@lang('acc_supplies_goods.info')</a></li>
      <li data-id ="1"><a href="javascript:;">@lang('acc_supplies_goods.tax_and_account')</a></li>
      <li data-id ="2"><a href="javascript:;">@lang('acc_supplies_goods.discount')</a></li>
      <li data-id ="3"><a href="javascript:;">@lang('acc_supplies_goods.image')</a></li>
      <li data-id ="4"><a href="javascript:;">@lang('global.expand')</a></li>
  </ul>


   <section id="tabs_content" style="width : 100%" class="uk-switcher-hot uk-margin">
       <div class="uk-tab-content">
           <table>
           <tr>
                 <td class="row-label"><label>@lang('acc_supplies_goods.type')</label></td>
                 <td>
                 <select class="droplist large" id="type" data-position="4" data-title="@lang('acc_supplies_goods.type')" data-template="#= FormatDropList(type,'type') #" data-type="number" data-width="200px" name="type">
                         <option readonly selected value="0">--Select--</option>
                           @foreach($sg_type as $m)
                              <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                           @endforeach
                 </select>
                 </td>
                 </tr>
                 
                 <tr>
                     <td class="row-label"><label>@lang('acc_supplies_goods.code') *</label></td>
                     <td>
                       <span class="k-textbox k-space-right medium">
                            <input type="text" data-position="1" data-title="@lang('acc_supplies_goods.code')" data-width="200px" maxlength="50" data-type="string" name="code" id="icon-right">
                            <a href="javascript:;" class="k-icon k-i-rotate load">&nbsp;</a>
                      </span>
                    </td>
                 </tr>
                 <tr>
                     <td class="row-label"><label>@lang('acc_supplies_goods.name') *</label></td>
                     <td><input type="text" class="k-textbox large" data-position="2" data-title="@lang('acc_supplies_goods.name')" maxlength="100" data-width="200px" data-type="string" name="name" /></td>
                 </tr>
                 <tr>
                     <td class="row-label"><label>@lang('acc_supplies_goods.name_en')</label></td>
                     <td><input type="text" class="k-textbox large" data-position="4" data-title="@lang('acc_supplies_goods.name_en')" maxlength="100" data-width="200px" data-type="string" name="name_en" /></td>
                 </tr>

                 <tr>
                     <td class="row-label"><label>@lang('acc_supplies_goods.description')</label></td>
                     <td><textarea type="text" class="k-textbox medium" data-position="8" data-title="@lang('acc_supplies_goods.description')" data-width="200px" data-hidden="true" data-type="string" name="description" /></textarea></td>
                 </tr>

                 <tr>
                 <td class="row-label"><label>@lang('acc_supplies_goods.unit')</label></td>
                 <td>
                 <select class="droplist large" data-position="4" data-title="@lang('acc_supplies_goods.unit')" data-template="#= FormatDropList(unit_id,'unit_id') #" data-type="number" data-width="200px" name="unit_id">
                         <option readonly selected value="0">--Select--</option>
                           @foreach($unit as $m)
                              <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                           @endforeach
                 </select>
                 </td>
                 </tr>
                

                 <tr>
                 <td class="row-label"><label>@lang('acc_supplies_goods.group')</label></td>
                 <td>
                 <select class="droplist large" data-position="4" data-title="@lang('acc_supplies_goods.group')" data-template="#= FormatDropList(group,'group') #" data-type="number" data-width="200px" name="group">
                         <option readonly selected value="0">--Select--</option>
                           @foreach($sg_group as $m)
                              <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                           @endforeach
                 </select>
                 </td>
                 </tr>

                 <tr>
                     <td class="row-label"><label>@lang('acc_supplies_goods.interpretations_buy')</label></td>
                     <td><textarea type="text" class="k-textbox medium" data-position="8" data-title="@lang('acc_supplies_goods.interpretations_buy')" data-width="200px" data-hidden="true" data-type="string" name="interpretations_buy" /></textarea></td>
                 </tr>

                 <tr>
                     <td class="row-label"><label>@lang('acc_supplies_goods.interpretations_sell')</label></td>
                     <td><textarea type="text" class="k-textbox medium" data-position="8" data-title="@lang('acc_supplies_goods.interpretations_sell')" data-width="200px" data-hidden="true" data-type="string" name="interpretations_sell" /></textarea></td>
                 </tr>
                 <tr>
                 <td class="row-label"><label>@lang('acc_supplies_goods.warranty_period')</label></td>
                 <td>
                 <select class="droplist large" data-position="4" data-title="@lang('acc_supplies_goods.warranty_period')" data-template="#= FormatDropList(warranty_period,'warranty_period') #" data-type="number" data-width="200px" name="warranty_period">
                         <option readonly selected value="0">--Select--</option>
                           @foreach($w_p as $m)
                              <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                           @endforeach
                 </select>
                 </td>
                 </tr>

                 <tr>
                     <td class="row-label"><label>@lang('acc_supplies_goods.minimum_stock_quantity')</label></td>
                     <td><input type="number" class="k-textbox large" data-position="3" data-title="@lang('acc_supplies_goods.minimum_stock_quantity')" data-width="200px" data-type="string" name="minimum_stock_quantity" /></td>
                 </tr>

                 <tr>
                     <td class="row-label"><label>@lang('acc_supplies_goods.maximum_stock_quantity')</label></td>
                     <td><input type="number" class="k-textbox large" data-position="3" data-title="@lang('acc_supplies_goods.maximum_stock_quantity')" data-width="200px" data-type="string" name="maximum_stock_quantity" /></td>
                 </tr>
                 <tr>
                     <td class="row-label"><label>@lang('acc_supplies_goods.origin')</label></td>
                     <td><input type="text" class="k-textbox large" data-position="4" data-title="@lang('acc_supplies_goods.origin')" maxlength="100" data-width="200px" data-type="string" name="origin" /></td>
                 </tr>
                 <tr>
                 <td class="row-label"><label>@lang('acc_supplies_goods.stock_default')</label></td>
                 <td>
                 <select class="droplist large" data-position="4" data-title="@lang('acc_supplies_goods.stock_default')" data-template="#= FormatDropList(stock_default,'stock_default') #" data-type="number" data-width="200px" name="stock_default">
                         <option readonly selected value="0">--Select--</option>
                           @foreach($st as $m)
                              <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                           @endforeach
                 </select>
                 </td>
                 </tr>
                 <tr>
                     <td class="row-label"><label>@lang('acc_supplies_goods.percent_purchase_discount')</label></td>
                     <td><input type="number" step="0.01" max="100" class="k-textbox large" data-position="3" data-title="@lang('acc_supplies_goods.percent_purchase_discount')" data-width="200px" data-type="string" name="percent_purchase_discount" /></td>
                 </tr>
                 <tr>
                     <td class="row-label"><label>@lang('acc_supplies_goods.purchase_discount')</label></td>
                     <td><input type="text" class="k-textbox large number-price" data-position="3" data-title="@lang('acc_supplies_goods.purchase_discount')" data-template="#= FormatNumber(purchase_discount) #" data-width="200px" data-type="string" name="purchase_discount" /></td>
                 </tr>
                 <tr>
                     <td class="row-label"><label>@lang('acc_supplies_goods.price_purchase')</label></td>
                     <td><input type="text" class="k-textbox large number-price" data-position="3" data-title="@lang('acc_supplies_goods.price_purchase')" data-template="#= FormatNumber(price_purchase) #"  data-width="200px" data-type="string" name="price_purchase" /></td>
                 </tr>
                 <tr>
                     <td class="row-label"><label>@lang('acc_supplies_goods.price')</label></td>
                     <td><input type="text" class="k-textbox large number-price" data-position="3" data-title="@lang('acc_supplies_goods.price')"  data-template="#= FormatNumber(price) #"  data-width="200px" data-type="string" name="price" /></td>
                 </tr>
                 <tr>
                     <td><label>@lang('action.active')</label></td>
                     <td class="row-height"><input type="checkbox" data-md-icheck="" data-position="14" data-title="@lang('action.active')" data-value="1" data-width="100px" data-type="string" data-template="#= FormatCheckBox(active) #" name="active" /></td>
                 </tr>
           </table>
       </div>
       <div class="uk-tab-content">
         <table>
               <tr>
               <td class="row-label"><label>@lang('acc_supplies_goods.stock_account')</label></td>
               <td>
               <select class="droplist large" data-position="4" data-title="@lang('acc_supplies_goods.stock_account')" data-template="#= FormatDropList(stock_account,'stock_account') #" data-type="number" data-width="200px" name="stock_account">
                       <option readonly selected value="0">--Select--</option>
                         @foreach($s_a as $m)
                            <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                         @endforeach
               </select>
               </td>
               </tr>
               <tr>
               <td class="row-label"><label>@lang('acc_supplies_goods.revenue_account')</label></td>
               <td>
               <select class="droplist large" data-position="4" data-title="@lang('acc_supplies_goods.revenue_account')" data-template="#= FormatDropList(revenue_account,'revenue_account') #" data-type="number" data-width="200px" name="revenue_account">
                       <option readonly selected value="0">--Select--</option>
                         @foreach($r_a as $m)
                            <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                         @endforeach
               </select>
               </td>
               </tr>
               <tr>
               <td class="row-label"><label>@lang('acc_supplies_goods.cost_account')</label></td>
               <td>
               <select class="droplist large" data-position="4" data-title="@lang('acc_supplies_goods.cost_account')" data-template="#= FormatDropList(cost_account,'cost_account') #" data-type="number" data-width="200px" name="cost_account">
                       <option readonly selected value="0">--Select--</option>
                         @foreach($c_a as $m)
                            <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                         @endforeach
               </select>
               </td>
               </tr>
               <tr>
               <td class="row-label"><label>@lang('acc_supplies_goods.vat_tax')</label></td>
               <td>
               <select class="droplist large" data-position="4" data-title="@lang('acc_supplies_goods.vat_tax')" data-template="#= FormatDropList(vat_tax,'vat_tax') #" data-type="number" data-width="200px" name="vat_tax">
                       <option readonly selected value="0">--Select--</option>
                         @foreach($vat_tax as $m)
                            <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                         @endforeach
               </select>
               </td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_supplies_goods.import_tax')</label></td>
                   <td><input type="number" step="0.01" max="100" class="k-textbox large" data-position="3" data-title="@lang('acc_supplies_goods.import_tax')"  maxlength="3"  data-width="200px" data-type="string" name="import_tax" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_supplies_goods.export_tax')</label></td>
                   <td><input type="number" step="0.01" max="100" class="k-textbox large" data-position="3" data-title="@lang('acc_supplies_goods.export_tax')" maxlength="3" data-width="200px" data-type="string" name="export_tax" /></td>
               </tr>

               <tr>
               <td class="row-label"><label>@lang('acc_supplies_goods.excise_tax')</label></td>
               <td>
               <select class="droplist large" data-position="4" data-title="@lang('acc_supplies_goods.vat_tax')" data-template="#= FormatDropList(excise_tax,'excise_tax') #" data-type="number" data-width="200px" name="excise_tax">
                       <option readonly selected value="0">--Select--</option>
                         @foreach($excise_tax as $m)
                            <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                         @endforeach
               </select>
               </td>
               </tr>
         </table>
       </div>
       <div class="uk-tab-content">
           <div id="ermis-hot"></div>
       </div>
       <div class="uk-tab-content">
           <table>
             <tr>
                 <td class="row-label"><label>@lang('acc_supplies_goods.identity')</label></td>
                 <td><textarea type="text" class="k-textbox medium" data-position="8" data-title="@lang('acc_supplies_goods.identity')" data-width="200px" data-hidden="true" data-type="string" name="identity" /></textarea></td>
             </tr>
             <tr>
                <td class="row-label"><label>@lang('acc_supplies_goods.image')</label></td>
                <td class="row-height">
                    <input type="file" data-title="@lang('acc_supplies_goods.image')" data-width="120px"  data-position="3" data-template="#= FormatImageTooltip(image) #" id="image" aria-label="files" name="image" />
                    <img id="image_preview" class="max-width-100" src="{{ url('addon/img/placehold/100.png') }}" alt="Image Preview" />
                </td>
             </tr>
           </table>
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
      Ermis.fieldload_crit = 'type';
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.image_upload = '#image';
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.decimal = "{{$decimal}}";
      Ermis.locales_hot = "{{ app()->getLocale() == 'vi' ? 'vi-VI' : 'en-US' }}";
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "t.code", column:  "@lang('acc_supplies_goods.code')" },
                           {field : "t.name", column:  "@lang('acc_supplies_goods.name')" },
                           {field : "t.name_en", column:  "@lang('acc_supplies_goods.name_en')" },
                           {field : "t.description", column:  "@lang('acc_supplies_goods.description')" },
                           {field : "a.name as unit", column:  "@lang('acc_supplies_goods.unit')" },
                           {field : "b.name as type", column:  "@lang('acc_supplies_goods.type')" },
                           {field : "c.name as 'group'", column:  "@lang('acc_supplies_goods.group')" },
                           {field : "t.interpretations_buy", column:  "@lang('acc_supplies_goods.interpretations_buy')" },
                           {field : "t.interpretations_sell", column:  "@lang('acc_supplies_goods.interpretations_sell')" },
                           {field : "d.name as warranty_period", column:  "@lang('acc_supplies_goods.warranty_period')" },
                           {field : "t.minimum_stock_quantity", column:  "@lang('acc_supplies_goods.minimum_stock_quantity')" },
                           {field : "t.maximum_stock_quantity", column:  "@lang('acc_supplies_goods.maximum_stock_quantity')" },
                           {field : "t.origin", column:  "@lang('acc_supplies_goods.origin')" },
                           {field : "e.name as stock_default", column:  "@lang('acc_supplies_goods.stock_default')" },
                           {field : "f.name as stock_account", column:  "@lang('acc_supplies_goods.stock_account')" },
                           {field : "g.name as revenue_account", column:  "@lang('acc_supplies_goods.revenue_account')" },
                           {field : "h.name as cost_account", column:  "@lang('acc_supplies_goods.cost_account')" },
                           {field : "t.percent_purchase_discount", column:  "@lang('acc_supplies_goods.percent_purchase_discount')" },
                           {field : "t.purchase_discount", column:  "@lang('acc_supplies_goods.purchase_discount')" },
                           {field : "t.price_purchase", column:  "@lang('acc_supplies_goods.price_purchase')" },
                           {field : "t.price", column:  "@lang('acc_supplies_goods.price')" },
                           {field : "j.name as vat_tax", column:  "@lang('acc_supplies_goods.vat_tax')" },
                           {field : "t.import_tax", column:  "@lang('acc_supplies_goods.import_tax')" },
                           {field : "t.export_tax", column:  "@lang('acc_supplies_goods.export_tax')" },
                           {field : "m.name as excise_tax", column:  "@lang('acc_supplies_goods.excise_tax')" },
                           {field : "t.identity", column:  "@lang('acc_supplies_goods.identity')" },
                           {field : "t.active", column:  "@lang('action.active')" }];

           Ermis.hot_field = "discount";
           Ermis.ArrayColumn = [{data:'id',title:"@lang('global.column_name')", readOnly:true },
           {data:'quantity_start',type: 'numeric',default : 0,numericFormat: {pattern: '0,0',culture: 'de-DE'},title:"@lang('acc_supplies_goods.quantity_start')",width : ( 0.1 * $(window).width() )},
           {data:'quantity_end',type: 'numeric',default : 0,numericFormat: {pattern: '0,0',culture: 'de-DE'},title:"@lang('acc_supplies_goods.quantity_end')",width : ( 0.1 * $(window).width() )},
           {data:'amount_discount',type: 'numeric',default : 0,numericFormat: {pattern: '0,0',culture: 'de-DE'},title:"@lang('acc_supplies_goods.amount_discount')",width : ( 0.1 * $(window).width() )},
           {data:'percent_discount',type: 'numeric',default : 0, renderer: 'percent.custom' ,title:"@lang('acc_supplies_goods.percent_discount')",width : ( 0.1 * $(window).width() )} ];
  });
  </script>
@endsection
@section('scripts_end')
@if($decimal_symbol === ".")
document.write('<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.de-DE.min.js') }}"></script>')
document.write('<script>kendo.culture('de-DE')</script>')
@endif
<script src="{{ url('library/handsontable/dist/handsontable.full.min.js') }}"></script>
<script src="{{ url('library/handsontable/dist/numbro/languages.min.js') }}"></script>
@if(app()->getLocale() == 'vi')
document.write('<script src="{{ url('library/handsontable/dist/languages/vi-VI.js') }}"></script>');
@endif
<script src="{{ url('addon/scripts/ermis/ermis-form-2-pageuphot.js') }}"></script>
@endsection
