    <!-- main header -->
    <header id="header_main">
        <div class="header_main_content">
            <nav class="uk-navbar">

                <!-- main sidebar switch -->
                <a href="#" id="sidebar_main_toggle" class="sSwitch sSwitch_left">
                    <span class="sSwitchIcon"></span>
                </a>

                <!-- secondary sidebar switch -->
                <a href="#" id="sidebar_secondary_toggle" class="sSwitch sSwitch_right sidebar_secondary_check">
                    <span class="sSwitchIcon"></span>
                </a>
                 @stack('action')
                    <div id="menu_top_dropdown" class="uk-float-left uk-hidden-small">
                        <div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
                            <a href="#" class="top_menu_toggle"><i class="material-icons md-24">&#xE8F0;</i></a>
                            <div class="uk-dropdown uk-dropdown-width-3">
                                <div class="uk-grid uk-dropdown-grid" data-uk-grid-margin>
                                    <div class="uk-width-2-3">
                                        <div class="uk-grid uk-grid-width-medium-1-3 uk-margin-top uk-margin-bottom uk-text-center" data-uk-grid-margin>
                                            <a href="{{url($lang.'/acc/open-balance')}}">
                                                <i class="material-icons md-36">input_circle</i>
                                                <span class="uk-text-muted uk-display-block">@lang('acc_index.open_balance')</span>
                                            </a>
                                            <a href="{{url($lang.'/acc/accounted-fast')}}">
                                                <i class="material-icons md-36">unfold_less_double</i>
                                                <span class="uk-text-muted uk-display-block">@lang('acc_index.accounted_fast')</span>
                                            </a>
                                            <a href="{{url($lang.'/acc/accounted-auto')}}">
                                                <i class="material-icons md-36 md-color-red-600">&#xE86F;</i>
                                                <span class="uk-text-muted uk-display-block">@lang('acc_index.accounted_auto')</span>
                                            </a>
                                            <a href="{{url($lang.'/acc/period')}}">
                                                <i class="material-icons md-36">lock_open</i>
                                                <span class="uk-text-muted uk-display-block">@lang('acc_index.period')</span>
                                            </a>
                                            <a href="{{url($lang.'/acc/number-code')}}">
                                                <i class="material-icons md-36">exposure_plus_1</i>
                                                <span class="uk-text-muted uk-display-block">@lang('acc_index.number_code')</span>
                                            </a>
                                            <a href="{{url($lang.'/acc/print-template')}}">
                                                <i class="material-icons md-36">content_paste</i>
                                                <span class="uk-text-muted uk-display-block">@lang('acc_index.print_template')</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="uk-width-1-3">
                                        <ul class="uk-nav uk-nav-dropdown uk-panel">
                                            <li class="uk-nav-header">@lang('acc_index.support')</li>
                                            <li><a href="{{url($lang.'/acc')}}">Tính thuế nhà thầu</a></li>
                                            <li><a href="{{url($lang.'/acc')}}">Tính giao dịch liên kết</a></li>                  
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="uk-navbar-flip">
                    <ul class="uk-navbar-nav user_actions">
                        <li><a href="#" id="full_screen_toggle" class="user_action_icon uk-visible-large"><i class="material-icons md-24 md-light">&#xE5D0;</i></a></li>
                        <li><a href="#" id="main_search_btn" class="user_action_icon"><i class="material-icons md-24 md-light">&#xE8B6;</i></a></li>
                        <li data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                            <a href="#" class="user_action_icon"><i class="material-icons md-24 md-light">&#xE7F4;</i><span class="uk-badge">16</span></a>
                            <div class="uk-dropdown uk-dropdown-xlarge">
                                <div class="md-card-content">
                                    <ul class="uk-tab uk-tab-grid" data-uk-tab="{connect:'#header_alerts',animation:'slide-horizontal'}">
                                        <li class="uk-width-1-2 uk-active"><a href="#" class="js-uk-prevent uk-text-small">Messages (12)</a></li>
                                        <li class="uk-width-1-2"><a href="#" class="js-uk-prevent uk-text-small">Alerts (4)</a></li>
                                    </ul>
                                    <ul id="header_alerts" class="uk-switcher uk-margin">
                                        <li>
                                            <ul class="md-list md-list-addon">
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <span class="md-user-letters md-bg-cyan">jx</span>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading"><a href="pages_mailbox.html">Iure et nam.</a></span>
                                                        <span class="uk-text-small uk-text-muted">Vel aut officiis commodi veritatis dicta.</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <img class="md-user-image md-list-addon-avatar" src="{{url('addon/img/avatar.png')}}" alt="avatar"/>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading"><a href="pages_mailbox.html">Voluptate explicabo.</a></span>
                                                        <span class="uk-text-small uk-text-muted">Doloribus qui expedita debitis saepe quidem vel impedit dolore dolore qui est.</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <span class="md-user-letters md-bg-light-green">lm</span>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading"><a href="pages_mailbox.html">Minima perspiciatis.</a></span>
                                                        <span class="uk-text-small uk-text-muted">Quas cumque doloremque ab aut aut quia veritatis.</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <img class="md-user-image md-list-addon-avatar" src="{{url('addon/img/avatar.png')}}" alt="avatar"/>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading"><a href="pages_mailbox.html">Aperiam dolorum corporis.</a></span>
                                                        <span class="uk-text-small uk-text-muted">Delectus dicta eaque illum ad nemo.</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <img class="md-user-image md-list-addon-avatar" src="{{url('addon/img/avatar.png')}}" alt="avatar"/>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading"><a href="pages_mailbox.html">Non harum unde.</a></span>
                                                        <span class="uk-text-small uk-text-muted">Sequi molestias non provident similique adipisci ullam.</span>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="uk-text-center uk-margin-top uk-margin-small-bottom">
                                                <a href="page_mailbox.html" class="md-btn md-btn-flat md-btn-flat-primary js-uk-prevent">Show All</a>
                                            </div>
                                        </li>
                                        <li>
                                            <ul class="md-list md-list-addon">
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <i class="md-list-addon-icon material-icons uk-text-warning">&#xE8B2;</i>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading">Sit doloribus voluptas.</span>
                                                        <span class="uk-text-small uk-text-muted uk-text-truncate">Harum velit assumenda dolorem ducimus ut officiis.</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <i class="md-list-addon-icon material-icons uk-text-success">&#xE88F;</i>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading">Molestias libero.</span>
                                                        <span class="uk-text-small uk-text-muted uk-text-truncate">Magnam enim quia aut tenetur error numquam.</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <i class="md-list-addon-icon material-icons uk-text-danger">&#xE001;</i>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading">Et et.</span>
                                                        <span class="uk-text-small uk-text-muted uk-text-truncate">Aliquam eius id qui illum.</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <i class="md-list-addon-icon material-icons uk-text-primary">&#xE8FD;</i>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading">Non ad.</span>
                                                        <span class="uk-text-small uk-text-muted uk-text-truncate">Et inventore aperiam velit.</span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                            <a href="javascripts:;" class="user_action_image"><img class="md-user-image" src="{{ asset(Auth::user()->avatar) }}" alt="{{Auth::user()->username}}"/> {{Auth::user()->username}}</a>
                            <div class="uk-dropdown uk-dropdown-small">
                                <ul class="uk-nav js-uk-prevent">
                                    <li><a href="{{ url('/'.$lang.'/'.$manage.'/profile') }}">@lang('index.my_profile')</a></li>
                                    <li><a href="{{ url('/'.$lang.'/'.$manage.'/setting') }}">@lang('index.settings')</a></li>
                                    <li><a href="{{ url('/logout') }}">@lang('index.logout')</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="header_main_search_form">
            <i class="md-icon header_main_search_close material-icons">&#xE5CD;</i>
            <form class="uk-form">
                <input type="text" class="header_main_search_input" />
                <a href="javascripts:;" class="header_main_search_btn uk-button-link"><i class="md-icon material-icons">&#xE8B6;</i></a>
            </form>
        </div>
    </header><!-- main header end -->
