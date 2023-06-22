@extends('layouts.default_3')
@section('title',  $title )
@push('css_up')


@endpush

@push('css_down')


@endpush

@section('content')
<div id="page_content">
<div id="page_content_inner">
    <div class="md-card uk-margin-medium-bottom">
        <div class="md-card-toolbar">
            <div class="md-card-toolbar-heading-text">
                {{ $toolbar }}
            </div>
            @stack('toolbar_action')
        </div>
        <div class="md-card-content">
              @yield('content_add')
        </div>
    </div>
  </div>
  </div>
    @stack('context_action')
    @endsection

    @push('js_up')

    @endpush

    @push('js_down')
    @yield('scripts_up')
    <script src="{{ url('library/kendoui/js/jszip.min.js') }}"></script>
    <script src="{{ url('library/shortcuts/shortcuts.js') }}"></script>
    <script src="{{ url('library/moment/min/moment.min.js') }}"></script>
    <script src="{{ url('library/jquery.print/jQuery.print.js') }}"></script>
    @yield('scripts_end')
    @endpush
