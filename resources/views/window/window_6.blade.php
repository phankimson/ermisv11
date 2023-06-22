<div id="form-window-voucher-change" style="display:none">
<table>
    <tr>
        <td class="row-label-responsive"><label>@lang('acc_number_voucher.code')</label></td>
        <td>
            <input type="text" class="hidden no_clear" name ="id" readonly value="{{$voucher->id}}" />
            <input type="text" class="k-textbox large no_clear" readonly value="{{$voucher->code}}" />
        </td>        
    </tr>
    <tr>
        <td class="row-label-responsive"><label>@lang('acc_number_voucher.name')</label></td>
        <td>
        <input type="text" class="k-textbox large no_clear" readonly value="{{$lang = 'en' ? $voucher->name_en : $voucher->name}}"/>
        </td>        
    </tr>
    <tr>
        <td class="row-label-responsive"><label>@lang('acc_number_voucher.prefix')</label></td>
        <td>
        <input type="text" class="k-textbox large no_clear" name ="prefix" value="{{$voucher->prefix}}" />
        </td>        
    </tr>
    <tr>
        <td class="row-label-responsive"><label>@lang('acc_number_voucher.suffixes')</label></td>
        <td>
            <input type="text" class="k-textbox large no_clear" name ="suffixes" value="{{$voucher->suffixes}}" />
        </td>        
    </tr>
    <tr>
        <td class="row-label-responsive"><label>@lang('acc_number_voucher.length_number')</label></td>
        <td>
            <input type="text" class="k-textbox large no_clear" name ="length_number" value="{{$voucher->length_number}}" />
        </td>        
    </tr>
    <tr>
        <td class="row-label-responsive"><label>@lang('acc_number_voucher.number')</label></td>
        <td>
            <input type="text" class="k-textbox large no_clear" name ="number" value="{{$voucher->number}}" />
        </td>        
    </tr>    
</table>
      <div class="uk-margin" style="float : right">
          <a href="javascript:;" class="k-button k-primary" id="voucher-change" data-uk-tooltip title="@lang('action.change')"><i class="md-18 material-icons md-color-white">repeat</i>@lang('action.change')</a>
          <a href="javascript:;" class="k-button k-primary cancel-window" data-uk-tooltip title="@lang('action.close')"><i class="md-18 material-icons md-color-white">not_interested</i>@lang('action.close')</a>
      </div>
</div>
