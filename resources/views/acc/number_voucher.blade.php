
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
      <td class="row-label"><label>@lang('acc_number_voucher.menu')</label></td>
      <td>
      <select class="droplist read large" data-position="1" data-title="@lang('acc_number_voucher.menu')" data-template="#= FormatDropListRead(menu_id,'menu_id') #" data-type="number" data-width="200px"  data-value-field="value" data-text-field="text" data-read-url="{{env('URL_DROPDOWN').'/menu'}}" name="menu_id">
             
      </select>
      </td>
      </tr>
      <tr>
      <td class="row-label"><label>@lang('acc_number_voucher.menu_general')</label></td>
      <td>
      <select class="droplist read large" data-position="1" data-title="@lang('acc_number_voucher.menu_general')" data-template="#= FormatDropListRead(menu_general_id,'menu_general_id') #" data-type="number" data-width="200px"  data-value-field="value" data-text-field="text" data-read-url="{{env('URL_DROPDOWN').'/menu'}}" name="menu_general_id">
              
      </select>
      </td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('acc_number_voucher.code') *</label></td>
          <td><input type="text" class="k-textbox medium" data-position="2" data-title="@lang('acc_number_voucher.code')" data-width="200px" maxlength="50" data-type="string" name="code" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('acc_number_voucher.name') </label></td>
          <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('acc_number_voucher.name')" data-width="200px" maxlength="100" data-type="string" name="name" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('acc_number_voucher.name_en') </label></td>
          <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('acc_number_voucher.name_en')" data-width="200px" maxlength="100" data-type="string" name="name_en" /></td>
      </tr>   
      <tr>
          <td class="row-label"><label>@lang('acc_number_voucher.prefix') </label></td>
          <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('acc_number_voucher.prefix')" data-width="200px" maxlength="100" data-type="string" name="prefix" /></td>
      </tr>
      <tr>
      <td class="row-label"><label>@lang('acc_number_voucher.format_type')</label></td>
      <td>
      <select class="droplist large" change-item="1" data-position="1" data-title="@lang('acc_number_voucher.format')" data-template="#= FormatDropList(format,'format') #" data-type="number" data-width="200px" name="format">
              <option readonly selected value="0">@lang('global.select')</option>              
                @foreach($number_voucher_format as $k=>$m)
                   <option value="{{ $k }}"> {{ $m }}</option>
                @endforeach
      </select>
      </td>
      </tr>     

      <tr>
          <td class="row-label"><label>@lang('acc_number_voucher.number') *</label></td>
          <td><input type="number" class="k-textbox xlarge" data-position="3" data-title="@lang('acc_number_voucher.number')" value="1" data-width="200px" maxlength="100" data-type="string" name="number" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('acc_number_voucher.length_number') *</label></td>
          <td><input type="number" class="k-textbox xlarge" data-position="3" data-title="@lang('acc_number_voucher.length_number')" value="1" data-width="200px" maxlength="11" data-type="string" name="length_number" /></td>
      </tr>
      <tr>
            <td><label>@lang('acc_number_voucher.change_voucher')</label></td>
            <td class="row-height"><input type="checkbox" data-md-icheck="" data-position="10" data-value="1"  data-title="@lang('acc_number_voucher.change_voucher')" data-width="100px" data-type="number" data-template="#= FormatCheckBox(change_voucher) #" name="change_voucher" /></td>
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
      Ermis.data_expend = [{field : "m.name as menu", column:  "@lang('acc_number_voucher.menu')" },
                           {field : "n.name as menu", column:  "@lang('acc_number_voucher.menu_general')" },
                           {field : "t.code", column:  "@lang('acc_number_voucher.code')" },
                           {field : "t.name", column:  "@lang('acc_number_voucher.name')" },
                           {field : "t.prefix", column:  "@lang('acc_number_voucher.prefix')" },
                           {field : "t.format", column:  "@lang('acc_number_voucher.format')" },
                           {field : "t.number", column:  "@lang('acc_number_voucher.number')" },
                           {field : "t.change_voucher", column:  "@lang('action.change_voucher')" },
                           {field : "t.active", column:  "@lang('action.active')" }];
  });
  </script>
@endsection
@section('scripts_end')
@if($decimal_symbol === ".")
<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.de-DE.min.js') }}"></script>
<script>kendo.culture('de-DE')</script>
@endif
<script src="{{ url('addon/scripts/ermis/ermis-form-1-scrolldf.js') }}"></script>
@endsection
