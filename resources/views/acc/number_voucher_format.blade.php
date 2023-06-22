
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
      <td class="row-label"><label>@lang('acc_number_voucher_format.number_voucher')</label></td>
      <td>
      <select class="droplist large" data-position="1" data-title="@lang('acc_number_voucher_format.number_voucher')" data-template="#= FormatDropList(number_voucher,'number_voucher') #" data-type="number" data-width="200px" name="number_voucher">
              <option readonly selected value="0">--Select--</option>
                @foreach($menu as $m)
                   <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                @endforeach
      </select>
      </td>
      </tr>
      <tr>
      <td class="row-label"><label>@lang('acc_number_voucher_format.format')</label></td>
      <td>
      <select class="droplist large" data-position="1" data-title="@lang('acc_number_voucher_format.format')" data-template="#= FormatDropList(format,'format') #" data-type="number" data-width="200px" name="format">
              <option readonly selected value="0">@lang('global.select')</option>              
                @foreach($number_voucher_format as $k=>$m)
                   <option value="{{ $k }}"> {{ $m }}</option>
                @endforeach
      </select>
      </td>
      </tr>
      <tr>
      <td class="row-label"><label>@lang('acc_number_voucher_format.type')</label></td>
      <td>
      <select class="droplist large" data-position="1" data-title="@lang('acc_number_voucher.suffixes_type')" data-template="#= FormatDropList(suffixes_type,'suffixes_type') #" data-type="number" data-width="200px" name="suffixes_type">
              <option readonly selected value="0">@lang('global.select')</option>              
              <option value="1"> {{ trans('acc_number_voucher.suffixes') }}</option>
              <option value="2"> {{ trans('acc_number_voucher.prefix') }}</option>
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
      Ermis.data = <?= json_encode($data);?>;
      Ermis.per = <?= json_encode($per);?>;
      Ermis.flag = 1;
      Ermis.link = "{{$key}}";
      Ermis.page_size = "{{$page_size}}";
      Ermis.fieldload = '';
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.decimal = "{{$decimal}}";
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "m.name as menu", column:  "@lang('acc_number_voucher.menu')" },
                           {field : "t.code", column:  "@lang('acc_number_voucher.code')" },
                           {field : "t.name", column:  "@lang('acc_number_voucher.name')" },
                           {field : "t.prefix", column:  "@lang('acc_number_voucher.prefix')" },
                           {field : "t.middle_type", column:  "@lang('acc_number_voucher.middle_type')" },
                           {field : "t.middle", column:  "@lang('acc_number_voucher.middle')" },
                           {field : "t.suffixes_type", column:  "@lang('acc_number_voucher.suffixes_type')" },
                           {field : "t.suffixes", column:  "@lang('acc_number_voucher.suffixes')" },
                           {field : "t.number", column:  "@lang('acc_number_voucher.number')" },
                           {field : "t.change_voucher", column:  "@lang('action.change_voucher')" },
                           {field : "t.active", column:  "@lang('action.active')" }];
  });
  </script>
@endsection
@section('scripts_end')
@if($decimal_symbol === ".")
document.write('<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.de-DE.min.js') }}"></script>')
document.write('<script>kendo.culture('de-DE')</script>')
@endif
<script src="{{ url('addon/scripts/ermis/ermis-form-1-scrolldf.js') }}"></script>
@endsection
