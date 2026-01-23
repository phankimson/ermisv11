<table class="TableForm_NH">
    <tr>
        <td class="row-label-responsive"><label>@lang('acc_voucher.subject')</label></td>
        <td>
            <input type="text" name="subject_id" data-get="object.id" data-find="subject_id" readonly hidden />
            <span class="k-textbox k-space-right">
                <input type="text" name="code" data-get="object.code" data-find="subject_code" readonly />
                <a href="javascript:;" style="right : 10px" class="k-icon k-i-filter filter">&nbsp;</a>
            </span>
        </td>
        <td><input type="text" class="k-textbox name large" data-get="object.name" data-find="subject_name" name="name_NH" /></td>

        <td class="row-label-responsive"></td>
        <td><label>@lang('acc_voucher.currency')</label></td>
        <td colspan="2">
          <select class="droplist get_option fix" id="currency" name="currency_NH">
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
       <td class="row-label-responsive"><label>@lang('acc_voucher.bank_account')</label></td>
        <td colspan="2">
            <select class="droplist multi large xxlarge bank_account" name="bank_account_NH"  data-type="string" data-format-description ="number" data-width="200px" data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.bank-account').'?detail=true'}}" data-change="{{ $change ?? '' }}">
                <option readonly selected value="0">--Select--</option>
                          
            </select>            
        </td>        
        <td class="row-label-responsive"></td>
        <td><label>@lang('acc_voucher.rate')</label></td>
        <td colspan="2"><input type="text" class="k-textbox number fix rate" value="{{$currency_rate}}" name="rate_NH" /></td>
    </tr>
    <tr>
        @if($voucher->code == "MH")
        <td><label>@lang('acc_voucher.bank_payer')</label></td>    
        @elseif($voucher->code == "BH")
        <td><label>@lang('acc_voucher.bank_receiver')</label></td>    
        @endif     
        <td colspan="2"><input type="text" class="k-textbox xxlarge" name="traders_NH" /></td>
        
        <td class="row-label-responsive"></td>
        <td><label>@lang('acc_voucher.accounting_date')</label></td>
        <td colspan="2"><input type="text" data-type="date" class="k-textbox date-picker" name="accounting_date_NH" /></td>
    </tr>

    <tr>
        <td><label>@lang('acc_voucher.description')</label></td>
        <td colspan="2"><input type="text" class="k-textbox xxlarge" name="description_NH" /></td>              

        <td class="row-label-responsive"></td>
        <td><label>@lang('acc_voucher.voucher_date')</label></td>
        <td colspan="2"><input type="text" data-type="date" class="k-textbox date-picker" name="voucher_date_NH" /></td>
    </tr>
        <tr> 
            <td></td>
            <td colspan="2"></td>
            <td class="row-label-responsive"></td>     
            <td><label>@lang('acc_voucher.voucher')</label></td>
            <td colspan="2">
            <span class="k-textbox k-space-right medium">
                <input type="text" class="voucher no_copy" name="voucher_NH"/>
                <a href="javascript:;" style="right : 10px"  class="k-icon k-i-table-cell-properties voucher-change">&nbsp;</a>
            </span>               
            </td>
        </tr>
</table>
