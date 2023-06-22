<div id="form-window-filter" style="display:none">
  <div class="uk-width-medium-4-4">
    @foreach($object_type as $t)
          <input type="radio" name="filter_type" id="{{$t->id}}" value="{{$t->id}}" class="k-radio" {{ $t->id == $ot->id ? 'checked' : '' }}>
          <label class="k-radio-label" for="{{$t->id}}"> {{ $lang == 'vi'? $t->name : $t->name_en }} </label>
    @endforeach
      </div>
      <div class="uk-margin-medium-top"></div>

      <select class="droplist" name="filter_field">
          <option readonly selected value="">--Select--</option>
          <option value="code">@lang('acc_object.code')</option>
          <option value="name">@lang('acc_object.name')</option>
          <option value="address">@lang('acc_object.address')</option>
          <option value="tax_code">@lang('acc_object.tax_code')</option>
          <option value="phone">@lang('acc_object.phone')</option>
          <option value="email">@lang('acc_object.email')</option>
          <option value="full_name_contact">@lang('acc_object.full_name_contact')</option>
      </select>
      <input type="text" name="filter_value" class="k-textbox">
      <a href="javascript:;" class="uk-margin-left-30 k-button k-primary" id="search_data" data-uk-tooltip=""><i class="md-18 material-icons md-color-white">search</i>@lang('action.search')</a>

      <div class="uk-margin-medium-top"></div>

      <div id="grid_subject"></div>

      <div class="uk-margin" style="float : right">
          <a href="javascript:;" class="k-button k-primary choose" data-uk-tooltip title="@lang('action.choose')"><i class="md-18 material-icons md-color-white">done</i>@lang('action.choose')</a>
          <a href="javascript:;" class="k-button k-primary cancel-window" data-uk-tooltip title="@lang('action.close')"><i class="md-18 material-icons md-color-white">not_interested</i>@lang('action.close')</a>
      </div>
</div>
