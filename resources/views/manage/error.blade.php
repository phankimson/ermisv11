
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
            <td class="row-label"><label>@lang('error.url') *</label></td>
            <td><input type="text" class="k-textbox medium" data-position="1" data-title="@lang('error.url')" maxlength="50" data-width="200px" maxlength="50" data-type="string" name="url" /></td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('error.user')</label></td>
            <td>
            <select class="droplist large" data-position="1" data-title="@lang('error.user')" data-template="#= FormatDropList(user_id,'user_id') #" data-type="number" data-width="200px" name="user_id">
                    <option readonly selected value="0">--Select--</option>
                      @foreach($user as $u)
                        <option value="{{ $u->id }}">{{ $u->fullname }} - {{ $u->username }}</option>
                      @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('error.menu')</label></td>
            <td>
            <select class="droplist large" data-position="2" data-title="@lang('error.menu')" data-template="#= FormatDropList(menu_id,'menu_id') #" data-type="number" data-width="200px" name="menu_id">
                    <option readonly selected value="0">--Select--</option>
                       @foreach($menu as $m)
                        <option value="{{ $m->id }}">{{ $m->code }} - {{ $lang == 'vi'? $m->name : $m->name_en }}</option>
                       @endforeach
                </select>
            </td>
        </tr>
        <tr>
            <td><label>@lang('error.error')</label></td>
            <td><textarea name="error" class="k-textbox large" data-title="@lang('error.error')" data-type="string"  data-hidden="true" rows="10" cols="30"></textarea></td>
        </tr>
        <tr>
            <td><label>@lang('error.check')</label></td>
            <td class="row-height"><input type="checkbox" data-md-icheck="" data-position="10" data-value="1"  data-title="@lang('error.check')" data-width="100px" data-type="number" data-template="#= FormatCheckBox(check) #" name="check" /></td>
        </tr>
    </table>

@endsection

@push('context_action')
@include('action.context_1')
@endpush

@section('tabs')
<div id="tabstrip" style="display:none">
    <ul>
        <li data-search="0" class="k-state-active"> @lang('error.tab1')</li>
        <li data-search="1">@lang('error.tab2')</li>
        <li data-search="2">@lang('error.tab3')</li>
        <li data-search="3">@lang('error.tab4')</li>
        <li data-search="4">@lang('error.tab5')</li>
        <li data-search="5">@lang('error.tab6')</li>
        <li data-search="6">@lang('error.tab7')</li>
        <li data-search="7">@lang('error.tab8')</li>
        <li data-search="8">@lang('error.tab9')</li>
        <li data-search="9">@lang('error.tab10')</li>
    </ul>
</div>
@endsection
@section('scripts_up')
<script>
  jQuery(document).ready(function () {
      Ermis.data = <?= json_encode($data);?>;
      Ermis.per = <?= json_encode($per);?>;
      Ermis.flag = 1;
      Ermis.page_size = "{{$page_size}}";
      Ermis.link = "{{$key}}";
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.ts = {{$type}};
      Ermis.fieldload = '';
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{ field : "u.username", column:  "@lang('error.user')" },
                            {field : "{{ $lang == 'vi'? 'm.name' : 'm.name_en' }}", column:  "@lang('error.menu')" },
                            {field : "t.error", column:  "@lang('error.error')" },
                            {field : "t.url", column:  "@lang('error.url')" },
                            {field : "t.check", column:  "@lang('error.check')" }];
  });
  </script>
@endsection
@section('scripts_end')
<script src="{{ url('addon/scripts/ermis/ermis-form-1-tabstrip.js') }}"></script>
@endsection
