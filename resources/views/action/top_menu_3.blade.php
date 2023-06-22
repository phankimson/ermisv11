<div class="uk-float-left uk-hidden-small" id="add-top-menu">
   <a class="show top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.show') ({{ config('app.short_key')}}S)">swap_vert</i></a>
   <div class="top_menu uk-float-left uk-hidden-small">
       <div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
           <a href="javascript:;" class="top_menu_toggle connect_database"><i class="material-icons md-24">settings_input_component</i></a>
           <div class="uk-dropdown uk-dropdown-width-2">
               <div class="uk-grid uk-dropdown-grid" data-uk-grid-margin>
                   <div class="uk-width-1-1" id="form_select">
                     <select class="large not_disabled database" name="database">
                            <option selected value="0">Manager - Ermis</option>
                             @foreach($database as $c)
                             @if($c->database == $db)
                                <option value="{{ $c->id }}">{{ $c->name }} - {{ $c->database }}</option>
                             @else
                                <option value="{{ $c->id }}">{{ $c->name }} - {{ $c->database }}</option>
                             @endif
                             @endforeach
                     </select>
                   </div>
               </div>
           </div>
       </div>
   </div>
</div>
