@extends('layouts.default')
@section('title',  $title )
@push('css_up')
<!-- additional styles for plugins -->
     <!-- htmleditor (codeMirror) -->
    <link rel="stylesheet" href="{{ asset('library/codemirror/lib/codemirror.css')}}" media="all">
     <!-- uikit -->
    <link rel="stylesheet" href="{{ asset('library/uikit/css/uikit.almost-flat.min.css')}}">
      <!-- flag icons -->
    <link rel="stylesheet" href="{{ asset('assets/icons/flags/flags.min.css')}}">
    <!-- altair admin -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.min.css')}}" media="all">
@endpush

@push('css_down')

@endpush

@push('action')

@endpush

@section('content')
<div id="page_content">
       <div id="page_content_inner">
            <div class="md-card">
                <div class="md-card-content">
                    <h3 class="heading_a">@lang('update_database.info_database')</h3>
                    <div class="uk-grid info-database margin-bottom-10" data-uk-grid-margin>
                        <div class="uk-width-medium-1-4">
                            <label>@lang('update_database.host')</label>
                            <input type="text" class="input-count md-input" value="localhost" name="host" maxlength="60" />
                        </div>
                        <div class="uk-width-medium-1-4">
                            <label>@lang('update_database.database')</label>
                            <input type="text" class="input-count md-input" value="ermis" name="database" maxlength="60" />
                        </div>
                        <div class="uk-width-medium-1-4">
                            <label>@lang('login.username')</label>
                            <input type="text" class="input-count md-input" value="root" name="username" maxlength="60" />
                        </div>
                        <div class="uk-width-medium-1-4">
                            <label>@lang('login.password')</label>
                            <input type="password" class="input-count md-input" value="" name="password" maxlength="60" />
                        </div>
                        <div class="uk-width-medium-1-3 item_table_add">
                        <div class="hidden item_table">
                            <label>test</label>
                            <input type="checkbox" data-md-icheck="" name="check" />
                                <select class="droplist large" name="check_droplist">
                                        <option selected value="1">id</option>
                                        <option value="2">code</option>
                                        <option value="3">name</option>
                                </select>
                            </div>
                        </div>                      
                    </div>

                    <div id="notification"></div>
                    <div class="pull-right uk-margin-top">
                        <a class="md-btn md-btn-primary md-btn-wave-light waves-effect waves-button waves-light load tooltips" >@lang('action.load')</a>
                        <a class="md-btn md-btn-warning md-btn-wave-light waves-effect waves-button waves-light start tooltips">@lang('action.start')</a>                
                    </div>
                </div>
            </div>
       </div>
   </div>

@endsection
@push('js_up')

@endpush

@push('js_down')
 <!-- page specific plugins -->
    <!-- ionrangeslider -->
    <script src="{{ asset('library/ion.rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <!-- htmleditor (codeMirror) -->
    <script src="{{ asset('assets/js/uikit_htmleditor_custom.min.js') }}"></script>
    <!-- inputmask-->
    <script src="{{ asset('library/jquery.inputmask/dist/jquery.inputmask.bundle.js') }}"></script>

    <!--  forms advanced functions -->
    <script src="{{ asset('assets/js/pages/forms_advanced.min.js') }}"></script>
    
    <script>
        $(function() {
            // enable hires images
            altair_helpers.retina_images();
            // fastClick (touch devices)
            if(Modernizr.touch) {
                FastClick.attach(document.body);
            }
        });
    </script>
    <script src="{{ url('addon/scripts/update_database.js') }}"></script>
@endpush
