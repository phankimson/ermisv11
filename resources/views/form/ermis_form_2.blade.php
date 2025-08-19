@extends('layouts.default_3')
@section('title',  $title )
@push('css_up')


@endpush

@push('css_down')


@endpush

@section('content')
<div id="page_content">
<div id="page_content_inner">
<div id="export" aria-hidden="false"></div>
<div id="form-window-extra" style="display:none" aria-hidden="false">
    @stack('export_extra')
</div>
    <div id="import" aria-hidden="false"></div>

    <div class="md-card uk-margin-medium-bottom">
        <div class="md-card-toolbar">
            <div class="md-card-toolbar-heading-text">
                {{ $toolbar }}
            </div>
            @stack('toolbar_action')
        </div>
        <div class="md-card-content">
            <div class="uk-grid">
                <div class="uk-width-medium-4-4">
                    <div id="grid"></div>
                </div>
                    <div id="form-action">
                        @yield('content_add')
                    </div>
            </div>
                @yield('tabs')
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
    @yield('scripts_end')
    @endpush
