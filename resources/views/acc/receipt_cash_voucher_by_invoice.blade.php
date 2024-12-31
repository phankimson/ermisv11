@extends('form.ermis_form_4')

@push('css_up')

@endpush

@push('action')
@include('action.top_menu_8')
@endpush

@push('toolbar_action')
@include('action.toolbar_7')
@endpush


@section('form_window')
<div id="print"></div>
@include('window.window_1')
@endsection

@section('uktab')
<div class="uk-grid uk-tab uk-width-1-1">
    @foreach($menu_tab as $m)
    <li class={{($m->id== $menu )? 'uk-active' : ''}}><a href="{{url($lang.'/'.$m->link)}}">{{ $lang=='vi'? $m->name : $m->name_en}}</a></li>
    @endforeach   
    <li class="uk-disabled"><a href="#">@lang('global.expand')</a></li>
</div>      
@endsection

@section('content_add')
<div class="uk-width-medium-4-4 search-table-outter">
  @include('action.content_2')
</div>
@endsection

@section('tabs')
<div id="tabstrip">
    <ul style="display:none">
        <li class="k-state-active">@lang('acc_voucher.detail')</li>
        <li>@lang('global.expand')</li>
    </ul>
    <div><div id="grid"></div> </div>
    <div>
        <table>
            <tr>
                <td class="row-label-responsive"><label>@lang('global.expand')</label></td>
                <td colspan="2"><input type="text" class="k-textbox large" /></td>

                <td class="row-label-responsive"></td>
                <td><label>@lang('global.expand')</label></td>
                <td colspan="2"><input type="text" class="k-textbox large" /></td>
            </tr>

            <tr>
                <td><label>@lang('global.expand')</label></td>
                <td colspan="2"><input type="text" class="k-textbox large" /></td>

                <td class="row-label-responsive"></td>
                <td><label>@lang('global.expand')</label></td>
                <td colspan="2"><input type="text" class="k-textbox large" /></td>
            </tr>

            <tr>
                <td><label>@lang('global.expand')</label></td>
                <td colspan="2"><input type="text" class="k-textbox large" /></td>

                <td class="row-label-responsive"></td>
                <td><label>@lang('global.expand')</label></td>
                <td colspan="2"><input type="text" class="k-textbox large" /></td>
            </tr>

        </table>
    </div>

</div>
<!--<div class="uk-width-medium-4-4">
    <div id="grid"></div>
</div>-->
@endsection

@push('context_action')
@include('action.context_7')
@endpush

@push('context_action_grid')
@include('action.context_6')
@endpush

@section('scripts_up')
<!--<div id="tax_dropdown_list" class="hidden" data-json=""></div>-->
<script>
    jQuery(document).ready(function () {
        Ermis.data = [];
        Ermis.link = "{{$key}}";
        Ermis.page_size = "{{$page_size}}";
        Ermis.page_size_1 = "{{$page_size_1}}";
        Ermis.per = <?= json_encode($per);?>;
        Ermis.short_key = "{{ config('app.short_key')}}";
        Ermis.voucher = <?= json_encode($voucher);?>;
        Ermis.decimal = "{{$decimal}}";
        Ermis.decimal_symbol = "{{$decimal_symbol}}";
        Ermis.columns_subject = [{ "title": "STT", "template": "<span class='row-number'></span>", "width": 100 },
                                {"field" : "subject_id", hidden: true},
                                {"field" : "code","title" :"@lang('acc_voucher.subject_code')" , "field_set": "subject_code"},
                                {"field" : "name","title" :"@lang('acc_voucher.subject_name')" , "field_set": "subject_name"},
                                {"field" : "address","title" :"@lang('acc_voucher.address')" },
                                {"field" : "invoice_form", hidden: true},
                                {"field" : "invoice_symbol", hidden: true},]

      
        Ermis.columns    = [{"field" :"id", hidden : true},
                            { "field" : "invoice","title" : "@lang('acc_voucher.invoice')"  ,width : '150px'},
                            { "field" : "description","title" : "@lang('acc_voucher.description')",width : '200px'  },
                            { "field" : "total_amount","title" : "@lang('acc_voucher.total_amount')" ,format: "{0:n{{$decimal}}}",decimals: "{{$decimal}}" ,aggregates: ['sum'] , template: "#=calculateAmountTax(amount, tax.value, {{$decimal}} )#" ,footerTemplate: "<p id='total_amount'>#=calculateTotalVatAggregate({{$decimal}})#</p>" ,width : '150px'}];
                      
        Ermis.field = {
            id : {field :"id" ,defaultValue: 0},
            description:     {field : "description"},
            amount:     {field : "amount",type:"number" , defaultValue : 0 , validation: { min: 1, required: true }},
            rate:     {field : "rate",type:"number", defaultValue : parseInt(jQuery(".rate").val()) , validation: { min: 0, required: true }},
            amount_rate:     {field : "amount_rate",type:"number" , defaultValue : 0 , validation: { min: 1, required: true }},
            case_code: { field : "case_code", defaultValue: DefaultReadValueField() },
            cost_code: { field : "cost_code", defaultValue: DefaultReadValueField() },
            statistical_code: { field : "statistical_code", defaultValue: DefaultReadValueField() },
            subject_code: { field : "subject_code", defaultValue: DefaultReadValueField() },
            //work_code: { field : "work_code" ,defaultValue: DefaultValueField() },
            work_code: { field : "work_code" ,defaultValue: DefaultReadValueField() },
            department: { field : "department", defaultValue: DefaultReadValueField()  },
            bank_account: { field : "bank_account", defaultValue: DefaultReadValueField()  },
            debit: { field : "debit", defaultValue: RequestURL("{!!route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.account-voucher-default').'?menu='.$menu.'&type=1'!!}"), validation: { min: 1 ,required: true }},
            credit: { field : "credit", defaultValue: RequestURL("{!!route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.account-voucher-default').'?menu='.$menu.'&type=2'!!}"), validation: { min: 1, required: true }},
            accounted_fast: { field : "accounted_fast",defaultValue: DefaultReadValueField() },
            lot_number:     {field : "lot_number"},
            contract:     {field : "contract"},
            order:     {field : "order"},
        };

        Ermis.aggregate = [ { field: "description", aggregate: "count" },
                            { field: "amount", aggregate: "sum" },
                            { field: "amount_rate", aggregate: "sum" },
                            { field: "total_amount", aggregate: "sum" }];
    });
</script>


@endsection
@section('scripts_end')
@if($decimal_symbol === ".")
<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.de-DE.min.js') }}"></script>
<script>kendo.culture('de-DE')</script>
@endif
<script src="{{ url('addon/scripts/ermis/ermis-form-global-detail.js') }}"></script>
<script src="{{ url('addon/scripts/ermis/ermis-form-5-detail-dom-file.js') }}"></script>
@endsection
