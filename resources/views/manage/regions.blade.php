
@extends('form.ermis_form_1')

@push('css_up')

@endpush

@push('action')
@include('action.top_menu_1')
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
          <td class="row-label"><label>@lang('regions.country')</label></td>
          <td><select class="droplist large" data-position="1" data-title="@lang('regions.country')" data-template="#= FormatDropList(country,'country') #" data-type="number" data-width="200px" name="country">
                  <option readonly selected value="0">--Select--</option>
                    @foreach($country as $c)
                      <option value="{{ $c->id }}">{{ $c->code }} - {{ $c->name }}</option>
                    @endforeach
              </select>
          </td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('regions.code') *</label></td>
          <td><input type="text" class="k-textbox medium" data-position="2" data-title="@lang('regions.code')" maxlength="50" data-width="200px" maxlength="50" data-type="string" name="code" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('regions.name') *</label></td>
          <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('regions.name')" maxlength="100" data-width="200px" maxlength="100" data-type="string" name="name" /></td>
      </tr>
      <tr>
          <td class="row-label"><label>@lang('regions.name_en') *</label></td>
          <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('regions.name_en')" maxlength="100" data-width="200px" maxlength="100" data-type="string" name="name_en" /></td>
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
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "m.name as country", column:  "@lang('regions.country')" },
                           {field : "t.code", column:  "@lang('regions.code')" },
                           {field : "t.name", column:  "@lang('regions.name')" },
                           {field : "t.name_en", column:  "@lang('regions.name_en')" },
                           {field : "t.active", column:  "@lang('action.active')" }];
  });
  </script>
@endsection
@section('scripts_end')
<script src="{{ url('addon/scripts/ermis/ermis-form-1-scrolldf.js') }}"></script>
@endsection
