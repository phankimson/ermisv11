@extends('layouts.default')
@section('title',  $title )
@push('css_up')
  <link rel="stylesheet" href="{{ asset('library/pagination/dist/bs-pagination.min.css')}}">
@endpush

@push('css_down')

@push('action')

@endpush

@endpush

@section('content')
<div id="page_content">
  <div id="page_content_inner">
        <div class="uk-form-stacked" id="user_edit_form" style="display:none">
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-large-7-10">
                    <div class="md-card">
                        <div class="user_heading" data-uk-sticky="{ top: 48, media: 960 }">
                            <div class="user_heading_avatar fileinput fileinput-new" data-provides="fileinput">
                              <form class="change-image" method="post"  role="form" enctype="multipart/form-data">
                                <div class="fileinput-new thumbnail">
                                    <img src="{{ Auth::user()->avatar != '' ? url(Auth::user()->avatar) : url('images/avatar.png')  }}" alt="user avatar" />
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail"></div>
                                <div class="user_avatar_controls">
                                    <span class="btn-file">
                                        <span class="fileinput-new"><i class="material-icons">&#xE2C6;</i></span>
                                        <span class="fileinput-exists"><i class="material-icons">&#xE86A;</i></span>
                                        <input type="file" name="user_edit_avatar_control" id="user_edit_avatar_control">
                                    </span>
                                    <a href="#" class="btn-file fileinput-exists" data-dismiss="fileinput"><i class="material-icons">&#xE5CD;</i></a>
                                </div>
                                  </form>
                            </div>
                            <div class="user_heading_content">
                                <h2 class="heading_b"><span class="uk-text-truncate" id="user_edit_uname">{{ Auth::user()->username }}</span><span class="sub-heading" id="user_edit_position">{{ Auth::user()->fullname }}</span></h2>
                            </div>
                            <div class="md-fab-wrapper">
                                <div class="md-fab md-fab-toolbar md-fab-small md-fab-accent">
                                    <i class="material-icons">&#xE8BE;</i>
                                    <div class="md-fab-toolbar-actions">
                                        <button type="submit" id="user_edit_save" data-uk-tooltip="{cls:'uk-tooltip-small',pos:'bottom'}" title="Save"><i class="material-icons md-color-white">&#xE161;</i></button>
                                        <a id="user_edit_back" data-uk-tooltip="{cls:'uk-tooltip-small',pos:'bottom'}" title="Back"><i class="material-icons md-color-white">exit_to_app</i></a>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="user_content">
                            <div id="notification"></div>
                            <ul id="user_edit_tabs" class="uk-tab" data-uk-tab="{connect:'#user_edit_tabs_content'}">
                                <li class="uk-active"><a href="#">@lang('index.change_info')</a></li>
                                <li><a href="#">@lang('index.change_password')</a></li>
                            </ul>
                            <ul id="user_edit_tabs_content" class="uk-switcher uk-margin">
                                <li id="user_edit_info_content">
                                    <form class="profile-update" role="form" method="post">
                                        <div class="uk-margin-top">
                                            <div class="uk-grid" data-uk-grid-margin>
                                                <div class="uk-width-medium-1-2">
                                                    <label class="control-label">@lang('user.fullname')</label>
                                                    <input class="md-input" type="text" value="{{ Auth::user()->fullname }}" name="fullname" />
                                                </div>
                                                <div class="uk-width-medium-1-2">
                                                    <label class="control-label">@lang('user.identity_card')</label>
                                                    <input class="md-input" type="text" value="{{ Auth::user()->identity_card }}" name="identity_card" />
                                                </div>
                                            </div>
                                            <div class="uk-grid" data-uk-grid-margin>
                                                <div class="uk-width-medium-1-2">
                                                    <label class="control-label">@lang('user.firstname')</label>
                                                    <input class="md-input" type="text" value="{{ Auth::user()->firstname }}" name="firstname" />
                                                </div>
                                                <div class="uk-width-medium-1-2">
                                                    <label class="control-label">@lang('user.lastname')</label>
                                                    <input class="md-input" type="text" value="{{ Auth::user()->lastname }}" name="lastname" />
                                                </div>
                                            </div>

                                            <div class="uk-grid" data-uk-grid-margin>
                                                <div class="uk-width-medium-1-2">
                                                    <label class="control-label">@lang('user.birthday')</label>
                                                    <input class="md-input" type="text" name="birthday" value="{{ Auth::user()->birthday->format('d/m/Y') }}" data-uk-datepicker="{format:'DD/MM/YYYY'}" />
                                                </div>
                                                <div class="uk-width-medium-1-2">
                                                    <label class="control-label">@lang('user.phone')</label>
                                                    <input class="md-input" type="text" value="{{ Auth::user()->phone }}" name="phone" />
                                                </div>
                                            </div>
                                            <div class="uk-grid" data-uk-grid-margin>
                                                <div class="uk-width-medium-1-2">
                                                    <label class="control-label">@lang('user.email')</label>
                                                    <input class="md-input" type="email" value="{{ Auth::user()->email }}" name="email" />
                                                </div>
                                                <div class="uk-width-medium-1-2">
                                                    <label class="control-label">@lang('user.address')</label>
                                                    <input class="md-input" type="text" value="{{ Auth::user()->address }}" name="address" />
                                                </div>
                                            </div>
                                            <div class="uk-grid" data-uk-grid-margin>
                                                <div class="uk-width-medium-1-2">
                                                    <label class="control-label">@lang('user.city')</label>
                                                    <input class="md-input" type="text" value="{{ Auth::user()->city }}" name="city" />
                                                </div>
                                                <div class="uk-width-medium-1-2">
                                                    <label class="control-label">@lang('user.jobs')</label>
                                                    <input class="md-input" type="text" value="{{ Auth::user()->jobs }}" name="jobs" />
                                                </div>
                                            </div>
                                            <div class="uk-grid" data-uk-grid-margin>
                                                <div class="uk-width-medium-1-1">
                                                    <select id="country" name="country" class="md-input">
                                                      @foreach($country as $c)
                                                      @if( Auth::user()->country == $c->id)
                                                        <option selected value="{{ $c->id }}">{{ $c->code }} - {{ $c->name }}</option>
                                                      @else
                                                        <option value="{{ $c->id }}">{{ $c->code }} - {{ $c->name }}</option>
                                                      @endif
                                                      @endforeach
                                                    </select>
                                                    <span class="uk-form-help-block control-label">@lang('user.country')</span>
                                                </div>
                                            </div>
                                            <div class="uk-grid">
                                                <div class="uk-width-1-1">
                                                    <label class="control-label">@lang('user.about')</label>
                                                    <textarea class="md-input" name="about" id="about" cols="30" rows="4">{{ Auth::user()->about }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        </form>
                                </li>
                                <li id="user_edit_changepass_content">
                                    <form class="change-password" role="form" method="post">
                                        <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-width-medium-1-1">
                                                <label class="control-label">@lang('global.password')</label>
                                                <input class="md-input" type="password" name="password" />
                                            </div>
                                        </div>
                                        <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-width-medium-1-1">
                                                <label class="control-label">@lang('global.npassword')</label>
                                                <input class="md-input" type="password" id="new_password" name="npassword" />
                                            </div>
                                        </div>
                                        <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-width-medium-1-1">
                                                <label class="control-label">@lang('global.rpassword')</label>
                                                <input class="md-input" type="password" name="rpassword" />
                                            </div>
                                        </div>
                                        </form>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="uk-width-large-3-10">
                    <div class="md-card">
                        <div class="md-card-content">
                            <h3 class="heading_c uk-margin-medium-bottom">@lang('index.settings')</h3>
                            <div class="uk-form-row">
                                <input type="checkbox" readonly checked data-switchery id="user_edit_active" />
                                <label for="user_edit_active" class="inline-label">@lang('index.active')</label>
                            </div>
                            <hr class="md-hr">
                            <div class="uk-form-row">
                                <label class="uk-form-label" for="user_edit_role">@lang('index.role')</label>
                                <select data-md-selectize>
                                    <option selected="{{ Auth::user()->role == '' ? 'selected' : '' }}" value="" disabled hidden >Select...</option>
                                    <option selected="{{ Auth::user()->role == 0 ? 'selected' : '' }}" value="0">Admin</option>
                                    <option selected="{{ Auth::user()->role == 1 ? 'selected' : '' }}" value="1">Manage</option>
                                    <option selected="{{ Auth::user()->role == 2 ? 'selected' : '' }}" value="2">User</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="uk-grid" data-uk-grid-margin data-uk-grid-match id="user_profile">
        <div class="uk-width-large-7-10">
            <div class="md-card">
                <div class="user_heading">
                    <div class="user_heading_menu hidden-print">
                        <div class="uk-display-inline-block" data-uk-dropdown="{pos:'left-top'}">
                            <i class="md-icon material-icons md-icon-light">&#xE5D4;</i>
                            <div class="uk-dropdown uk-dropdown-small">
                                <ul class="uk-nav">
                                    <li><a href="#">Action 1</a></li>
                                    <li><a href="#">Action 2</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="uk-display-inline-block"><i class="md-icon md-icon-light material-icons" id="page_print">&#xE8ad;</i></div>
                    </div>
                    <div class="user_heading_avatar">
                        <div class="thumbnail">
                            <img src="{{ Auth::user()->avatar != "" ? url(Auth::user()->avatar) : url('images/avatar.png') }}" alt="user avatar" />
                        </div>
                    </div>
                    <div class="user_heading_content">
                        <h2 class="heading_b uk-margin-bottom"><span class="uk-text-truncate">{{ Auth::user()->username }}</span><span class="sub-heading">{{ Auth::user()->fullname }}</span></h2>

                    </div>
                    <a class="md-fab md-fab-small md-fab-accent hidden-print" id="change_user_edit" href="javascript:;">
                        <i class="material-icons">&#xE150;</i>
                    </a>
                </div>
                <div class="user_content">
                    <ul id="user_profile_tabs" class="uk-tab" data-uk-tab="{connect:'#user_profile_tabs_content', animation:'slide-horizontal'}" data-uk-sticky="{ top: 48, media: 960 }">
                        <li class="uk-active"><a href="javascript:;">@lang('index.infomation')</a></li>
                        <li><a href="javascript:;"> @lang('index.history')</a></li>
                    </ul>
                    <ul id="user_profile_tabs_content" class="uk-switcher uk-margin">
                        <li>
                            {{ Auth::user()->about }}
                              <div class="uk-grid uk-margin-medium-top uk-margin-large-bottom" data-uk-grid-margin>
                                <div class="uk-width-large-1-2">
                                    <h4 class="heading_c uk-margin-small-bottom"> @lang('index.contact_info')</h4>
                                    <ul class="md-list md-list-addon">
                                    <li>
                                        <div class="md-list-addon-element">
                                            <i class="md-list-addon-icon material-icons">nfc</i>
                                        </div>
                                        <div class="md-list-content">
                                            <span class="md-list-heading">{{ Auth::user()->barcode }}</span>
                                            <span class="uk-text-small uk-text-muted">@lang('user.barcode') </span>
                                        </div>
                                    </li>
                                        <li>
                                            <div class="md-list-addon-element">
                                                <i class="md-list-addon-icon material-icons">&#xE158;</i>
                                            </div>
                                            <div class="md-list-content">
                                                <span class="md-list-heading">{{ Auth::user()->email }}</span>
                                                <span class="uk-text-small uk-text-muted">@lang('user.email')</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="md-list-addon-element">
                                                <i class="md-list-addon-icon material-icons">&#xE0CD;</i>
                                            </div>
                                            <div class="md-list-content">
                                                <span class="md-list-heading">{{ Auth::user()->phone }}</span>
                                                <span class="uk-text-small uk-text-muted"> @lang('user.phone') </span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="md-list-addon-element">
                                                <i class="md-list-addon-icon material-icons">home</i>
                                            </div>
                                            <div class="md-list-content">
                                                <span class="md-list-heading">{{ Auth::user()->address }}</span>
                                                <span class="uk-text-small uk-text-muted"> @lang('user.address') </span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="md-list-addon-element">
                                                <i class="md-list-addon-icon material-icons">work</i>
                                            </div>
                                            <div class="md-list-content">
                                                <span class="md-list-heading">{{ Auth::user()->jobs }}</span>
                                                <span class="uk-text-small uk-text-muted">@lang('user.jobs') </span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="uk-width-large-1-2">
                                    <h4 class="heading_c uk-margin-small-bottom">@lang('index.other')</h4>
                                    <ul class="md-list">
                                    <li>
                                        <div class="md-list-content">
                                            <span class="md-list-heading">{{ Auth::user()->created_at->format('d/m/Y') }}</span>
                                            <span class="uk-text-small uk-text-muted"> @lang('user.created')</span>
                                        </div>
                                    </li>
                                        <li>
                                            <div class="md-list-content">
                                                <span class="md-list-heading"> {{ Auth::user()->birthday->format('d/m/Y') }}</span>
                                                <span class="uk-text-small uk-text-muted">@lang('user.birthday') </span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="md-list-content">
                                                <span class="md-list-heading">{{ Auth::user()->identity_card }}</span>
                                                <span class="uk-text-small uk-text-muted">@lang('user.identity_card')</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="md-list-content">
                                                <span class="md-list-heading">{{ Auth::user()->city }}</span>
                                                <span class="uk-text-small uk-text-muted">@lang('user.city')</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="md-list-content">
                                                <span class="md-list-heading">{{ $country->find(Auth::user()->country)->name }}</span>
                                                <span class="uk-text-small uk-text-muted">@lang('user.country')</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li>
                            <ul class="md-list" id="pagination_history_action_content">
                                <li style="display:none">
                                   <div class="md-list-content">
                                      <span class="md-list-heading"><a href="javascript:;" id="history_action_title">Abc</a></span>
                                      <div class="uk-margin-small-top">
                                                <span class="uk-margin-right">
                                                    <i class="material-icons">&#xE192;</i> <span class="uk-text-muted uk-text-small" id="history_action_timer"></span>
                                                </span>
                                      </div>
                                   </div>
                                </li>
                            </ul>
                            <div id="pagination_history_action" class="pagination"></div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="uk-width-large-3-10 hidden-print">
            <div class="md-card">
                <div class="md-card-content">
                    <div class="uk-margin-medium-bottom">
                        <h3 class="heading_c uk-margin-bottom">@lang('index.history')</h3>
                        <ul class="md-list md-list-addon">
                          @foreach($history_action as $h)
                          <li>
                             <div class="md-list-addon-element">
                              @if($h->type == 2)
                              <i class="md-list-addon-icon material-icons uk-text-warning">&#xE8B2;</i>
                              @elseif($h->type == 3)
                              <i class="md-list-addon-icon material-icons uk-text-success">&#xE88F;</i>
                              @elseif($h->type == 4)
                              <i class="md-list-addon-icon material-icons uk-text-danger">&#xE001;</i>
                              @endif
                             </div>
                              <div class="md-list-content">
                                @if($h->type == 2)
                                <span class="md-list-heading">@lang('action.add')</span>
                                <span class="uk-text-small uk-text-muted uk-text-truncate">{{ $lang == 'vi'? $h->menus->name : $h->menus->name_en }}</span>
                                @elseif($h->type == 3)
                                <span class="md-list-heading">@lang('action.edit')</span>
                                <span class="uk-text-small uk-text-muted uk-text-truncate">{{ $lang == 'vi'? $h->menus->name : $h->menus->name_en }}</span>
                                @elseif($h->type == 4)
                                <span class="md-list-heading">@lang('action.delete')</span>
                                <span class="uk-text-small uk-text-muted uk-text-truncate">{{ $lang == 'vi'? $h->menus->name : $h->menus->name_en }}</span>
                                @endif
                              </div>
                          </li>
                          @endforeach
                        </ul>
                    </div>
                    <a class="md-btn md-btn-flat md-btn-flat-primary" href="#">@lang('action.view')</a>
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
<script src="{{ url('library/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ url('assets/js/custom/uikit_fileinput.min.js') }}"></script>
<script src="{{ url('library/pagination/dist/pagination.min.js')}}"></script>
<script src="{{ url('addon/scripts/profile.js') }}"></script>
<script>
 Profile.total = {{$count_history_action}};
</script>
@endpush
