
@extends('form.ermis_form_1')

@push('css_up')

@endpush

@push('action')
@include('action.top_menu_1')
@endpush

@push('toolbar_action')
@include('action.toolbar_1')
@endpush

@push('export_extra')
@include('window.export_extra')
@endpush

@section('content_add')
    <div id="notification"></div>
    <table>
        <tr>
            <td class="row-label"><label>@lang('history_action.url') *</label></td>
            <td><input type="text" class="k-textbox medium" data-position="1" data-title="@lang('history_action.url')" maxlength="50" data-width="200px" maxlength="50" data-type="string" name="url" /></td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('history_action.user')</label></td>
            <td>
            <select class="droplist large" data-position="1" data-title="@lang('history_action.user')" data-template="#= FormatDropList(user,'user') #" data-type="number" data-width="200px" name="user">
                    <option readonly selected value="0">--Select--</option>
                      @foreach($user as $u)
                        <option value="{{ $u->id }}">{{ $u->fullname }} - {{ $u->username }}</option>
                      @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('history_action.menu')</label></td>
            <td>
            <select class="droplist large" data-position="2" data-title="@lang('history_action.menu')" data-template="#= FormatDropList(menu,'menu') #" data-type="number" data-width="200px" name="menu">
                    <option readonly selected value="0">--Select--</option>
                       @foreach($menu as $m)
                        <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                       @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td><label>@lang('history_action.data')</label></td>
            <td><textarea name="dataz" class="k-textbox large" data-title="@lang('history_action.data')" data-type="string"  data-hidden="true" rows="10" cols="30"></textarea></td>
        </tr>
        <tr>
            <td><label>@lang('history_action.created_at')</label></td>
            <td><input type="text" class="k-datepicker date" data-position="4" data-title="@lang('history_action.created_at')" data-width="200px" data-type="date" data-template="#= kendo.toString(kendo.parseDate(created_at, 'yyyy-MM-dd'), 'dd/MM/yyyy') #" name="created_at" /></td>
        </tr>
    </table>

@endsection

@push('context_action')
@include('action.context_1')
@endpush

@section('tabs')
<div id="tabstrip" style="display:none">
    <ul>
        <li data-search="0" class="k-state-active"> @lang('history_action.tab1')</li>
        <li data-search="1">@lang('history_action.tab2')</li>
        <li data-search="2">@lang('history_action.tab3')</li>
        <li data-search="3">@lang('history_action.tab5')</li>
        <li data-search="4">@lang('history_action.tab6')</li>
        <li data-search="5">@lang('history_action.tab7')</li>
        <li data-search="6">@lang('history_action.tab4')</li>
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
      Ermis.elem = "#form-action";
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.ts = {{$type}};
      Ermis.fieldload = '';
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{ field : "u.username", column:  "@lang('history_action.user')" },
                           {field : "{{ $lang == 'vi'? 'm.name' : 'm.name_en' }}", column:  "@lang('history_action.menu')" },
                           {field : "t.dataz", column:  "@lang('history_action.data')" }];
  });
  </script>
@endsection
@section('scripts_end')
<script src="{{ url('addon/scripts/ermis/ermis-form-1-tabstrip.js') }}"></script>
@endsection
