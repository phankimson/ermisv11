@extends('layouts.default_1')
@section('title',  $title )
@push('css_up')

@endpush

@push('css_down')

@push('action')

@endpush

@endpush

@section('content')
<div id="page_content">
        <div id="page_content_inner">

            <div class="uk-width-medium-8-10 uk-container-center reset-print">

                <div class="uk-grid uk-grid-collapse" data-uk-grid-margin>
                    <div class="uk-width-large-3-10 hidden-print uk-visible-large">
                        <div class="md-list-outside-wrapper">
                            <ul class="md-list md-list-outside notes_list" id="notes_list">
                              <li class="hidden" >
                                  <a href="javascript:;" class="md-list-content note_link" data-note-id="0">
                                      <span class="md-list-heading uk-text-truncate"></span>
                                      <span class="uk-text-small uk-text-muted"> </span>
                                  </a>
                              </li>
                               @foreach($notes as $n)
                               @if($loop->iteration == 1)
                                 <li class="heading_list uk-text-danger append-new">@lang('global.new')</li>
                               @elseif($loop->iteration == 10)
                                 <li class="heading_list uk-text-danger">@lang('global.old')</li>
                               @endif
                               <li>
                                   <a href="javascript:;" class="md-list-content note_link" data-note-id="{{$n->id}}">
                                       <span class="md-list-heading uk-text-truncate">{{$n->title}}</span>
                                       <span class="uk-text-small uk-text-muted"> {{ $n->created_at->format('d/m/Y') }}</span>
                                   </a>
                               </li>
                               @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="uk-width-large-7-10">
                        <div class="md-card md-card-single">
                            <form action="" id="form-action">
                                <div class="md-card-toolbar hidden-print">
                                    <div class="md-card-toolbar-actions">
                                        <a href="javascript:;" class="add"><i class="md-icon material-icons">add</i></a>
                                        <a href="javascript:;" class="save"><i class="md-icon material-icons">&#xE161;</i></a>
                                        <a href="javascript:;" class="delete"><i class="md-icon material-icons">&#xE872;</i></a>
                                        <!--<div class="md-card-dropdown" data-uk-dropdown="{pos:'bottom-right'}">
                                            <i class="md-icon material-icons">&#xE5D4;</i>
                                            <div class="uk-dropdown uk-dropdown-small">
                                                <ul class="uk-nav">
                                                    <li><a href="javascript:;"><i class="material-icons uk-margin-small-right">&#xE80D;</i> @lang('action.share')</a></li>
                                                    <li><a href="javascript:;" class="delete"><i class="material-icons uk-margin-small-right delete">&#xE872;</i> @lang('action.delete')</a></li>
                                                </ul>
                                            </div>
                                        </div>-->
                                    </div>
                                    <input name="title" class="md-card-toolbar-input not-null" type="text" value="" placeholder="@lang('notes.add_title') *" />
                                </div>
                                <div class="md-card-content">
                                    <textarea class="textarea_autosize md-input" name="message" cols="30" rows="12" placeholder="@lang('notes.add_content')"></textarea>
                                    <input class="switchery" type="checkbox" data-switchery data-switchery-size="large" name="active" checked />
                                    <label for="note_switch" class="inline-label">@lang('action.active')</label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <div class="md-fab-wrapper">
        <a class="md-fab md-fab-danger add" href="javascript:;">
            <i class="material-icons">&#xE145;</i>
        </a>
    </div>

    <div id="sidebar_secondary">
        <div class="sidebar_secondary_wrapper uk-margin-remove"></div>
    </div>
@endsection
@push('js_up')

@endpush

@push('js_down')
<script>
  jQuery(document).ready(function () {
      Ermis.per = <?= json_encode($per);?>;
      Ermis.link = "{{$key}}";
      Ermis.row_multiselect = 0;
      Ermis.short_key = "{{ config('app.short_key')}}";
      Ermis.elem = "#form-action";
  });
  </script>
<script src="{{ url('library/kendoui/js/jszip.min.js') }}"></script>
<script src="{{ url('library/shortcuts/shortcuts.js') }}"></script>
<!--  notes functions -->
<script src="{{ url('addon/scripts/ermis/ermis-2.js') }}"></script>
@endpush
