
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
      <td class="row-label"><label>@lang('acc_count_voucher.number_voucher')</label></td>
      <td>
      <select class="droplist large" data-position="1" data-title="@lang('acc_count_voucher.number_voucher')" data-template="#= FormatDropList(number_voucher,'number_voucher') #" data-type="number" data-width="200px" name="number_voucher">
              <option readonly selected value="0">--Select--</option>
                @foreach($number_voucher as $m)
                   <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                @endforeach
      </select>
      </td>
      </tr>
      <tr>
      <td class="row-label"><label>@lang('acc_count_voucher.format')</label></td>
      <td>
      <select class="droplist large" data-position="1" data-title="@lang('acc_count_voucher.format')" data-template="#= FormatDropList(format,'format') #" data-type="number" data-width="200px" name="format">
              <option readonly selected value="0">--Select--</option>
                @foreach($number_voucher_format as $k=>$m)
                   <option value="{{ $k }}"> {{ $m }}</option>
                @endforeach
      </select>
      </td>
      </tr>      
        <tr>
            <td class="row-label"><label>@lang('acc_number_voucher.day') *</label></td>
            <td><input type="number" class="k-textbox xlarge" data-position="3" data-title="@lang('acc_number_voucher.day')" value="{{date('d')}}" data-width="200px" max="31" maxlength="2" data-type="number" name="day" /></td>
        </tr>
         <tr>
            <td class="row-label"><label>@lang('acc_number_voucher.month') *</label></td>
            <td><input type="number" class="k-textbox xlarge" data-position="3" data-title="@lang('acc_number_voucher.month')" value="{{date('m')}}" data-width="200px" max="12" maxlength="2" data-type="number" name="month" /></td>
        </tr>
         <tr>
            <td class="row-label"><label>@lang('acc_number_voucher.year') *</label></td>
            <td><input type="number" class="k-textbox xlarge" data-position="3" data-title="@lang('acc_number_voucher.year')" value="{{date('Y')}}" data-width="200px" maxlength="4" data-type="number" name="year" /></td>
        </tr>
      <tr>
          <td class="row-label"><label>@lang('acc_number_voucher.number') *</label></td>
          <td><input type="number" class="k-textbox xlarge" data-position="3" data-title="@lang('acc_number_voucher.number')" value="1" data-width="200px" maxlength="100" data-type="number" name="number" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('acc_number_voucher.length_number') *</label></td>
          <td><input type="number" class="k-textbox xlarge" data-position="3" data-title="@lang('acc_number_voucher.length_number')" value="1" data-width="200px" maxlength="11" data-type="number" name="length_number" /></td>
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
      Ermis.data_expend = [{field : "t.number_voucher", column:  "@lang('acc_count_voucher.number_voucher')" },                       
                           {field : "t.day", column:  "@lang('acc_count_voucher.day')" },
                           {field : "t.month", column:  "@lang('acc_count_voucher.month')" },
                           {field : "t.year", column:  "@lang('acc_count_voucher.year')" },
                           {field : "t.number", column:  "@lang('acc_count_voucher.number')" },
                           {field : "t.length_number", column:  "@lang('acc_count_voucher.length_number')" },                       
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
