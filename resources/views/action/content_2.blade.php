<table>
<tr>
    <td class="row-label-responsive"><label>@lang('acc_voucher.payment')</label></td>
    <td><input type="text" class="k-textbox number fix total_payment" value="0" min="0" name="total_payment" /></td>  
    <td><input type="text" class="k-textbox number percent fix" placeholder="@lang('acc_voucher.percent')" value="0" min="0"  max="100" name="percent" /><a href="javascript:;" class="uk-margin-left-30 k-button k-primary get-data" data-uk-tooltip=""><i class="md-18 material-icons md-color-white"><span class="material-symbols-outlined">filter_list</span></i>@lang('global.get_data')</a></td>
        
    <td class="row-label-responsive"></td>
        <td><label>@lang('acc_voucher.currency')</label></td>
        <td colspan="2">
          <select class="droplist find_val fix" id="currency" name="currency">
                <option readonly selected value="0">--Select--</option>
                @foreach($currency as $c)
                  @if($c->code == $currency_default->value)
                  <option selected value="{{ $c->id }}">{{ $c->code }} - {{ $c->name }}</option>
                  @else
                  <option value="{{ $c->id }}">{{ $c->code }} - {{ $c->name }}</option>
                  @endif
                @endforeach
        </select>
      </td>
    </tr>
    <tr>
        <td class="row-label-responsive"><label>@lang('acc_voucher.subject')</label></td>
        <td>
            <input type="text" name="subject_id" data-get="object.id" data-find="subject_id" readonly hidden />
            <span class="k-textbox k-space-right">
                <input type="text" name="code" data-get="object.code" data-find="subject_code" readonly />
                <a href="javascript:;" style="right : 10px" class="k-icon k-i-filter filter">&nbsp;</a>
            </span>
        </td>
        <td><input type="text" class="k-textbox large" data-get="object.name" data-find="subject_name" name="name" readonly/></td>

        <td class="row-label-responsive"></td>
        <td><label>@lang('acc_voucher.rate')</label></td>
        <td colspan="2"><input type="text" class="k-textbox number fix rate" value="{{$currency_rate}}" name="rate" /></td>
       
    </tr>

    <tr>
        <td><label>@lang('acc_voucher.payer')</label></td>
        <td colspan="2"><input type="text" class="k-textbox xxlarge" name="traders" /></td>

        <td class="row-label-responsive"></td>
        <td><label>@lang('acc_voucher.accounting_date')</label></td>
        <td colspan="2"><input type="text" data-type="date" class="k-textbox date-picker" name="accounting_date" /></td>
       
    </tr>
    <tr>
        <td><label>@lang('acc_voucher.description')</label></td>
        <td colspan="2"><input type="text" class="k-textbox xxlarge" name="description" /></td>

        <td class="row-label-responsive"></td>
        <td><label>@lang('acc_voucher.voucher_date')</label></td>
        <td colspan="2"><input type="text" data-type="date" class="k-textbox date-picker" name="voucher_date" /></td>
       
    </tr>

    <tr>
        <td><label>@lang('acc_voucher.attach')</label></td>
        <td colspan="2">
          <span class="k-textbox k-space-right xxlarge">
              <input type="file" id="attach" class="md-btn" name="attach" multiple accept="image/png, image/jpeg,application/pdf,.doc,.docx,.xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
              <a href="javascript:;" style="right : 10px"  class="k-icon k-i-folder-more attach">&nbsp;</a>
          </span>
        </td>

        <td class="row-label-responsive"></td>

    <td><label>@lang('acc_voucher.voucher')</label></td>
    <td colspan="2">
    <span class="k-textbox k-space-right medium">
        <input type="text" class="voucher no_copy" name="voucher"/>
        <a href="javascript:;" style="right : 10px"  class="k-icon k-i-table-cell-properties voucher-change">&nbsp;</a>
    </span>               
    </td>
    </tr>
    
</table>
