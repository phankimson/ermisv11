<div class="uk-float-left">
<a class="add top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.add') ({{ config('app.short_key')}}A)">note_add</i></a>
<a class="copy top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.copy') ({{ config('app.short_key')}}X)">content_copy</i></a>
<a class="edit top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.edit') ({{ config('app.short_key')}}E)">edit</i></a>
<a class="save top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.save') ({{ config('app.short_key')}}S)">save</i></a>
<a class="cancel top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.cancel') ({{ config('app.short_key')}}C)">cancel</i></a>
<a class="delete top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.delete') ({{ config('app.short_key')}}D)">delete</i></a>
<a class="write_item top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.write') ({{ config('app.short_key')}}W)">flash_on</i></a>
<a class="unwrite_item top_menu" style="display:none"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.unwrite') ({{ config('app.short_key')}}U)">flash_off</i></a>
<a class="back top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.back') ({{ config('app.short_key')}}<)">arrow_back</i></a>
<a class="forward top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.forward') ({{ config('app.short_key')}}>)">arrow_forward</i></a>

<div id="print_top_dropdown" class="uk-float-right uk-hidden-small print">
    <div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
        <a href="javascript:;" data-uk-tooltip title="@lang('action.print')" class="top_menu_toggle top_menu"><i class="material-icons md-24">local_printshop</i></a>
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
  <a class="pageview top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.pageview') ({{ config('app.short_key')}}I)">pageview</i></a>
</div>
