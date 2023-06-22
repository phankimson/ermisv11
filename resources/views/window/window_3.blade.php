<div id="form-window-barcode" style="display:none">
  <div class="uk-width-medium-4-4">
    @foreach($supplier_goods_type as $t)
          <input type="radio" name="filter_nature" id="{{$t->id}}" value="{{$t->id}}" class="k-radio" {{ $t->id == $ot->id ? 'checked' : '' }}>
          <label class="k-radio-label" for="{{$t->id}}"> {{ $lang == 'vi'? $t->name : $t->name_en }} </label>
    @endforeach
  </div>
  <div class="uk-margin-medium-top"></div>

  <select class="droplist" name="filter_field">
      <option readonly selected value="">--Select--</option>
      <option value="barcode">@lang('marial_goods.barcode') </option>
      <option value="code">@lang('global.code')</option>
      <option value="name">@lang('global.name')</option>
      <option value="name_en">@lang('global.name_en')</option>
      <option value="price">@lang('marial_goods.price')</option>
      <option value="purchase_price">@lang('marial_goods.purchase_price')</option>
  </select>
  <input type="text" name="filter_value" class="k-textbox">
  <a href="javascript:;" class="uk-margin-left-30 k-button k-primary" id="search_barcode" data-uk-tooltip=""><i class="md-18 material-icons md-color-white">search</i>@lang('action.search')</a>

  <div class="uk-margin-medium-top"></div>

  <div id="grid_barcode"></div>

  <div class="uk-margin" style="float : right">
      <a href="javascript:;" class="k-button k-primary choose_barcode" data-uk-tooltip title="@lang('action.choose') "><i class="md-18 material-icons md-color-white">done</i>@lang('action.choose')</a>
      <a href="javascript:;" class="k-button k-primary cancel-window" data-uk-tooltip title="@lang('action.close') "><i class="md-18 material-icons md-color-white">not_interested</i>@lang('action.close')</a>
  </div>

    </div>
