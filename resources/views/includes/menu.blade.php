<!-- main sidebar -->
<aside id="sidebar_main">

    <div class="sidebar_main_header">
        <div class="sidebar_logo">
            <a href="{{url('/'.$manage)}}" class="sSidebar_hide"><img src="{{asset('addon/img/logo-small-blue.png')}}" alt="logo" height="15" width="71"/></a>
            <a href="{{url('/'.$manage)}}" class="sSidebar_show"><img src="{{asset('addon/img/logo-small-blue.png')}}" alt="logo" height="32" width="32"/></a>
        </div>
        <div class="sidebar_actions">
            <select id="lang_switcher" name="lang_switcher">
                <option value="gb" selected>English</option>
            </select>
        </div>
    </div>

    <div class="menu_section">
        <ul>
          @if($menu->count()>0)
            @foreach($menu as $m)
              @if($m->link == "")
              <h4>{{ $lang == 'vi'? $m->name : $m->name_en }}</h4>
                      @foreach($m->sub_menu as $m1)
                        <li class="{{$m1->link == $manage.'/'.request()->segment(3) ? 'current_section' : '' }}" mid="{{$m1->id}}" title="{{ $lang == 'vi'? $m1->name : $m1->name_en }}">
                            <a href="{{$m1->link == ''? 'javascript:;' : url($lang.'/'.$m1->link)}}">
                                <span class="menu_icon"><i class="material-icons">{{$m1->icon}}</i></span>
                                <span class="menu_title">{{ $lang == 'vi'? $m1->name : $m1->name_en }}</span>
                            </a>
                            @if($m1->sub_menu1->count()>0)
                            <ul>
                              @foreach($m1->sub_menu1 as $m2)
                               <li class="{{$m2->link == $manage.'/'.request()->segment(3) ? 'current_section' : '' }}" pid ="{{$m2->parent_id}}" title="{{ $lang == 'vi'? $m2->name : $m2->name_en }}">
                                  <a href="{{$m2->link == ''? 'javascript:;' : url($lang.'/'.$m2->link)}}">
                                      <span class="menu_icon"><i class="material-icons">{{$m2->icon}}</i></span>
                                      <span class="menu_title">{{ $lang == 'vi'? $m2->name : $m2->name_en }}</span>
                                  </a>
                               </li>
                              @endforeach
                            </ul>
                            @endif
                        </li>
                      @endforeach
                @else
                <li class="{{$m->link == $manage.'/'.request()->segment(3) ? 'current_section' : '' }}" title="{{ $lang == 'vi'? $m->name : $m->name_en }}">
                   <a href="{{$m->link == ''? 'javascript:;' : url($lang.'/'.$m->link)}}">
                       <span class="menu_icon"><i class="material-icons">{{$m->icon}}</i></span>
                       <span class="menu_title">{{ $lang == 'vi'? $m->name : $m->name_en }}</span>
                   </a>
                </li>
              @endif
            @endforeach
          @endif
        </ul>
    </div>
</aside><!-- main sidebar end -->
