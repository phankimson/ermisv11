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
       <li class="uk-active"><a href="javascript:;">@lang('acc_object.info')</a></li>
       <li><a href="javascript:;">@lang('acc_object.contact')</a></li>
       <li><a href="javascript:;">@lang('acc_object.statistics')</a></li>
       <li><a href="javascript:;">@lang('global.expand')</a></li>
   </ul>
   <section id="tabs_anim" class="uk-switcher uk-margin">
       <div>
           <table>
             <tr>
             <td class="row-label"><label>@lang('acc_object.object_type') *</label></td>
             <td>
               <select class="multiselect large" id="object_type" data-position="1" data-hidden="true" data-type="arr" data-filter="true" name="object_type" multiple="multiple" data-placeholder="Select">
                       @foreach($object_type as $c)
                         <option value="{{ $c->id }}">{{ $c->filter }} - {{ $c->code }} - {{ $lang == 'vi'? $c->name : $c->name_en }}</option>
                       @endforeach
                </select>
             </td>
             </tr>
             <tr>
             <td class="row-label"><label>@lang('acc_object.object_group')</label></td>
             <td>
             <select class="droplist large" data-position="1" data-title="@lang('acc_object.object_group')" data-template="#= FormatDropList(object_group,'object_group') #" data-type="number" data-width="200px" name="object_group">
                     <option readonly selected value="0">--Select--</option>
                       @foreach($object_group as $m)
                          <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                       @endforeach
             </select>
             </td>
             </tr>
             <tr>
                 <td class="row-label"><label>@lang('acc_object.level')</label></td>
                 <td><input type="number" class="k-textbox medium" data-position="3" data-title="@lang('acc_object.level')" data-hidden="true" data-width="200px" data-type="string" name="level" /></td>
             </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_object.code') *</label></td>
                   <td>
                     <span class="k-textbox k-space-right medium">
                           <input type="text" data-position="1" data-title="@lang('acc_object.code')" data-width="200px" maxlength="50" data-type="string" name="code" id="icon-right">
                           <a href="javascript:;" class="k-icon k-i-rotate load">&nbsp;</a>
                     </span>
                   </td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_object.name') *</label></td>
                   <td><input type="text" class="k-textbox large" data-position="2" data-title="@lang('acc_object.name')" maxlength="100" data-width="200px" data-type="string" name="name" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_object.name_1')</label></td>
                   <td><input type="text" class="k-textbox large" data-position="4" data-title="@lang('acc_object.name_1')" maxlength="100" data-width="200px" data-type="string" name="name_1" /></td>
               </tr>
               <tr  class="object_type_3 hidden filter_tr">
                   <td class="row-label"><label>@lang('acc_object.identity_card') </label></td>
                    <td><input type="text" class="k-textbox large" data-position="5" data-title="@lang('acc_object.identity_card')" maxlength="50" data-width="200px" data-type="string" name="identity_card" /></td>
               </tr>
               <tr class="object_type_3 hidden filter_tr">
                   <td class="row-label"><label>@lang('acc_object.issued_by_identity_card') </label></td>
                    <td><input type="text" class="k-textbox large" data-position="6" data-title="@lang('acc_object.issued_by_identity_card')" maxlength="50" data-width="200px" data-type="string" name="issued_by_identity_card" /></td>
               </tr>
               <tr  class="object_type_3 hidden filter_tr">
                   <td class="row-label"><label>@lang('acc_object.date_identity_card')</label></td>
                   <td><input type="text" class="k-widget k-datepicker k-header k-textbox date" data-position="7" data-title="@lang('acc_object.date_identity_card')" data-template="#= FormatDate(date_identity_card) #" data-width="200px" data-type="date" name="date_identity_card" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_object.address') </label></td>
                    <td><input type="text" class="k-textbox large" data-position="8" data-title="@lang('acc_object.address')" maxlength="50" data-width="200px" data-type="string" name="address" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_object.email') </label></td>
                    <td><input type="text" class="k-textbox large" data-position="9" data-title="@lang('acc_object.email')" maxlength="50" data-width="200px" data-type="string" name="email" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_object.tax_code') </label></td>
                    <td><input type="text" class="k-textbox large" data-position="10" data-title="@lang('acc_object.tax_code')" maxlength="50" data-width="200px" data-type="string" name="tax_code" /></td>
               </tr>
               <tr class="object_type_1 object_type_2 hidden filter_tr">
                   <td class="row-label"><label>@lang('acc_object.director') </label></td>
                    <td><input type="text" class="k-textbox large" data-position="11" data-title="@lang('acc_object.director')" maxlength="50" data-width="200px" data-type="string" name="director" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('acc_object.phone') </label></td>
                    <td><input type="text" class="k-textbox large" data-position="12" data-title="@lang('acc_object.phone')" maxlength="50" data-width="200px" data-type="string" name="phone" /></td>
               </tr>
               <tr class="object_type_1 object_type_2 hidden filter_tr">
                   <td class="row-label"><label>@lang('acc_object.fax') </label></td>
                    <td><input type="text" class="k-textbox large" data-position="13" data-title="@lang('acc_object.fax')" maxlength="50" data-width="200px" data-type="string" name="fax" /></td>
               </tr>
               <tr class="object_type_3 hidden filter_tr">
               <td class="row-label"><label>@lang('acc_object.department')</label></td>
               <td>
               <select class="droplist large" data-position="13" data-title="@lang('acc_object.department')" data-template="#= FormatDropList(department,'department') #" data-type="number" data-width="200px" name="department">
                       <option readonly selected value="0">--Select--</option>
                         @foreach($department as $m)
                            <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                         @endforeach
               </select>
               </td>
               </tr>
               <tr>
                   <td><label>@lang('action.active')</label></td>
                   <td class="row-height"><input type="checkbox" data-md-icheck="" data-position="14" data-title="@lang('action.active')" data-value="1" data-width="100px" data-type="string" data-template="#= FormatCheckBox(active) #" name="active" /></td>
               </tr>
           </table>
       </div>
       <div>
         <table>
           <tr class="object_type_1 object_type_2 hidden filter_tr">
               <td class="row-label"><label>@lang('acc_object.full_name_contact')</label></td>
               <td><input type="text" class="k-textbox medium" data-position="15" data-title="@lang('acc_object.full_name_contact')" maxlength="50" data-width="200px" data-type="string" name="full_name_contact" /></td>
           </tr>
           <tr class="object_type_1 object_type_2 hidden filter_tr">
               <td class="row-label"><label>@lang('acc_object.address_contact')</label></td>
               <td><input type="text" class="k-textbox medium" data-position="16" data-title="@lang('acc_object.address_contact')" maxlength="50" data-width="200px" data-type="string" name="address_contact" /></td>
           </tr>
           <tr class="object_type_1 object_type_2 hidden filter_tr">
               <td class="row-label"><label>@lang('acc_object.title_contact')</label></td>
               <td><input type="text" class="k-textbox medium" data-position="17" data-title="@lang('acc_object.title_contact')" maxlength="50" data-width="200px" data-type="string" name="title_contact" /></td>
           </tr>
           <tr class="object_type_1 object_type_2 hidden filter_tr">
               <td class="row-label"><label>@lang('acc_object.email_contact')</label></td>
               <td><input type="text" class="k-textbox medium" data-position="18" data-title="@lang('acc_object.email_contact')" maxlength="50" data-width="200px" data-type="string" name="email_contact" /></td>
           </tr>
           <tr class="object_type_1 object_type_2 hidden filter_tr">
               <td class="row-label"><label>@lang('acc_object.telephone1_contact')</label></td>
               <td><input type="text" class="k-textbox medium" data-position="19" data-title="@lang('acc_object.telephone1_contact')" maxlength="50" data-width="200px" data-type="string" name="telephone1_contact" /></td>
           </tr>
           <tr class="object_type_1 object_type_2 hidden filter_tr">
               <td class="row-label"><label>@lang('acc_object.telephone2_contact')</label></td>
               <td><input type="text" class="k-textbox medium" data-position="20" data-title="@lang('acc_object.telephone2_contact')" maxlength="50" data-width="200px" data-type="string" name="telephone2_contact" /></td>
           </tr>
           <tr>
               <td class="row-label"><label>@lang('acc_object.bank_name')</label></td>
               <td><input type="text" class="k-textbox medium" data-position="21" data-title="@lang('acc_object.bank_name')" maxlength="50" data-width="200px" data-type="string" name="bank_name" /></td>
           </tr>
           <tr>
               <td class="row-label"><label>@lang('acc_object.bank_branch')</label></td>
               <td><input type="text" class="k-textbox medium" data-position="22" data-title="@lang('acc_object.bank_branch')" maxlength="50" data-width="200px" data-type="string" name="bank_branch" /></td>
           </tr>
           <tr>
               <td class="row-label"><label>@lang('acc_object.bank_account')</label></td>
               <td><input type="text" class="k-textbox medium" data-position="23" data-title="@lang('acc_object.bank_account')" maxlength="50" data-width="200px" data-type="string" name="bank_account" /></td>
           </tr>
           <tr class="object_type_1 object_type_2 hidden filter_tr">
               <td class="row-label"><label>@lang('acc_voucher.invoice_form') </label></td>
                <td><input type="text" class="k-textbox large" data-position="10" data-title="@lang('acc_voucher.invoice_form')" maxlength="50" data-width="200px" data-type="string" name="invoice_form" /></td>
           </tr>
           <tr class="object_type_1 object_type_2 hidden filter_tr">
               <td class="row-label"><label>@lang('acc_voucher.invoice_symbol') </label></td>
                <td><input type="text" class="k-textbox large" data-position="10" data-title="@lang('acc_voucher.invoice_symbol')" maxlength="50" data-width="200px" data-type="string" name="invoice_symbol" /></td>
           </tr>
         </table>
       </div>
       <div>
           <table>
               <tr>
                   <td><label>@lang('acc_object.country')</label></td>
                   <td>
                       <select class="droplist large" data-position="24" data-title="@lang('acc_object.country')" data-template="#= FormatDropList(country,'country') #" data-hidden="true" data-type="number" data-width="200px" name="country">
                           <option readonly selected value="0">--Select--</option>
                           @foreach($country as $m)
                              <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                           @endforeach
                       </select>
                   </td>
               </tr>
               <tr>
                   <td><label>@lang('acc_object.regions')</label></td>
                   <td>
                       <select class="droplist large" data-position="25" data-title="@lang('company.regions')" data-template="#= FormatDropList(regions,'regions') #" data-hidden="true" data-type="number" data-width="200px" name="regions">
                           <option readonly selected value="0">--Select--</option>
                           @foreach($regions as $m)
                              <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                           @endforeach
                       </select>
                   </td>
               </tr>
               <tr>
                   <td><label>@lang('acc_object.area')</label></td>
                   <td>
                       <select class="droplist large" data-position="26" data-title="@langt('company.area')" data-template="#= FormatDropList(area,'area') #" data-hidden="true" data-type="number" data-width="200px" name="area">
                         <option readonly selected value="0">--Select--</option>
                         @foreach($area as $m)
                            <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                         @endforeach
                       </select>
                   </td>
               </tr>
               <tr>
                   <td><label>@lang('acc_object.distric')</label></td>
                   <td>
                       <select class="droplist large" data-position="27" data-title="@lang('acc_object.distric')" data-template="#= FormatDropList(distric,'distric') #" data-hidden="true" data-type="number" data-width="200px" name="distric">
                       <option readonly selected value="0">--Select--</option>
                         @foreach($distric as $m)
                            <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                         @endforeach
                       </select>
                   </td>
               </tr>
               <tr class="object_type_1 object_type_2 hidden filter_tr">
                   <td><label>@lang('acc_object.marketing')</label></td>
                   <td>
                       <input type="text" class="k-textbox large" data-position="28" data-title="@lang('acc_object.marketing')" data-hidden="true" data-width="200px" data-type="string" name="marketing" />
                   </td>
               </tr>
               <tr class="object_type_1 object_type_2 hidden filter_tr">
                   <td><label>@lang('acc_object.company_size')</label></td>
                   <td>
                     <select class="droplist large" data-position="29" data-title="@lang('acc_object.company_size')" data-template="#= FormatDropList(company_size,'company_size') #" data-hidden="true" data-type="number" data-width="200px" name="company_size">
                     <option readonly selected value="0">--Select--</option>
                     <option value="1">@lang('company.size_large')</option>
                     <option value="2">@lang('company.size_medium')</option>
                     <option value="3">@lang('company.size_small')</option>
                     </select>
                   </td>
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
      Ermis.data = <?= json_encode($data);?>;
      Ermis.per = <?= json_encode($per);?>;
      Ermis.flag = 1;
      Ermis.link = "{{$key}}";
      Ermis.page_size = "{{$page_size}}";
      Ermis.fieldload = 'code';
      Ermis.fieldload_crit = 'object_type';
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.image_upload = '#avatar';
      Ermis.row_multiselect = 1;
      Ermis.elem = "#form-action";
      Ermis.decimal = "{{$decimal}}";
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "t.object_type", column:  "@lang('acc_object.object_type')" },
                           {field : "t.code", column:  "@lang('acc_object.code')" },
                           {field : "t.name", column:  "@lang('acc_object.name')" },
                           {field : "t.name_1", column:  "@lang('acc_object.name_1')" },
                           {field : "t.identity_card", column:  "@lang('acc_object.identity_card')" },
                           {field : "t.issued_by_identity_card", column:  "@lang('acc_object.issued_by_identity_card')" },
                           {field : "t.date_identity_card", column:  "@lang('acc_object.date_identity_card')" },
                           {field : "t.address", column:  "@lang('acc_object.address')" },
                           {field : "t.email", column:  "@lang('acc_object.email')" },
                           {field : "t.tax_code", column:  "@lang('acc_object.tax_code')" },
                           {field : "t.director", column:  "@lang('acc_object.director')" },
                           {field : "t.phone", column:  "@lang('acc_object.phone')" },
                           {field : "t.fax", column:  "@lang('acc_object.fax')" },
                           {field : "t.full_name_contact", column:  "@lang('acc_object.full_name_contact')" },
                           {field : "t.address_contact", column:  "@lang('acc_object.address_contact')" },
                           {field : "t.title_contact", column:  "@lang('acc_object.title_contact')" },
                           {field : "t.email_contact", column:  "@lang('acc_object.email_contact')" },
                           {field : "t.telephone1_contact", column:  "@lang('acc_object.telephone1_contact')" },
                           {field : "t.telephone2_contact", column:  "@lang('acc_object.telephone2_contact')" },
                           {field : "t.bank_name", column:  "@lang('acc_object.bank_name')" },
                           {field : "t.bank_branch", column:  "@lang('acc_object.bank_branch')" },
                           {field : "t.bank_account", column:  "@lang('acc_object.bank_account')" },
                           {field : "c.name as object_group", column:  "@lang('acc_object.object_group')" },
                           {field : "a.name as department", column:  "@lang('acc_object.department')" },
                           {field : "d.name", column:  "@lang('company.country')" },
                           {field : "m.name", column:  "@lang('company.regions')" },
                           {field : "n.name", column:  "@lang('company.area')" },
                           {field : "s.name", column:  "@lang('company.distric')" },
                           {field : "t.marketing", column:  "@lang('company.marketing')" },
                           {field : "t.company_size", column:  "@lang('company.company_size')" },
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
