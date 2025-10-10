<div id="form-window-barcode" style="display:none" aria-hidden="false">
  <div class="uk-width-medium-4-4">
    <span>@lang('acc_supplies_goods.type')</span>
    <select class="droplist large" name="filter_type_barcode">
      @foreach($supplier_goods_type as $x)
      @if($loop->first)
      <option selected value="{{ $x->id }}">{{ $x->code }} - {{ $lang == 'vi'? $x->name : $x->name_en }}</option>
      @else
      <option value="{{ $x->id }}">{{ $x->code }} - {{ $lang == 'vi'? $x->name : $x->name_en }}</option>
      @endif
      @endforeach
    </select>   
  </div>
  <div class="uk-margin-medium-top"></div>

  <select class="droplist" name="filter_field_barcode">
      <option readonly selected value="">--Select--</option>
      <option value="code">@lang('global.code')</option>
      <option value="name">@lang('global.name')</option>
      <option value="name_en">@lang('global.name_en')</option>
      <option value="price">@lang('acc_supplies_goods.price')</option>
      <option value="price_purchase">@lang('acc_supplies_goods.price_purchase')</option>
  </select>
  <input type="text" name="filter_value_barcode" class="k-textbox">
  <a href="javascript:;" class="uk-margin-left-30 k-button k-primary" id="search_barcode" data-uk-tooltip=""><i class="md-18 material-icons md-color-white">search</i>@lang('action.search')</a>

  <div class="uk-margin-medium-top"></div>

  <div id="grid_barcode"></div>

  <div class="uk-margin" style="float : right">
      <a href="javascript:;" class="k-button k-primary choose-barcode" data-uk-tooltip title="@lang('action.choose') "><i class="md-18 material-icons md-color-white">done</i>@lang('action.choose')</a>
      <a href="javascript:;" class="k-button k-primary cancel-window" data-uk-tooltip title="@lang('action.close') "><i class="md-18 material-icons md-color-white">not_interested</i>@lang('action.close')</a>
  </div>

    </div>
