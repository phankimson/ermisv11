<div class="uk-float-left uk-hidden-small" id="add-top-menu-general">
             <a class="new_item top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.new_page')  ({{ config('app.short_key')}}A)">note_add</i></a>
             <a class="view_item top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.view') ({{ config('app.short_key')}}V)">remove_red_eye</i></a>
             <a class="delete_item top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.delete') ({{ config('app.short_key')}}D)">delete_forever</i></a>
             <a class="write_item top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.write') ({{ config('app.short_key')}}W)">flash_on</i></a>
             <a class="unwrite_item top_menu" style="display:none"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.unwrite')  ({{ config('app.short_key')}}U)">flash_off</i></a>
             <a class="refesh_item top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.refesh')  ({{ config('app.short_key')}}R)">restore_page</i></a>
             <div id="print_top_dropdown" class="uk-float-right uk-hidden-small print">
                 <div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
                     <a href="javascript:;" data-uk-tooltip title="@lang('action.print') " class="top_menu_toggle top_menu"><i class="material-icons md-24">local_printshop</i></a>
                     <div class="uk-dropdown uk-dropdown-width-1">
                         <div class="uk-grid uk-dropdown-grid">
                             <div class="uk-width-1-1">
                                 <ul class="uk-nav uk-nav-dropdown uk-panel">
                                     @foreach($print as $p)
                                      <li><a href="javascript:;" data-id="{{ $p->id }}" class="print-item">{{ $p->name }}</a></li>
                                     @endforeach
                                 </ul>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
