<div class="uk-float-left uk-hidden-small" id="add-top-menu">
   <a class="add top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.add') ({{ config('app.short_key')}}A)">add</i></a>
   <a class="copy top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.copy') ({{ config('app.short_key')}}X)">content_copy</i></a>
   <a class="edit top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.edit') ({{ config('app.short_key')}}E)">edit</i></a>
   <a class="save top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.save') ({{ config('app.short_key')}}S)">save</i></a>
   <a class="cancel top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.cancel') ({{ config('app.short_key')}}C)">cancel</i></a>
   <a class="delete top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.delete') ({{ config('app.short_key')}}D)">delete</i></a>
   <a class="import top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.import') ({{ config('app.short_key')}}I)">archive</i></a>
   <a class="export top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.export') ({{ config('app.short_key')}}Q)">unarchive</i></a>
   <a class="export_extra top_menu"><i class="md-24 material-icons" data-uk-tooltip title="@lang('action.export_extra') ({{ config('app.short_key')}}W)">cloud_upload</i></a>
</div>
@if(Auth::user()->role == 0)
<div class="top_menu uk-float-left uk-hidden-small">
    <div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
        <a href="javascript:;" class="top_menu_toggle connect_database"><i class="material-icons md-24">settings_input_component</i></a>
        <div class="uk-dropdown uk-dropdown-width-2">
            <div class="uk-grid uk-dropdown-grid" data-uk-grid-margin>
                <div class="uk-width-1-1" id="form_select">
                  <select class="large not_disabled database" name="database">
                          @foreach($database as $c)
                          @if($c->database == $db)
                             <option selected value="{{ $c->id }}">{{ $c->name }} - {{ $c->database }}</option>
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
@endif
