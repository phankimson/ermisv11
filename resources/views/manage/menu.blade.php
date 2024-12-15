
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
            <td class="row-label"><label>@lang('menu.parent')</label></td>
            <td>
            <select id="parent_id" class="droplist read load_droplist large" data-position="1" data-title="@lang('menu.parent')" add-option="true" data-template="#= FormatDropListRead(parent_id,'parent_id') #" data-type="number" data-width="200px" data-value-field="value" data-text-field="text" data-read-url="{{env('URL_DROPDOWN').'/menu'}}"  name="parent_id">

            </select>
            </td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('menu.code') *</label></td>
            <td><input type="text" class="k-textbox medium" data-position="2" data-title="@lang('menu.code')" data-width="200px" data-type="string" name="code" /></td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('menu.name') *</label></td>
            <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('menu.name')" data-width="200px" data-type="string" name="name" /></td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('menu.name_en') *</label></td>
            <td><input type="text" class="k-textbox xxlarge" data-position="3" data-title="@lang('menu.name_en')" data-width="200px" data-type="string" name="name_en" /></td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('menu.icon')</label></td>
            <td><input type="text" class="k-textbox large" data-position="3" data-title="@lang('menu.icon')" data-width="200px" data-type="string" name="icon" /></td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('menu.link')</label></td>
            <td><input type="text" class="k-textbox large" data-position="3" data-title="@lang('menu.link')" data-width="200px" data-type="string" name="link" /></td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('menu.position')</label></td>
            <td><input type="text" class="k-textbox medium" data-position="3" data-title="@lang('menu.position')" data-width="200px" data-type="string" name="position" /></td>
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
           <li data-search="{{ $s->id }}" class="k-state-active">{{ $lang == 'vi'? $s->name : $s->name_en }}</li>
      @else
           <li data-search="{{ $s->id }}">{{ $lang == 'vi'? $s->name : $s->name_en }}</li>
      @endif
      @endforeach
    </ul>
</div>
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
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.fieldload = '';
      Ermis.ts = '{{$type}}';
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{ field : "{{ $lang == 'vi'? 'c.name as type' : 'c.name_en as type' }}", column:  "@lang('menu.type')" },
                           {field : "{{ $lang == 'vi'? 'm.name as parent' : 'm.name_en as parent' }}", column:  "@lang('menu.parent')" },
                           {field : "t.code", column:  "@lang('menu.code')" },
                           {field : "t.name", column:  "@lang('menu.name')" },
                           {field : "t.name_en", column:  "@lang('menu.name_en')" },
                           {field : "t.icon", column:  "@lang('menu.icon')" },
                           {field : "t.link", column:  "@lang('menu.link')" },
                           {field : "t.position", column:  "@lang('menu.position')"},
                           {field : "t.active", column:  "@lang('action.active')" }];
  });
  </script>
@endsection
@section('scripts_end')
        <script src="{{ url('addon/scripts/ermis/ermis-form-1-tabstrip.js') }}"></script>
@endsection
