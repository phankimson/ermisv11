@extends('layouts.default')
@section('title', 'Page Block')
@push('css_up')

@endpush

@push('css_down')

@endpush

@section('content')
<div id="page_content">
       <div id="page_content_inner">
        <div class="md-card uk-margin-medium-bottom" id="all">
            <div class="md-card-content">
             <h2>@lang('messages.you_not_permission')</h2>
            </div>
        </div>
      </div>
</div>
@endsection
@push('js_up')

@endpush

@push('js_down')


@endpush
