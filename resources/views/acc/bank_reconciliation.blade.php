
@extends('form.ermis_form_5')

@push('css_up')

@endpush

@push('action')
@include('action.top_menu_8')
@endpush

@push('toolbar_action')
@include('action.toolbar_1')
@endpush

@section('content_add')

@endsection

@push('context_action')
@include('action.context_1')
@endpush

@section('tabs')

@endsection
@section('scripts_up')

@endsection
@section('scripts_end')
@if($decimal_symbol === ".")
<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.en-DE.min.js') }}"></script>
<script>kendo.culture('en-DE')</script>
@endif
@endsection
