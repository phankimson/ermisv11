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
                    <h3 class="heading_a">Character Counter</h3>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-3">
                            <label>Default</label>
                            <input type="text" class="input-count md-input" id="input_counter" maxlength="60" />
                        </div>
                        <div class="uk-width-medium-1-3">
                            <label>Error</label>
                            <input type="text" class="md-input md-input-danger input-count" maxlength="40" value="Something wrong" />
                        </div>
                        <div class="uk-width-medium-1-3">
                            <label>Success</label>
                            <input type="text" class="md-input md-input-success input-count" maxlength="40" value="All ok" />
                        </div>
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
@endpush
