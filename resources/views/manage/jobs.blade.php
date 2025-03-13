
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
            <td class="row-label"><label>@lang('jobs.queue') *</label></td>
            <td><input type="text" class="k-textbox medium" data-position="1" data-title="@lang('jobs.queue')" maxlength="200" data-width="200px" data-type="string" name="queue" /></td>
        </tr>
        <tr>
            <td><label>@lang('jobs.payload')</label></td>
            <td><textarea name="payload" class="k-textbox large" data-title="@lang('jobs.payload')" data-type="string"  data-hidden="true" rows="10" cols="30"></textarea></td>
        </tr>
        <tr>
            <td class="row-label"><label>@lang('jobs.attempts')</label></td>
            <td><input type="number" class="k-textbox large" data-position="4" data-title="@lang('jobs.attempts')" maxlength="11" data-width="200px" data-type="string" name="attempts" /></td>
        </tr>  
        <tr>
            <td class="row-label"><label>@lang('jobs.reserved_at')</label></td>
            <td><input type="number" class="k-textbox large" data-position="4" data-title="@lang('jobs.reserved_at')" maxlength="11" data-width="200px" data-type="string" name="reserved_at" /></td>
        </tr>   
        <tr>
            <td class="row-label"><label>@lang('jobs.available_at')</label></td>
            <td><input type="number" class="k-textbox large" data-position="4" data-title="@lang('jobs.available_at')" maxlength="11" data-width="200px" data-type="string" name="available_at" /></td>
        </tr>   
        <tr>
            <td class="row-label"><label>@lang('jobs.created_at')</label></td>
            <td><input type="number" class="k-textbox large" data-position="4" data-title="@lang('jobs.created_at')" maxlength="11" data-width="200px" data-type="string" name="created_at" /></td>
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
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.fieldload = '';
      Ermis.row_multiselect = 0;
      Ermis.elem = "#form-action";
      Ermis.columns_expend = [{ selectable: true, width: "50px" }, {"field" : "column","title" : "@lang('global.column_name')"}];
      Ermis.data_expend = [{field : "queue", column:  "@lang('jobs.queue')" },
                           {field : "payload", column:  "@lang('jobs.payload')" },
                           {field : "attempts", column:  "@lang('jobs.attempts')" },
                           {field : "reserved_at", column:  "@lang('jobs.reserved_at')" },
                           {field : "available_at", column:  "@lang('jobs.available_at')" },
                           {field : "created_at", column:  "@lang('jobs.created_at')" }];
  });
  </script>
@endsection
@section('scripts_end')
<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.en-DE.min.js') }}"></script>
<script>kendo.culture('en-DE')</script>
@endif
<script src="{{ url('addon/scripts/ermis/ermis-form-1-scrolltt.js') }}"></script>
@endsection
