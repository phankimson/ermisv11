@extends('layouts.default')
@section('title',  $title )
@push('css_up')
<!-- additional styles for plugins -->
    <!-- weather icons -->
    <link rel="stylesheet" href="{{ asset('library/weather-icons/css/weather-icons.min.css')}}" media="all">
    <!-- metrics graphics (charts) -->
    <link rel="stylesheet" href="{{ asset('library/metrics-graphics/dist/metricsgraphics.css')}}">
    <!-- chartist -->
    <link rel="stylesheet" href="{{ asset('library/chartist/dist/chartist.min.css')}}">   
@endpush

@push('css_down')

@endpush

@push('action')

@endpush

@section('content')
<div id="page_content">
       <div id="page_content_inner">
           <!-- statistics (small charts) -->
           <div class="uk-grid uk-grid-width-large-1-4 uk-grid-width-medium-1-2 uk-grid-medium uk-sortable sortable-handler hierarchical_show" data-uk-sortable data-uk-grid-margin>
               <div>
                   <div class="md-card">
                       <div class="md-card-content">
                           <div class="uk-float-right uk-margin-top uk-margin-small-right"><span class="peity_visitors peity_data">5,3,9,6,5,9,7</span></div>
                           <span class="uk-text-muted uk-text-small">@lang('index.company')</span>
                           <h2 class="uk-margin-remove"><span class="countUpMe">0<noscript>{{$company}}</noscript></span></h2>
                       </div>
                   </div>
               </div>
               <div>
                   <div class="md-card">
                       <div class="md-card-content">
                           <div class="uk-float-right uk-margin-top uk-margin-small-right"><span class="peity_sale peity_data">5,3,9,6,5,9,7,3,5,2</span></div>
                           <span class="uk-text-muted uk-text-small">@lang('index.database')</span>
                           <h2 class="uk-margin-remove"><span class="countUpMe">0<noscript>{{$database}}</noscript></span></h2>
                       </div>
                   </div>
               </div>
               <div>
                   <div class="md-card">
                       <div class="md-card-content">
                           <div class="uk-float-right uk-margin-top uk-margin-small-right"><span class="peity_orders peity_data">64/100</span></div>
                           <span class="uk-text-muted uk-text-small">@lang('index.error')</span>
                           <h2 class="uk-margin-remove"><span class="countUpMe">0<noscript>{{$error}}</noscript></span></h2>
                       </div>
                   </div>
               </div>
               <div>
                   <div class="md-card">
                       <div class="md-card-content">
                           <div class="uk-float-right uk-margin-top uk-margin-small-right"><span class="peity_live peity_data">5,3,9,6,5,9,7,3,5,2,5,3,9,6,5,9,7,3,5,2</span></div>
                           <span class="uk-text-muted uk-text-small">@lang('index.user')</span>
                           <h2 class="uk-margin-remove" id="peity_live_text">{{$user}}</h2>
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
    <!-- d3 -->
    <script src="{{ asset('library/d3/d3.min.js') }}"></script>
    <!-- metrics graphics (charts) -->
    <script src="{{ asset('library/metrics-graphics/dist/metricsgraphics.min.js') }}"></script>
    <!-- chartist (charts) -->
    <script src="{{ asset('library/chartist/dist/chartist.min.js') }}"></script>
    <!-- maplace (google maps) -->
    <script src="http://maps.google.com/maps/api/js?sensor=true"></script>
    <script src="{{ asset('library/maplace-js/dist/maplace.min.js') }}"></script>
    <!-- peity (small charts) -->
    <script src="{{ asset('library/peity/jquery.peity.min.js') }}"></script>
    <!-- easy-pie-chart (circular statistics) -->
    <script src="{{ asset('library/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js') }}"></script>
    <!-- countUp -->
    <script src="{{ asset('library/countUp.js/countUp.min.js') }}"></script>
    <!-- handlebars.js -->
    <script src="{{ asset('library/handlebars/handlebars.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/handlebars_helpers.min.js') }}"></script>
    <!-- CLNDR -->
    <script src="{{ asset('library/clndr/src/clndr.js') }}"></script>
    <!-- fitvids -->
    <script src="{{ asset('library/fitvids/jquery.fitvids.js') }}"></script>
    
    <!--  dashbord functions -->
    <script src="{{ asset('assets/js/pages/dashboard.min.js') }}"></script>
@endpush
