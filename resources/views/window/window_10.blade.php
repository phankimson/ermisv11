<div id="form-window-check-subject" style="display:none" aria-hidden="false">
    <table>
    <tr>
    <td class="row-label-responsive"><label>@lang('acc_voucher.tax_code')</label></td>
    <td>
        <input type="text" class="k-textbox large no_clear" id="check_subject_tax_code"  name="tax_code" value="" />
    </td>
    </tr>

    <tr class="row-height load_check_subject hidden subject_tax_code">
    <td class="row-label-responsive"><label>@lang('acc_voucher.tax_code')</label></td>
    <td>
         <span></span>
    </td>
    </tr>

    <tr class="row-height load_check_subject hidden subject_name">
    <td class="row-label-responsive"><label>@lang('acc_voucher.subject_name')</label></td>
    <td>
         <span></span>
    </td>
    </tr>

    <tr class="row-height load_check_subject hidden subject_name_en">
    <td class="row-label-responsive"><label>@lang('acc_voucher.subject_name_en')</label></td>
    <td>
         <span></span>
    </td>
    </tr>

     <tr class="row-height load_check_subject hidden subject_address">
    <td class="row-label-responsive"><label>@lang('acc_voucher.address')</label></td>
    <td>
         <span></span>
    </td>
    </tr>

    <tr class="row-height load_check_subject hidden subject_active">
    <td class="row-label-responsive"><label>@lang('action.active')</label></td>
    <td>
         <a href="javascript:;" class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light">@lang('global.is_active')</a>
    </td>
    </tr>

     <tr class="row-height load_check_subject hidden">
        <td colspan="2">*** @lang('messages.message_tax_1')</td>
    </tr>
</table>

  <div class="uk-margin" style="float : right">
      <a href="javascript:;" class="k-button k-primary check_subject" data-uk-tooltip title="@lang('action.check') "><i class="md-18 material-icons md-color-white">check_box</i>@lang('action.check')</a>
      <a href="javascript:;" class="k-button k-primary cancel-window" data-uk-tooltip title="@lang('action.close') "><i class="md-18 material-icons md-color-white">not_interested</i>@lang('action.close')</a>
  </div>  
   </div>
