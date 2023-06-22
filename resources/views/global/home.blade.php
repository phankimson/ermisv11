<!DOCTYPE html>
<html>

<head>
    <title>@lang('index.title_page')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="locale" content="{{ app()->getLocale() }}"/>
    <link href="{{ asset('addon/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('addon/css/site.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('library/kendoui/styles/kendo.common-material.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('library/kendoui/styles/kendo.material.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('library/kendoui/styles/kendo.material.mobile.min.css') }}" />

    <script src="{{ asset('library/kendoui/js/jquery.min.js') }}"></script>
    <script src="{{ asset('library/kendoui/js/kendo.all.min.js') }}"></script>
    <script src="{{ asset('library/kendoui/js/jszip.min.js') }}"></script>

    <link rel="icon" type="image/png" href="{{ asset('addon/img/icon.png') }}" sizes="16x16">
    <link rel="icon" type="image/png" href="{{ asset('addon/img/icon.png') }}" sizes="32x32">
</head>

<body>
    @include('includes.loading')
    <div class="container-fluid">
        <!--open container-->
        <div class="row row-offcanvas row-offcanvas-left">
            <div id="main-section" class="col-xs-12 column">
                <div id="main-section-header" class="row">
                    <a href="{{ url('/') }}"><img id="team-efficiency" class="col-xs-3" src="{{ url('addon/img/logo-big-blue.png') }}" alt="Ermis" />  </a>
                    <div id="dateFilter" class="col-md-9">
                        <label for="language">@lang('index.choose_language')</label>
                        <select id="language">
                            <option value="{{ url('/en') }}" {{$lang == 'en'?"selected":""}} >English</option>
                            <option value="{{ url('/vi') }}" {{$lang == 'vi'?"selected":""}}  >Vietnamese</option>
                        </select>
                    </div>
                    <div style="clear:both;"></div>
                </div>

                <div class="main-section-content row" style="">

                    <div id="solution-list" class="col col-xs-2">
                        <h3>@lang('index.solution')</h3>
                        @foreach ($software as $s)
                        <div id="solutions-list">
                            <div class="solution-wrapper">
                                <a href="{{ url($s->url) }}">
                                    <img src="{{ url($s->image) }}" class="img-responsive solution-list-image">
                                    <dl class="solution-list-details">
                                        <p class="name">{{ $s->name_en }}</p>
                                        <p class="title">{{ $s->name }}</p>
                                    </dl>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div id="solution-details-wrapper" class="col col-xs-10">
                        <div id="solution-details" class="row">
                            <div id="tabstrip">
                                <ul>
                                    @foreach ($software as $s)
                                     @if ($loop->first)
                                    <li class="k-state-active">
                                     @else
                                    <li>
                                     @endif
                                      Tính năng {{$s->name}}
                                    </li>
                                    @endforeach
                                </ul>
                                  @foreach ($software as $s)
                                <div>
                                    <p>{{$s->note}}</p>
                                </div>
                                  @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--close main column-->
        </div>
        <!--close row-->
    </div>
    <!--close container-->
    <script src="{{ asset('addon/scripts/index.js') }}"></script>
</body>

</html>
