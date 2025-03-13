
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
       <li class="uk-active"><a href="javascript:;">@lang('user.info')</a></li>
       <li><a href="javascript:;">@lang('user.authentication')</a></li>
       <li><a href="javascript:;">@lang('global.expand')</a></li>
   </ul>
   <ul id="tabs_anim" class="uk-switcher uk-margin">
       <li>
           <table>
               <tr>
                   <td class="row-label"><label>@lang('login.username')</label></td>
                   <td><input type="text" class="k-textbox medium" data-position="2" data-title="@lang('login.username')" data-width="200px" data-type="string" name="username" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('user.fullname') *</label></td>
                   <td><input type="text" class="k-textbox medium" data-position="3" data-title="@lang('user.fullname')" maxlength="50" data-width="200px" data-type="string" name="fullname" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('user.firstname') *</label></td>
                   <td><input type="text" class="k-textbox large" data-position="4" data-title="@lang('user.firstname')" maxlength="100" data-width="200px" data-type="string" name="firstname" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('user.lastname')</label></td>
                   <td><input type="text" class="k-textbox large" data-position="6" data-title="@lang('user.lastname')" data-width="200px" data-type="string" name="lastname" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('user.identity_card') *</label></td>
                   <td><input type="text" class="k-textbox medium" data-position="7" data-title="@lang('user.identity_card')" maxlength="15" data-width="200px" data-type="string" name="identity_card" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('user.birthday')</label></td>
                   <td><input type="text" class="k-widget k-datepicker k-header k-textbox date" data-position="8" data-title="@lang('user.birthday')" data-template="#= FormatDate(birthday) #" data-width="200px" data-type="date" name="birthday" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('user.phone')</label></td>
                   <td><input type="text" class="k-textbox medium" data-position="8" data-title="@lang('user.phone')" data-width="200px" maxlength="15" data-type="string" name="phone" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('user.email')</label></td>
                   <td><input type="text" class="k-textbox large" data-position="8" data-title="@lang('user.email')" data-width="200px" data-type="string" name="email" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('user.address')</label></td>
                   <td><input type="text" class="k-textbox large" data-position="8" data-title="@lang('user.address')" data-width="200px" data-type="string" name="address" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('user.city')</label></td>
                   <td><input type="text" class="k-textbox large" data-position="8" data-title="@lang('user.city')" data-width="200px" data-type="string" name="city" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('user.jobs')</label></td>
                   <td><input type="text" class="k-textbox large" data-position="8" data-title="@lang('user.jobs')" data-width="200px" data-type="string" name="jobs" /></td>
               </tr>
               <tr>
                   <td class="row-label"><label>@lang('user.country')</label></td>
                   <td>
                     <select class="droplist read large" data-position="8" data-title="@lang('user.country')" add-option="true" data-template="#= FormatDropListRead(country,'country') #" data-type="string" data-width="200px" data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.manage.'.env('URL_DROPDOWN').'.country')}}"  name="country">
                         </select>
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
                 <td class="row-label"><label>@lang('login.password')</label></td>
                 <td><input type="password" class="k-textbox large" data-hidden="true" data-title="@lang('login.password')" data-width="200px" name="password" /></td>
             </tr>
             <tr>
                 <td class="row-label"><label>@lang('user.role')</label></td>
                 <td><input type="number" class="k-textbox large" data-position="8" data-title="@lang('user.role')" data-width="200px" data-type="string" name="role" /></td>
             </tr>
             <tr>
                 <td class="row-label"><label>@lang('user.group_user')</label></td>
                 <td><select class="droplist read large" data-position="8" data-title="@lang('user.group_user')" data-template="#= FormatDropListRead(group_users_id,'group_users_id') #" data-type="number"  data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.manage.'.env('URL_DROPDOWN').'.group-users')}}"  data-width="200px" name="group_users_id">
                     </select>
                 </td>
             </tr>
             <tr>
                 <td class="row-label"><label>@lang('user.active_code')</label></td>
                 <td><input type="text" class="k-textbox large" data-position="8" data-title="@lang('user.active_code')" data-width="200px" data-type="string" name="active_code" /></td>
             </tr>
             <tr>
                 <td class="row-label"><label>@lang('user.barcode')</label></td>
                 <td><input type="text" class="k-textbox large" data-position="8" data-title="@lang('user.barcode')" data-width="200px" data-type="string" name="barcode" /></td>
             </tr>
             <tr>
                 <td class="row-label"><label>@lang('user.stock_default')</label></td>
                 <td><select class="droplist large" data-position="8" data-title="@lang('user.stock_default')" data-template="#= FormatDropList(stock_default,'stock_default') #" data-type="number" data-width="200px" name="stock_default">
                         <option readonly selected value="0">--Select--</option>
                     </select>
                 </td>
             </tr>
             <tr>
                 <td class="row-label"><label>@lang('user.company_default')</label></td>
                 <td><select class="droplist read large" data-position="8" data-title="@lang('user.company_default')" data-template="#= FormatDropListRead(company_default,'company_default') #" data-type="number" data-width="200px"  data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.manage.'.env('URL_DROPDOWN').'.company')}}"  name="company_default">
                     </select>
                 </td>
             </tr>

           </table>
       </li>
       <li>
         <table>
           <tr>
               <td class="row-label"><label>@lang('user.about')</label></td>
               <td><textarea class="k-textbox" data-title="@lang('user.about')" data-position="8" data-hidden="true" rows="4" cols="50" name="about" /></textarea></td>
           </tr>
           <tr>
              <td class="row-label"><label>@lang('user.image')</label></td>
              <td class="row-height">
                  <input type="file" data-title="@lang('user.avatar')" data-width="120px"  data-position="3" data-template="#= FormatImageTooltip(avatar) #" id="avatar" aria-label="files" name="avatar" />
                  <img id="avatar_preview" class="max-width-100" src="{{ url('addon/img/placehold/100.png') }}" alt="Image Preview" />
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
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "t.username", column:  "@lang('login.username')" },
                           {field : "t.fullname", column:  "@lang('user.fullname')" },
                           {field : "t.firstname", column:  "@lang('user.firstname')" },
                           {field : "t.lastname", column:  "@lang('user.lastname')" },
                           {field : "t.identity_card", column:  "@lang('user.identity_card')" },
                           {field : "t.birthday", column:  "@lang('user.birthday')" },
                           {field : "t.phone", column:  "@lang('user.phone')" },
                           {field : "t.email", column:  "@lang('user.email')" },
                           {field : "t.address", column:  "@lang('user.address')" },
                           {field : "t.city", column:  "@lang('user.city')" },
                           {field : "t.jobs", column:  "@lang('user.jobs')" },
                           {field : "t.about", column:  "@lang('user.about')" },
                           {field : "t.active_code", column:  "@lang('user.active_code')" },
                           {field : "t.role", column:  "@lang('user.role')" },
                           {field : "t.barcode", column:  "@lang('user.barcode')" },
                           {field : "m.name as group_user", column:  "@lang('user.group_user')" },
                           {field : "n.name as company", column:  "@lang('user.company_default')"},
                           {field : "d.name as country", column:  "@lang('user.country')" },
                           {field : "t.active", column:  "@lang('action.active')" }];
  });
  </script>
@endsection
@section('scripts_end')
<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.en-DE.min.js') }}"></script>
<script>kendo.culture('en-DE')</script>
<script src="{{ url('addon/scripts/ermis/ermis-form-2-pageup.js') }}"></script>
@endsection
