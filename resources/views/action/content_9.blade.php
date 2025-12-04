<table>
    <tr>
        <td class="row-label-responsive"><label>@lang('acc_voucher.stock_issue')</label></td>
        <td colspan="2">
            <select class="droplist read large xxlarge bank_account" name="stock_issue"  data-type="string" data-width="200px" data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.stock')}}" data-change="credit">
                 <option readonly selected value="0">--Select--</option>         
            </select>            
        </td>   

        <td class="row-label-responsive"></td>
        <td><label>@lang('acc_voucher.currency')</label></td>
        <td colspan="2">
          <select class="droplist get_option fix" id="currency" name="currency">
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
       <td class="row-label-responsive"><label>@lang('acc_voucher.stock_receipt')</label></td>
        <td colspan="2">
            <select class="droplist read large xxlarge stock" name="stock_receipt"  data-type="string" data-width="200px" data-value-field="value" data-text-field="text" data-read-url="{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.stock')}}" data-change="debit">
                <option readonly selected value="0">--Select--</option>
                          
            </select>            
        </td>        
        <td class="row-label-responsive"></td>
        <td><label>@lang('acc_voucher.rate')</label></td>
        <td colspan="2"><input type="text" class="k-textbox number fix rate" value="{{$currency_rate}}" name="rate" /></td>
    </tr>
    <tr>
        <td><label>@lang('acc_voucher.exporter')</label></td>      
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
             <td><label>@lang('acc_voucher.reference')</label></td> 
            <td colspan="2">
            <span class="k-textbox k-space-right xxlarge">
                <input type="text" class="no_copy" name="reference" readonly />
                <a href="javascript:;" style="right : 10px"  class="k-icon k-i-search reference">&nbsp;</a>
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
    <tr>
        <td><label>@lang('acc_voucher.accounted_auto')</label></td>
        <td colspan="2">
            <select class="droplist large xxlarge no_copy_value" id="accounted_auto" name="accounted_auto">
                    <option readonly selected value="0">--Select--</option>
                    @foreach($accounted_auto as $c)
                    @if($c->profession == $menu)
                    <option value="{{ $c->id }}">{{ $c->code }} - {{ $c->name }}</option>
                    @else
                    @endif
                    @endforeach
            </select>
        </td>
    </tr>
    <tr>
        <td><label>@lang('acc_voucher.automatic_ai')</label></td>
        <td colspan="2"><input type="text" class="speech-input no_copy" url="ai" readonly lang="{{$lang}}" name="automatic_ai" data-ready="Tell here !!" value="" data-buttonsize="5" data-patience="5"></td>
    </tr>
    <tr>
        <td><label>@lang('acc_voucher.attach')</label></td>
        <td colspan="2">
          <span class="k-textbox k-space-right xxlarge">
              <input type="file" id="attach" class="md-btn" name="attach" multiple accept="image/png, image/jpeg,application/pdf,.doc,.docx,.xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
              <a href="javascript:;" style="right : 10px"  class="k-icon k-i-folder-more attach">&nbsp;</a>
          </span>
        </td>
    </tr>
      <tr>
        <td><label>@lang('global.code')</label></td>
        <td colspan="2">
          <span class="k-textbox k-space-right xxlarge">
                <input type="text" id="barcode" class="no_copy" name="barcode"/>
                <a href="javascript:;" style="right : 10px"  class="k-icon k-i-filter barcode">&nbsp;</a>
            </span> 
        </td>
    </tr>
</table>
