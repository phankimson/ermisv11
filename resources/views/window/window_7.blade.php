<div id="form-window-voucher" style="display:none" aria-hidden="false">
  <div class="uk-width-medium-4-4">
  <span>@lang('acc_count_voucher.day') :</span>
  <input type="text" id="day" class="day" name="day" value="{{date('d')}}" />

  <span>@lang('acc_count_voucher.month') :</span>
  <input type="text" id="month" class="month" name="month" value="{{date('m')}}" />

  <span>@lang('acc_count_voucher.year') :</span>
  <input type="text" id="year" class="year" name="year" value="{{date('Y')}}" />

  <a href="javascript:;" class="uk-margin-left-30 k-button k-primary" id="search_voucher" data-uk-tooltip=""><i class="md-18 material-icons md-color-white">search</i>@lang('action.search')</a>
  </div>
   
  <div class="uk-margin-medium-top"></div>

  <div id="grid_voucher"></div>

  <div class="uk-margin" style="float : right">
      <a href="javascript:;" class="k-button k-primary start_voucher" data-uk-tooltip title="@lang('action.start') "><i class="md-18 material-icons md-color-white">check_circle</i>@lang('action.start')</a>
      <a href="javascript:;" class="k-button k-primary change_voucher" data-uk-tooltip title="@lang('action.choose') "><i class="md-18 material-icons md-color-white">done</i>@lang('action.change')</a>      
      <a href="javascript:;" class="k-button k-primary cancel-window" data-uk-tooltip title="@lang('action.close') "><i class="md-18 material-icons md-color-white">not_interested</i>@lang('action.close')</a>  
    </div>  
   </div>
