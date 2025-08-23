
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
                          <img class="md-user-image md-list-addon-avatar" src="{{ $u->avatar != '' ? url($u->avatar) : url('addon/img/avatar.png')  }}" alt="Avatar"/>
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
                    <div id="chat-box-ai-begin">
                        <img src="{{url('addon/img/ai-chatbox.png')}}">
                        <div class="uk-flex uk-height-medium uk-background-muted uk-margin uk-text-center">       
                                <div class="uk-margin-auto uk-margin-auto-vertical uk-width-1-2@s uk-card uk-card-default uk-card-body">      
                            <a class="md-btn md-btn-primary md-btn-mini md-btn-wave-light md-btn-icon" id="begin" href="javascript:void(0)">
                                <i class="uk-icon-star"></i>
                                @lang('index.begin_assitant_ai')
                            </a>
                        </div>
                        </div>
                    </div>             
                    <div id="chat-box-ai-open" style="display:none">
                            <div class="uk-input-group">
                                <input type="text" class="md-input" name="content_message_ai" id="content_message_ai" placeholder="Send message">
                                <span class="uk-input-group-addon">
                                    <a href="javascript:;" id="submit_message_ai"><i class="material-icons md-24">&#xE163;</i></a>
                                </span>
                            </div>
                            
                            <div class="uk-margin chat_box_small" id="chat_ai">
                                    <div style="display:none" class="md-card md-card-primary chat_message_ai_load">                                   
                                    <div class="md-card-content">
                                        <p class="md-card-toolbar-heading-text uk-switcher">
                                            Panel 2
                                        </p>                                       
                                        <a class="load-data-modal" href="javascript:;">@lang('index.view_answer')<i class="md-icon material-icons">open_in_new</i></a>                                       
                                        <pre style="display:none" class="content-ai">
                                            Xin chào. 
                                        </pre>                                                             
                                    </div>
                                </div>
                                  
                            </div>

                            <div id="modal_overflow" class="uk-modal">
                                <div class="uk-modal-dialog uk-modal-dialog-large">
                                    <button type="button" class="uk-modal-close uk-close"></button>   
                                    <h2 class="heading_a">Panel 1</h2>                              
                                    <div class="uk-overflow-container">
                                        <pre class="content-ai">
                                             Test Xin chào. 
                                        </pre>
                                    </div>
                                </div>
                            </div>
                            <div class="uk-flex uk-height-medium uk-background-muted uk-margin uk-text-center">
                                 <div class="uk-margin-auto uk-margin-auto-vertical uk-width-1-2@s uk-card uk-card-default uk-card-body">
                                <a id="end_conversation" class="k-button k-primary" >@lang('index.end_conversation')</a>
                                </div>
                            </div>
                    </div>    
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
