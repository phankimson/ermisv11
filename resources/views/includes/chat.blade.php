
    <!-- secondary sidebar -->
    <aside id="sidebar_secondary" class="tabbed_sidebar">
        <ul class="uk-tab uk-tab-icons uk-tab-grid" data-uk-tab="{connect:'#dashboard_sidebar_tabs', animation:'slide-horizontal'}">
            <li class="uk-active uk-width-1-3"><a href="javascript:;"><i class="material-icons">&#xE422;</i></a></li>
            <li class="uk-width-1-3 chat_sidebar_tab"><a href="javascript:;"><i class="material-icons">&#xE0B7;</i></a></li>
            <li class="uk-width-1-3"><a href="javascript:;"><i class="material-icons">&#xE8B9;</i></a></li>
        </ul>

        <div class="scrollbar-inner">
            <ul id="dashboard_sidebar_tabs" class="uk-switcher">
                <li>
                  <div class="uk-flex uk-height-medium uk-background-muted uk-margin uk-text-center">
                    <div class="uk-margin-auto uk-margin-auto-vertical uk-width-1-2@s uk-card uk-card-default uk-card-body"><a id="add-event" class="k-button k-primary" >@lang('index.add_event')</a></div>
                    </div>
                    <div class="timeline timeline_small uk-margin-bottom">
                      <div class="timeline_item" style="display : none">
                         <div class="timeline_icon timeline_icon_success"><i class="material-icons">&#xE85D;</i></div>
                         <div class="timeline_date">
                             09<span>Oct</span>
                         </div>
                         <div class="timeline_content">Created ticket <a href="#"><strong>#3289</strong></a></div>
                         <div class="timeline_content_addon">
                                <blockquote>

                                </blockquote>
                            </div>
                     </div>
                     @foreach($timeline as $t)
                       <div class="timeline_item">
                            @if($t->type == 1 )
                           <div class="timeline_icon timeline_icon_success"><i class="material-icons">&#xE85D;</i></div>
                            @elseif($t->type == 2 )
                           <div class="timeline_icon timeline_icon_danger"><i class="material-icons">&#xE5CD;</i></div>
                           @elseif($t->type == 3 )
                           <div class="timeline_icon"><i class="material-icons">&#xE410;</i></div>
                           @elseif($t->type == 4 )
                           <div class="timeline_icon timeline_icon_primary"><i class="material-icons">&#xE0B9;</i></div>
                           @else
                           <div class="timeline_icon timeline_icon_warning"><i class="material-icons">&#xE7FE;</i></div>
                           @endif
                           <div class="timeline_date">
                               {{ $t->created_at->format('d/m/Y') }}
                           </div>
                           <div class="timeline_content">{{ $t->username }}</div>
                           <div class="timeline_content_addon">
                                  <blockquote>
                                      {{ $t->message }}
                                  </blockquote>
                              </div>
                       </div>
                        @endforeach
                    </div>
                    <div class="uk-flex uk-height-medium uk-background-muted uk-margin uk-text-center">
                    <div class="uk-margin-auto uk-margin-auto-vertical uk-width-1-2@s uk-card uk-card-default uk-card-body">
                    <a class="md-btn md-btn-primary md-btn-mini md-btn-wave-light md-btn-icon" id="view_more" href="javascript:void(0)">
                         <i class="uk-icon-angle-double-down"></i>
                         @lang('index.view_more')
                     </a>
                     </div>
                     </div>
                </li>
                <li>
                    <ul class="md-list md-list-addon chat_users">
                      @foreach($users_com as $u)
                  <li data-user="{{ $u->id }}">
                      <div class="md-list-addon-element">
                          <span class="element-status"></span>
                          <img class="md-user-image md-list-addon-avatar" src="{{ $u->avatar != "" ? url($u->avatar) : url('addon/img/avatar.png')  }}" alt="Avatar"/>
                      </div>
                      <div class="md-list-content">
                          <div class="md-list-action-placeholder"></div>
                          <span class="md-list-heading">{{ $u->username }}</span>
                          <span class="uk-text-small uk-text-muted uk-text-truncate">@lang('messages.offline')</span>
                      </div>
                  </li>
                      @endforeach
                    </ul>
                    <div class="chat_box_wrapper chat_box_small" id="chat">
                        <div class="chat_box chat_box_colors_a">
                            <div class="chat_message_wrapper chat_message_load" style="display:none">
                                <div class="chat_user_avatar">
                                    <img class="md-user-image" src="{{url('addon/img/avatar.png')}}" alt="avatar"/>
                                </div>
                                <ul class="chat_message">
                                    <li>
                                        <p> </p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <h4 class="heading_c uk-margin-small-bottom uk-margin-top">General Settings</h4>
                    <ul class="md-list">
                        <li>
                            <div class="md-list-content">
                                <div class="uk-float-right">
                                    <input type="checkbox" data-switchery data-switchery-size="small" checked id="settings_site_online" name="settings_site_online" />
                                </div>
                                <span class="md-list-heading">Site Online</span>
                                <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                            </div>
                        </li>
                        <li>
                            <div class="md-list-content">
                                <div class="uk-float-right">
                                    <input type="checkbox" data-switchery data-switchery-size="small" id="settings_seo" name="settings_seo" />
                                </div>
                                <span class="md-list-heading">Search Engine Friendly URLs</span>
                                <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                            </div>
                        </li>
                        <li>
                            <div class="md-list-content">
                                <div class="uk-float-right">
                                    <input type="checkbox" data-switchery data-switchery-size="small" id="settings_url_rewrite" name="settings_url_rewrite" />
                                </div>
                                <span class="md-list-heading">Use URL rewriting</span>
                                <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                            </div>
                        </li>
                    </ul>
                    <hr class="md-hr">
                    <h4 class="heading_c uk-margin-small-bottom uk-margin-top">Other Settings</h4>
                    <ul class="md-list">
                        <li>
                            <div class="md-list-content">
                                <div class="uk-float-right">
                                    <input type="checkbox" data-switchery data-switchery-size="small" data-switchery-color="#7cb342" checked id="settings_top_bar" name="settings_top_bar" />
                                </div>
                                <span class="md-list-heading">Top Bar Enabled</span>
                                <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                            </div>
                        </li>
                        <li>
                            <div class="md-list-content">
                                <div class="uk-float-right">
                                    <input type="checkbox" data-switchery data-switchery-size="small" data-switchery-color="#7cb342" id="settings_api" name="settings_api" />
                                </div>
                                <span class="md-list-heading">Api Enabled</span>
                                <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                            </div>
                        </li>
                        <li>
                            <div class="md-list-content">
                                <div class="uk-float-right">
                                    <input type="checkbox" data-switchery data-switchery-size="small" data-switchery-color="#d32f2f" id="settings_minify_static" checked name="settings_minify_static" />
                                </div>
                                <span class="md-list-heading">Minify JS files automatically</span>
                                <span class="uk-text-muted uk-text-small">Lorem ipsum dolor sit amet&hellip;</span>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

        <button type="button" class="chat_sidebar_close uk-close"></button>
        <div class="chat_submit_box">
            <p class="typing" style="display:none">user is chatting</p>
            <div class="uk-input-group">
                <input type="text" class="md-input" name="content_message" id="content_message" placeholder="Send message">
                <span class="uk-input-group-addon">
                    <a href="javascript:;" id="submit_message"><i class="material-icons md-24">&#xE163;</i></a>
                </span>
            </div>
        </div>

    </aside><!-- secondary sidebar end -->
