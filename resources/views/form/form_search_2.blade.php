<div class="uk-grid" id="form-search" aria-hidden="false">
       <div class="uk-width-medium-4-4 mobile_full_width">
           <select class="medium" id="fast_date">
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

           <span>@lang('global.start_date') :</span>
           <input type="text" data-type="date" id="start" name="start_date_a" value="{{ $start_date }}" />

           <span>@lang('global.end_date') :</span>
           <input type="text" data-type="date" id="end" name="end_date_a" value="{{ $end_date }}" />

           <select class="droplist medium-responsive" name="active">
               <option value="">@lang('global.all') </option>
               <option value="0">@lang('global.not_recorded') </option>
               <option value="1">@lang('global.recorded') </option>
           </select>

           <select class="droplist medium-responsive" name="type">
            @foreach($group as $t)
            <option data-id="{{$t->id}}" value="{{explode("/",$t->link)[1]}}">{{ $lang=='vi'? $t->name : $t->name_en}} </option>
            @endforeach               
           </select>

           <a href="javascript:;" class="uk-margin-left-30 k-button k-primary" id="get_data" data-uk-tooltip=""><i class="md-18 material-icons md-color-white">event_available</i>@lang('global.get_data')</a>
           <a href="javascript:;" class="uk-margin-left-30 k-button k-primary" id="re_voucher" data-uk-tooltip=""><i class="md-18 material-icons md-color-white">settings_backup_restore</i>@lang('global.re_voucher')</a>
       </div>
   </div>
