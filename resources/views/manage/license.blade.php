
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
            <td><label>@lang('license.date_start') *</label></td>
            <td><input type="text" id="start" class="k-widget k-datepicker k-header k-textbox" data-position="1" data-title="@lang('license.date_start')" data-template="#= FormatDate(date_start) #" data-width="200px" data-type="date" name="date_start" /></td>
        </tr>
        <tr>
            <td><label>@lang('license.date_start') *</label></td>
            <td><input type="text" id="end" class="k-widget k-datepicker k-header k-textbox" data-position="2" data-title="@lang('license.date_end')" data-template="#= FormatDate(date_end) #" data-width="200px" data-type="date" name="date_end" /></td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('license.keygen') *</label></td>
            <td>
              <span class="k-textbox k-space-right" style="width: 100%;">
                    <input type="text" data-position="1" data-title="@lang('license.keygen')" data-width="200px" data-null="true" maxlength="50" data-type="string" name="keygen" id="icon-right">
                    <a href="javascript:;" class="k-icon k-i-rotate load">&nbsp;</a>
            </span>
          </td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('license.company_use')</label></td>
            <td><select class="droplist large" data-position="1" data-title="@lang('license.company_use')" data-template="#= FormatDropList(company_use,'company_use') #" data-type="number" data-width="200px" name="company_use">
                    <option readonly selected value="0">--Select--</option>
                      @foreach($company_use as $c)
                        <option value="{{ $c->id }}">{{ $c->code }} - {{ $c->name }}</option>
                      @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('license.software_use')</label></td>
            <td><select class="droplist large" data-position="1" data-title="@lang('license.software_use')" data-template="#= FormatDropList(software_use,'software_use') #" data-type="number" data-width="200px" name="software_use">
                    <option readonly selected value="0">--Select--</option>
                      @foreach($software_use as $c)
                        <option value="{{ $c->id }}">{{ $c->code }} - {{ $c->name }}</option>
                      @endforeach
                </select>
            </td>
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
      Ermis.fieldload = 'keygen';
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "t.date_start", column:  "@lang('license.date_start')" },
                           {field : "t.date_end", column:  "@lang('license.date_end')" },
                           {field : "t.keygen", column:  "@lang('license.keygen')" },
                           {field : "c.name as company_use", column:  "@lang('license.company_use')" },
                           {field : "s.name as software_use", column:  "@lang('license.software_use')"},
                           {field : "t.active", column:  "@lang('action.active')" }];
  });
  </script>
@endsection
@section('scripts_end')
<script src="{{ url('addon/scripts/ermis/ermis-form-1-scroll.js') }}"></script>
@endsection
