@extends('layouts.default')
@section('title',  $title )
@push('css_up')

@endpush

@push('css_down')

@push('action')
@include('action.action_1')
@endpush

@endpush

@section('content')
<div id="page_content">
        <div id="page_content_inner">
            <h4 class="heading_a uk-margin-bottom">@lang('system.basic_settings')</h4>
            <form action="" class="uk-form-stacked" id="page_settings">
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-large-1-3 uk-width-medium-1-1">
                        <div class="md-card">
                            <div class="md-card-content">
                                <div class="uk-form-row">
                                    <label for="settings">{{$sys->firstWhere('code','DATE_USE_FREE')->name}}</label>
                                    <input class="md-input" type="number" name="DATE_USE_FREE" value-default="{{$sys->firstWhere('code','DATE_USE_FREE')->value}}" value="{{$sys->firstWhere('code','DATE_USE_FREE')->value}}"/>
                                </div>
                                <div class="uk-form-row">
                                    <label for="settings">{{$sys->firstWhere('code','MAX_TIMELINE')->name}}</label>
                                    <input class="md-input" type="number" name="MAX_TIMELINE" value-default="{{$sys->firstWhere('code','MAX_TIMELINE')->value}}" value="{{$sys->firstWhere('code','MAX_TIMELINE')->value}}"/>
                                </div>
                                <div class="uk-form-row">
                                    <label for="settings">{{$sys->firstWhere('code','MAX_LOAD_CHAT')->name}}</label>
                                    <input class="md-input" type="number" name="MAX_LOAD_CHAT" value-default="{{$sys->firstWhere('code','MAX_LOAD_CHAT')->value}}"  value="{{$sys->firstWhere('code','MAX_LOAD_CHAT')->value}}"/>
                                </div>
                                <div class="uk-form-row">
                                    <label for="settings">{{$sys->firstWhere('code','MAX_RANDOM')->name}}</label>
                                    <input class="md-input" type="number" name="MAX_RANDOM" value-default="{{$sys->firstWhere('code','MAX_RANDOM')->value}}"  value="{{$sys->firstWhere('code','MAX_RANDOM')->value}}"/>
                                </div>
                                <div class="uk-form-row">
                                    <label for="settings">{{$sys->firstWhere('code','PAGESIZE')->name}}</label>
                                    <input class="md-input" type="number" name="PAGESIZE" value-default="{{$sys->firstWhere('code','PAGESIZE')->value}}"  value="{{$sys->firstWhere('code','PAGESIZE')->value}}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-large-1-3 uk-width-medium-1-2">
                        <div class="md-card">
                            <div class="md-card-content">
                              <div class="uk-form-row">
                                  <label for="settings">{{$sys->firstWhere('code','PATH_UPLOAD_AVATAR')->name}}</label>
                                  <input class="md-input" type="text" name="PATH_UPLOAD_AVATAR" value-default="{{$sys->firstWhere('code','PATH_UPLOAD_AVATAR')->value}}" value="{{$sys->firstWhere('code','PATH_UPLOAD_AVATAR')->value}}"/>
                              </div>
                              <div class="uk-form-row">
                                  <label for="settings">{{$sys->firstWhere('code','PATH_UPLOAD_SOFTWARE')->name}}</label>
                                  <input class="md-input" type="text" name="PATH_UPLOAD_SOFTWARE" value-default="{{$sys->firstWhere('code','PATH_UPLOAD_SOFTWARE')->value}}" value="{{$sys->firstWhere('code','PATH_UPLOAD_SOFTWARE')->value}}"/>
                              </div>
                                <!--<ul class="md-list">
                                    <li>
                                        <div class="md-list-content">
                                            <div class="uk-float-right">
                                                <input type="checkbox" data-switchery checked id="settings_site_online" name="settings_site_online" />
                                            </div>
                                            <span class="md-list-heading">Site Online</span>
                                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="md-list-content">
                                            <div class="uk-float-right">
                                                <input type="checkbox" data-switchery id="settings_seo" name="settings_seo" />
                                            </div>
                                            <span class="md-list-heading">Search Engine Friendly URLs</span>
                                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="md-list-content">
                                            <div class="uk-float-right">
                                                <input type="checkbox" data-switchery id="settings_url_rewrite" name="settings_url_rewrite" />
                                            </div>
                                            <span class="md-list-heading">Use URL rewriting</span>
                                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                                        </div>
                                    </li>
                                </ul>-->
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-large-1-3 uk-width-medium-1-2">
                        <div class="md-card">
                            <div class="md-card-content">
                                <!--  <ul class="md-list">
                                    <li>
                                        <div class="md-list-content">
                                            <div class="uk-float-right">
                                                <input type="checkbox" data-switchery data-switchery-color="#7cb342" checked id="settings_top_bar" name="settings_top_bar" />
                                            </div>
                                            <span class="md-list-heading">Top Bar Enabled</span>
                                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="md-list-content">
                                            <div class="uk-float-right">
                                                <input type="checkbox" data-switchery data-switchery-color="#7cb342" id="settings_api" name="settings_api" />
                                            </div>
                                            <span class="md-list-heading">Api Enabled</span>
                                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="md-list-content">
                                            <div class="uk-float-right">
                                                <input type="checkbox" data-switchery data-switchery-color="#d32f2f" id="settings_minify_static" checked name="settings_minify_static" />
                                            </div>
                                            <span class="md-list-heading">Minify JS files automatically</span>
                                            <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                                        </div>
                                    </li>
                                </ul>-->
                            </div>
                        </div>
                    </div>
                </div>


                <div class="md-fab-wrapper">
                    <a class="md-fab md-fab-primary save" href="javascript:;" id="page_settings_submit">
                        <i class="material-icons">&#xE161;</i>
                    </a>
                    <a class="md-fab md-fab-warning cancel" href="javascript:;" id="page_settings_submit">
                        <i class="material-icons">cancel</i>
                    </a>
                </div>

            </form>

        </div>
    </div>
@endsection
@push('js_up')

@endpush

@push('js_down')
<script>
  jQuery(document).ready(function () {
      Ermis.per = <?= json_encode($per);?>;
      Ermis.link = "{{$key}}";
      Ermis.elem = "#page_settings";
      Ermis.short_key = "{{ config('app.short_key')}}";
  });
  </script>
<script src="{{ url('library/kendoui/js/kendo.all.min.js') }}"></script>
<script src="{{ url('library/kendoui/js/jszip.min.js') }}"></script>
<script src="{{ url('library/shortcuts/shortcuts.js') }}"></script>
<script src="{{ url('addon/scripts/ermis/ermis-1.js') }}"></script>
@endpush
