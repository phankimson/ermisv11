
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
   <ul class="uk-tab" data-uk-tab="{connect:'#tabs_anim', animation:'slide-left', swiping: false}">
       <li class="uk-active"><a href="javascript:;">@lang('company.info')</a></li>
       <li><a href="javascript:;">@lang('company.contact')</a></li>
       <li><a href="javascript:;">@lang('company.statistics')</a></li>
       <li><a href="javascript:;">@lang('global.expand')</a></li>
   </ul>
   <ul id="tabs_anim" class="uk-switcher uk-margin">
       <li>
           <table>
               <tr>
                   <td class="row-label"><label>@lang('company.level')</label></td>
                   <td><input type="number" class="k-textbox medium" data-position="2" data-title="@lang('company.level')" data-hidden="true" data-width="200px" data-type="string" name="level" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('company.code') *</label></td>
                   <td><input type="text" class="k-textbox medium" data-position="3" data-title="@lang('company.code')" maxlength="50" data-width="200px" data-type="string" name="code" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('company.name') *</label></td>
                   <td><input type="text" class="k-textbox large" data-position="4" data-title="@lang('company.name')" maxlength="100" data-width="200px" data-type="string" name="name" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('company.address')</label></td>
                   <td><input type="text" class="k-textbox large" data-position="6" data-title="@lang('company.address')" data-width="200px" data-type="string" name="address" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('company.tax_code') *</label></td>
                   <td><input type="text" class="k-textbox medium" data-position="7" data-title="@lang('company.tax_code')" maxlength="15" data-width="200px" data-type="string" name="tax_code" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('company.director')</label></td>
                   <td><input type="text" class="k-textbox medium" data-position="8" data-title="@lang('company.director')" data-width="200px" data-type="string" name="director" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('company.phone')</label></td>
                   <td><input type="text" class="k-textbox medium" data-position="8" data-title="@lang('company.phone')" data-width="200px" data-type="string" name="phone" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('company.fax')</label></td>
                   <td><input type="text" class="k-textbox medium" data-position="8" data-title="@lang('company.fax')" data-width="200px" data-type="string" name="fax" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('company.email')</label></td>
                   <td><input type="text" class="k-textbox large" data-position="8" data-title="@lang('company.email')" data-width="200px" data-type="string" name="email" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('company.website')</label></td>
                   <td><input type="text" class="k-textbox large" data-position="8" data-title="@lang('company.website')" data-width="200px" data-type="string" name="website" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('company.bank_account')</label></td>
                   <td><input type="text" class="k-textbox large" data-position="8" data-title="@lang('company.bank_account')" data-width="200px" data-type="string" name="bank_account" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('company.bank_name')</label></td>
                   <td><input type="text" class="k-textbox large" data-position="8" data-title="@lang('company.bank_name')" data-width="200px" data-type="string" name="bank_name" /></td>
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
                   <td class="row-label"><label>@lang('company.full_name_contact')</label></td>
                   <td><input type="text" class="k-textbox medium" data-position="8" data-title="@lang('company.full_name_contact')" data-hidden="true" data-width="200px" data-type="string" name="full_name_contact" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('company.address_contact')</label></td>
                   <td><input type="text" class="k-textbox medium" data-position="8" data-title="@lang('company.address_contact')" data-hidden="true" data-width="200px" data-type="string" name="address_contact" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('company.title_contact')</label></td>
                   <td><input type="text" class="k-textbox medium" data-position="8" data-title="@lang('company.title_contact')" data-hidden="true" data-width="200px" data-type="string" name="title_contact" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('company.email_contact')</label></td>
                   <td><input type="text" class="k-textbox medium" data-position="8" data-title="@lang('company.email_contact')" data-hidden="true" data-width="200px" data-type="string" name="email_contact" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('company.telephone1_contact')</label></td>
                   <td><input type="text" class="k-textbox medium" data-position="8" data-title="@lang('company.telephone1_contact') " data-hidden="true" data-width="200px" data-type="string" name="telephone1_contact" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('company.telephone2_contact')</label></td>
                   <td><input type="text" class="k-textbox medium" data-position="8" data-title="@lang('company.telephone2_contact') " data-hidden="true" data-width="200px" data-type="string" name="telephone2_contact" /></td>
               </tr>
           </table>
       </li>
       <li>
           <table>
               <tr>
                   <td><label>@lang('company.country')</label></td>
                   <td>
                       <select class="droplist large" data-position="3" data-title="@lang('company.country')" data-template="#= FormatDropList(country,'country') #" data-hidden="true" data-type="number" data-width="200px" name="country">
                           <option readonly selected value="0">--Select--</option>
                           @foreach($country as $m)
                              <option value="{{ $m->id }}">{{ $m->code }} - {{ $m->name }}</option>
                           @endforeach
                       </select>
                   </td>
               </tr>
               <tr>
                   <td><label>@lang('company.regions')</label></td>
                   <td>
                       <select class="droplist large" data-position="3" data-title="@lang('company.regions')" data-template="#= FormatDropList(regions,'regions') #" data-hidden="true" data-type="number" data-width="200px" name="regions">
                           <option readonly selected value="0">--Select--</option>
                           @foreach($regions as $m)
                              <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                           @endforeach
                       </select>
                   </td>
               </tr>
               <tr>
                   <td><label>@lang('company.area')</label></td>
                   <td>
                       <select class="droplist large" data-position="3" data-title="@langt('company.area')" data-template="#= FormatDropList(area,'area') #" data-hidden="true" data-type="number" data-width="200px" name="area">
                         <option readonly selected value="0">--Select--</option>
                         @foreach($area as $m)
                            <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                         @endforeach
                       </select>
                   </td>
               </tr>
               <tr>
                   <td><label>@lang('company.distric')</label></td>
                   <td>
                       <select class="droplist large" data-position="3" data-title="@lang('company.distric')" data-template="#= FormatDropList(distric,'distric') #" data-hidden="true" data-type="number" data-width="200px" name="distric">
                       <option readonly selected value="0">--Select--</option>
                         @foreach($distric as $m)
                            <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                         @endforeach
                       </select>
                   </td>
               </tr>
               <tr>
                   <td><label>@lang('company.marketing')</label></td>
                   <td>
                       <input type="text" class="k-textbox large" data-position="8" data-title="@lang('company.marketing')" data-hidden="true" data-width="200px" data-type="string" name="marketing" />
                   </td>
               </tr>
               <tr>
                   <td><label>@lang('company.company_size')</label></td>
                   <td>
                     <select class="droplist large" data-position="3" data-title="@lang('company.company_size')" data-template="#= FormatDropList(company_size,'company_size') #" data-hidden="true" data-type="number" data-width="200px" name="company_size">
                     <option readonly selected value="0">--Select--</option>
                     <option value="1">@lang('company.size_large')</option>
                     <option value="2">@lang('company.size_medium')</option>
                     <option value="3">@lang('company.size_small')</option>
                     </select>
                   </td>
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
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "t.code", column:  "@lang('company.code')" },
                           {field : "t.name", column:  "@lang('company.name')" },
                           {field : "t.address", column:  "@lang('company.address')" },
                           {field : "t.email", column:  "@lang('company.email')" },
                           {field : "t.tax_code", column:  "@lang('company.tax_code')" },
                           {field : "t.director", column:  "@lang('company.director')" },
                           {field : "t.phone", column:  "@lang('company.phone')" },
                           {field : "t.fax", column:  "@lang('company.fax')" },
                           {field : "t.full_name_contact", column:  "@lang('company.full_name_contact')" },
                           {field : "t.address_contact", column:  "@lang('company.address_contact')" },
                           {field : "t.title_contact", column:  "@lang('company.title_contact')" },
                           {field : "t.email_contact", column:  "@lang('company.email_contact')" },
                           {field : "t.telephone1_contact", column:  "@lang('company.telephone1_contact')" },
                           {field : "t.telephone2_contact", column:  "@lang('company.telephone2_contact')" },
                           {field : "d.name as country", column:  "@lang('company.country')" },
                           {field : "m.name as regions", column:  "@lang('company.regions')" },
                           {field : "n.name as area", column:  "@lang('company.area')" },
                           {field : "s.name as distric", column:  "@lang('company.distric')" },
                           {field : "t.marketing", column:  "@lang('company.marketing')" },
                           {field : "t.company_size", column:  "@lang('company.company_size')" },
                           {field : "t.level", column:  "@lang('company.level')" },
                           {field : "t.active", column:  "@lang('action.active')" }];
  });
  </script>
@endsection
@section('scripts_end')
<script src="{{ url('addon/scripts/ermis/ermis-form-2-page.js') }}"></script>
@endsection
