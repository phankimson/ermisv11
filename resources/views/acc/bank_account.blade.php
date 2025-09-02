
@extends('form.ermis_form_1')

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
    <div id="notification"></div>
    <table>
      <tr>
          <td class="row-label"><label>@lang('acc_bank_account.bank')</label></td>
          <td>
            <select class="droplist read large" data-position="1" data-title="@lang('acc_bank_account.bank')" data-template="#= FormatDropListRead(bank_id,'bank_id') #" data-type="number" data-width="200px" data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.bank')}}" name="bank_id">                    
            </select>
          </td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('acc_bank_account.bank_account') *</label></td>
          <td><input type="text" class="k-textbox medium" data-position="2" data-title="@lang('acc_bank_account.bank_account')" data-width="200px" maxlength="50" data-type="string" name="bank_account" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('acc_bank_account.bank_name') *</label></td>
          <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('acc_bank_account.bank_name')" data-width="200px" maxlength="100" data-type="string" name="bank_name" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('acc_bank_account.branch') </label></td>
          <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('acc_bank_account.branch')" data-width="200px" maxlength="100" data-type="string" name="branch" /></td>
      </tr>
       <tr>
          <td class="row-label"><label>@lang('acc_bank_account.account_default')</label></td>
          <td>
               <select class="droplist read large" data-position="4" data-title="@lang('acc_bank_account.account_default')" data-template="#= FormatDropListRead(account_default,'account_default') #" data-type="number"  data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.setting-account-group').'?code=NH'}}" data-width="200px" name="account_default">
                       
               </select>
          </td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('acc_bank_account.description')</label></td>
          <td><textarea type="text" class="k-textbox medium" data-position="8" data-title="@lang('acc_bank_account.description')" data-width="200px" data-hidden="true" data-type="string" name="description" /></textarea></td>
      </tr>
        <tr>
            <td><label>@lang('action.active')</label></td>
            <td class="row-height"><input type="checkbox" data-md-icheck="" data-position="10" data-value="1"  data-title="@lang('action.active')" data-width="100px" data-type="number" data-template="#= FormatCheckBox(active) #" name="active" /></td>
        </tr>
    </table>
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
      Ermis.fieldload = '';
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.decimal = "{{$decimal}}";
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "c.name as bank", column:  "@lang('acc_bank_account.bank')" },
                           {field : "t.bank_account", column:  "@lang('acc_bank_account.bank_account')" },
                           {field : "t.bank_name", column:  "@lang('acc_bank_account.bank_name')" },
                           {field : "a.code as account_default", column:  "@lang('acc_bank_account.account_default')" },
                           {field : "t.branch", column:  "@lang('acc_bank_account.branch')" },
                           {field : "t.description", column:  "@lang('acc_bank_account.description')" },
                           {field : "t.active", column:  "@lang('action.active')" }];
  });
  </script>
@endsection
@section('scripts_end')
@if($decimal_symbol === ".")
<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.en-DE.min.js') }}"></script>
<script>kendo.culture('en-DE')</script>
@endif
<script src="{{ url('addon/scripts/ermis/ermis-form-1-scroll.js') }}"></script>
@endsection
