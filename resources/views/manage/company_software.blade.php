
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
            <td class="row-label"><label>@lang('company_software.company')</label></td>
            <td>
            <select class="droplist large" data-position="1" data-title="@lang('company_software.company')" data-template="#= FormatDropList(company_id,'company_id') #" data-type="number" data-width="200px" name="company_id">
                    <option readonly selected value="0">--Select--</option>
                      @foreach($company as $c)
                         <option value="{{ $c->id }}">{{ $c->code }} - {{ $c->name }}</option>
                      @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('company_software.license') *</label></td>
            <td>
            <select class="droplist large" data-position="1" data-title="@lang('company_software.license')" data-template="#= FormatDropList(license_id,'license_id') #" data-type="number" data-width="200px" name="license_id">
                    <option readonly selected value="0">--Select--</option>
                      @foreach($license as $n)
                         <option value="{{ $n->id }}">{{ $n->keygen }} - {{ $n->date_end }}</option>
                      @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('company_software.free')</label></td>
            <td><input type="number" class="k-textbox large" data-position="3" data-title="@lang('company_software.free')" data-width="200px" data-type="string" name="free" /></td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('company_software.database') *</label></td>
            <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('company_software.database')" maxlength="100" data-width="200px" data-type="string" name="database" /></td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('company_software.username')</label></td>
            <td><input type="text" class="k-textbox large" data-position="4" data-title="@lang('company_software.username')" maxlength="100" data-width="200px" data-type="string" name="username" /></td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('company_software.password')</label></td>
            <td><input type="password" class="k-textbox large" data-position="4" data-hidden="true" maxlength="100" data-width="200px" data-type="string" name="password" /></td>
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
<div id="tabstrip" style="display:none">
    <ul>
      @foreach($software as $s)
        @if ($loop->first)
        <li data-search="{{$s->id}}" class="k-state-active">{{ $lang == 'vi'? $s->name : $s->name_en }}</li>
        @else
        <li data-search="{{$s->id}}">{{ $lang == 'vi'? $s->name : $s->name_en }}</li>
        @endif
      @endforeach
    </ul>
</div>
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
      Ermis.fieldload = '';
      Ermis.ts = '{{$type}}';
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{ field : "m.name as company", column:  "@lang('company_software.company')" },
                           {field : "u.name as software", column:  "@lang('company_software.software')" },
                           {field : "c.keygen as license", column:  "@lang('company_software.license')" },
                           {field : "t.free", column:  "@lang('company_software.free')" },
                           {field : "t.database", column:  "@lang('company_software.database')" },
                           {field : "t.active", column:  "@lang('action.active')" }];
  });
  </script>
@endsection
@section('scripts_end')
<script src="{{ url('addon/scripts/ermis/ermis-form-1-tabstrip.js') }}"></script>
@endsection
