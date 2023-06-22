
@extends('form.ermis_form_2')

@push('css_up')

@endpush

@push('action')
@include('action.top_menu_3')
@endpush

@push('export_extra')
@include('window.export_extra')
@endpush

@push('toolbar_action')
@include('action.toolbar_2')
@endpush
@section('content_add')
<div id="notification"></div>
<form action="" class="uk-form-stacked" id="page_settings">
    <div class="uk-grid" data-uk-grid-margin>
  <div class="portlet light bordered">
        <div class="portlet-title tabbable-line row-padding-bottom-small">
            <div class="pull-right">
                <a class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light select tooltips" >Select</a>
                <a class="md-btn md-btn-warning md-btn-wave-light waves-effect waves-button waves-light insert tooltips">Insert</a>
                <a class="md-btn md-btn-wave waves-effect waves-button update tooltips" >Update</a>
                <a class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light delete tooltips">Delete</a>
                <a class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light truncate tooltips">Truncate</a>
              </div>
        </div>
        <div class="portlet-body query-form">
             <textarea class="form-control xxxlarge row-height" placeholder="@lang('messages.please_fill_field')" name="query"></textarea>
        </div>
    </div>
  </div>
  <div class="uk-margin" style="float : right">
     <a href="javascript:;" class="k-button k-primary query" data-uk-tooltip title="@lang('action.query')  (Alt+Q)"><i class="md-18 material-icons md-color-white">settings_power</i>@lang('action.query')</a>
     <a href="javascript:;" class="k-button k-primary cancel" data-uk-tooltip title="@lang('action.cancel')  (Alt+C)"><i class="md-18 material-icons md-color-white">cancel</i>@lang('action.cancel')</a>
  </div>
</form>
@endsection

@push('context_action')
@include('action.context_2')
@endpush

@section('tabs')

@endsection
@section('scripts_up')
<script>
  jQuery(document).ready(function () {
      Ermis.per = <?= json_encode($per);?>;
      Ermis.link = "{{$key}}";
      Ermis.row_multiselect = 0;
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.elem = "#page_query";
  });
  </script>

@endsection
@section('scripts_end')
<script src="{{ url('addon/scripts/query.js') }}"></script>
@endsection
