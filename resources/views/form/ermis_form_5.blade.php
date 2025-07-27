@extends('layouts.default_3')
@section('title',  $title )
@push('css_up')


@endpush

@push('css_down')


@endpush

@section('content')
@yield('form_window')
<div id="page_content">
<div id="page_content_inner">
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
                <div id="form-action">
                    @yield('content_add')
                </div>
            </div>
            <div class="uk-grid">
                <div class="uk-width-medium-2-4">
                        <span class="k-textbox k-space-right margin-bottom-20 medium">
                            <input type="text" placeholder="@lang('messages.enter_search_keyword')" id="search_tab1" />
                            <a href="javascript:;" style="right : 10px"  class="k-icon k-i-search" id="btn_search_tab1">&nbsp;</a>
                        </span>
                    <div id="grid_tab1"></div>
                </div>
                <div class="uk-width-medium-2-4">
                    <span class="k-textbox k-space-right margin-bottom-20 medium">
                            <input type="text" placeholder="@lang('messages.enter_search_keyword')" id="search_tab2" />
                            <a href="javascript:;" style="right : 10px"  class="k-icon k-i-search"id="btn_search_tab2">&nbsp;</a>
                    </span>
                    <div id="grid_tab2"></div>                   
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
