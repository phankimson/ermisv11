
@extends('form.ermis_form_1')

@push('css_up')

@endpush

@push('action')
@include('action.top_menu_6')
@endpush


@push('toolbar_action')
@include('action.toolbar_5')
@endpush
@section('content_add')
    <div id="notification"></div>
    <table>
      <tr>
          <td class="row-label"><label>@lang('acc_period.date') *</label></td>
          <td>
            <input type="text" class="month-picker" name="date" value="{{ date('m/Y') }}" />
          </td>
      </tr>

    </table>
@endsection

@push('context_action')
@include('action.context_4')
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
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.columns    =   [{ "field" : "id",hidden: true },
                               { "field" : "name","title" :"@lang('acc_period.name')" ,"width" : "200px"},
                               { "field" : "name_en","title" :"@lang('acc_period.name_en')" ,"width" : "200px"},
                               { "field" : "date","title" :"@lang('acc_period.date')" ,"template" : "#= FormatMonth(date) #","width" : "200px" },
                               { "field" : "created_at","title" :"@lang('acc_period.date_created')","template" : "#= FormatDate(created_at) #" ,"width" : "200px" },
                               { "field" : "active","title" :"@lang('action.active')" ,"width" : "200px" ,"template" : "#= FormatCheckBox(active) #"},];
  });
  </script>
@endsection
@section('scripts_end')
<script src="{{ url('addon/scripts/ermis/ermis-4.js') }}"></script>
@endsection
