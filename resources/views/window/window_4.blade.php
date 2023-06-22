<div id="form-window-voucher" style="display:none">
  <div class="uk-width-medium-4-4">
  <select class="droplist not_disabled medium" id="fast_date_a">
    <option value="">@lang('action.choose')</option>
    <option value="today">@lang('global.today')</option>
    <option value="this_week">@lang('global.this_week')</option>
    <option value="this_month">@lang('global.this_month')</option>
    <option value="this_quarter">@lang('global.this_quarter')</option>
    <option value="this_year">@lang('global.this_year')</option>
    <option value="january">@lang('global.january')</option>
    <option value="february">@lang('global.february')</option>
    <option value="march">@lang('global.march')</option>
    <option value="april">@lang('global.april')</option>
    <option value="may">@lang('global.may')</option>
    <option value="june">@lang('global.june')</option>
    <option value="july">@lang('global.july')</option>
    <option value="august">@lang('global.august')</option>
    <option value="september">@lang('global.september')</option>
    <option value="october">@lang('global.october')</option>
    <option value="november">@lang('global.november')</option>
    <option value="december">@lang('global.december')</option>
    <option value="the_1st_quarter">@lang('global.the_1st_quarter')</option>
    <option value="the_2nd_quarter">@lang('global.the_2nd_quarter')</option>
    <option value="the_3rd_quarter">@lang('global.the_3rd_quarter')</option>
    <option value="the_4th_quarter">@lang('global.the_4th_quarter')</option>
  </select>
  </div>
  <div class="uk-margin-medium-top"></div>

  <span>@lang('global.start_date') :</span>
  <input type="text" data-type="date" id="start_a" class="start" name="start_date_a" value="{{date('d/m/Y')}}" />

  <span>@lang('global.end_date') :</span>
  <input type="text" data-type="date" id="end_a" class="end" name="end_date_a" value="{{date('d/m/Y')}}" />

  <select class="droplist not_disabled medium-responsive" name="active">
      <option value="">@lang('global.all') </option>
      <option value="0">@lang('global.not_recorded') </option>
      <option value="1">@lang('global.recorded') </option>
  </select>

    <a href="javascript:;" class="uk-margin-left-30 k-button k-primary" id="search_voucher" data-uk-tooltip=""><i class="md-18 material-icons md-color-white">search</i>@lang('action.search')</a>
  <div class="uk-margin-medium-top"></div>

  <div id="grid_voucher"></div>

  <div class="uk-margin" style="float : right">
      <a href="javascript:;" class="k-button k-primary choose_voucher" data-uk-tooltip title="@lang('action.choose') "><i class="md-18 material-icons md-color-white">done</i>@lang('action.choose')</a>
      <a href="javascript:;" class="k-button k-primary cancel-window" data-uk-tooltip title="@lang('action.close') "><i class="md-18 material-icons md-color-white">not_interested</i>@lang('action.close')</a>
  </div>  

    </div>
