@extends('form.ermis_form_4')

@push('css_up')

@endpush

@push('action')
@include('action.top_menu_7')
@endpush

@push('toolbar_action')
@include('action.toolbar_6')
@endpush


@section('form_window')
<div id="print"></div>
@include('window.window_1')
@include('window.window_2')
@include('window.window_3')
@include('window.window_4')
@include('window.window_5')
@include('window.window_6')
@endsection


@section('content_add')
<div class="uk-width-medium-4-4 search-table-outter">
  @include('action.content_1')
</div>
@endsection

@section('tabs')
<div id="tabstrip">
    <ul style="display:none"><li class="k-state-active">@lang('acc_voucher.detail')</li><li>@lang('acc_voucher.vat')</li><li>@lang('global.expand')</li></ul>
    <div><div id="grid"></div> </div>
    <div><div id="grid_vat"></div> </div>
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
<div class="uk-width-medium-4-4">
    <div id="grid"></div>
</div>
@endsection

@push('context_action')
@include('action.context_5')
@endpush

@push('context_action_grid')
@include('action.context_6')
@endpush

@section('scripts_up')
<div id="work_code_dropdown_list" class="hidden" data-json="{{$work_code}}"></div>
<div id="debit_dropdown_list" class="hidden" data-json="{{$debt_account}}"></div>
<div id="credit_dropdown_list" class="hidden" data-json="{{$credit_account}}"></div>
<div id="department_dropdown_list" class="hidden" data-json="{{$department}}"></div>
<div id="bank_account_dropdown_list" class="hidden" data-json="{{$bank_account}}"></div>
<div id="cost_code_dropdown_list" class="hidden" data-json="{{$cost_code}}"></div>
<div id="case_code_dropdown_list" class="hidden" data-json="{{$case_code}}"></div>
<div id="statistical_code_dropdown_list" class="hidden" data-json="{{$statistical_code}}"></div>
<div id="accounted_fast_dropdown_list" class="hidden" data-json="{{$accounted_fast}}"></div>
<div id="tax_dropdown_list" class="hidden" data-json="{{$vat}}"></div>
<div id="subject_code_dropdown_list" class="hidden" data-json="{{$subject_code}}"></div>
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
        Ermis.columns_voucher = [{"field" : "voucher_date","title" : "@lang('acc_voucher.voucher_date')" ,template : "#=FormatDate(voucher_date)#" },
                                {"field" : "voucher","title" : "@lang('acc_voucher.voucher')" },
                                {"field" : "description","title" : "@lang('acc_voucher.description')" },
                                {"field" : "total_amount","title" :  "@lang('acc_voucher.total_amount')" ,template: '#= FormatNumberDecimal(total_amount, {{$decimal}} )#' } ];

        Ermis.columns_subject = [{ "title": "STT", "template": "<span class='row-number'></span>", "width": 100 },
                                {"field" : "subject_id", hidden: true},
                                {"field" : "code","title" :"@lang('acc_voucher.subject_code')" , "field_set": "subject_code"},
                                {"field" : "name","title" :"@lang('acc_voucher.subject_name')" , "field_set": "subject_name"},
                                {"field" : "address","title" :"@lang('acc_voucher.address')" },
                                {"field" : "invoice_form", hidden: true},
                                {"field" : "invoice_symbol", hidden: true},]

        Ermis.columns_reference = [{title: 'Select All',headerTemplate: '<input type="checkbox" id="header-chb-reference" class="k-checkbox reference"><label class="k-checkbox-label" for="header-chb-reference"></label>',template: function(dataItem){return '<input type="checkbox" id="'+ dataItem.id+'" class="k-checkbox reference"><label class="k-checkbox-label" for="'+ dataItem.id +'"></label>'},width: 80},
                                                {"field" : "voucher_date","title" : "@lang('acc_voucher.voucher_date')",template: '#= FormatDate(voucher_date)#' },
                                                {"field" : "voucher","title" : "@lang('acc_voucher.voucher')" },
                                                {"field" : "description","title" : "@lang('acc_voucher.description')" },
                                                {"field" : "total_amount","title" :  "@lang('acc_voucher.total_amount')" ,template: '#= FormatNumberDecimal(total_amount, {{$decimal}} )#' } ];

        Ermis.columns_barcode = [{title: 'Select All',headerTemplate: '<input type="checkbox" id="header-chb-barcode" class="k-checkbox barcode"><label class="k-checkbox-label" for="header-chb-barcode"></label>',template: function(dataItem){return '<input type="checkbox" id="'+ dataItem.id+'" class="k-checkbox barcode"><label class="k-checkbox-label" for="'+ dataItem.id +'"></label>'},width: 80},
                                                {"field" : "date_voucher","title" : "@lang('acc_voucher.date_voucher')" },
                                                {"field" : "voucher","title" : "@lang('acc_voucher.voucher')" },
                                                {"field" : "description","title" : "@lang('acc_voucher.description')" },
                                                {"field" : "subject","title" :  "@lang('acc_voucher.subject')" } ]

        Ermis.column_grid = [{ "field" : "id",hidden: true },
                            { "field" : "date_invoice","title" : "@lang('acc_voucher.date_invoice')", type:"date" ,editor : DatePickerEditor ,template: '#= FormatDate(date_invoice) #' ,width : '150px'},
                            { "field" : "invoice_form","title" : "@lang('acc_voucher.invoice_form')"  ,width : '150px'},
                            { "field" : "invoice_symbol","title" : "@lang('acc_voucher.invoice_symbol')"  ,width : '150px'},
                            { "field" : "invoice","title" : "@lang('acc_voucher.invoice')"  ,width : '150px'},
                            { "field" : "subject_code","title" : "@lang('acc_voucher.subject_code')"  ,width : '150px'},
                            { "field" : "subject_name","title" : "@lang('acc_voucher.subject_name')"  ,width : '150px'},
                            { "field" : "tax_code","title" : "@lang('acc_voucher.tax_code')"  ,width : '150px'},
                            { "field" : "address","title" : "@lang('acc_voucher.address')"  ,width : '150px'},
                            { "field" : "description","title" : "@lang('acc_voucher.description')",width : '200px'  },
                            { "field" : "vat_type","title" : "@lang('acc_voucher.vat_type')",width : '200px'  },
                            { "field" : "amount","title" : "@lang('acc_voucher.amount')" ,type:"number",format: "{0:n{{$decimal}}}",decimals: {{$decimal}} ,aggregates: ['sum'],footerTemplate: "<p id='amount_total_tax'>#=FormatNumberDecimal(sum,{{$decimal}})#</p>" ,width : '150px'},
                            { "field" : "tax","title" : "@lang('acc_voucher.tax')" ,editor: ItemsDropDownEditor , "select" : "OnchangeItem" ,template : "#=getDataItemName(tax,'tax')#"  ,"width" : "150px"},
                            { "field" : "total_amount","title" : "@lang('acc_voucher.total_amount')" ,format: "{0:n{{$decimal}}}",decimals: {{$decimal}} ,aggregates: ['sum'] , template: "#=calculateAmountTax(amount, tax.code, {{$decimal}} )#" ,footerTemplate: "<p id='total_amount'>#=calculateTotalVatAggregate({{$decimal}})#</p>" ,width : '150px'}];


        Ermis.columns    = [{"field" :"id", hidden : true},
                            { "field" : "accounted_fast","title" :"@lang('acc_voucher.accounted_fast')" ,editor: ItemsDropDownEditor , "select" : "OnchangeCancel" ,template : "#=getDataItemName(accounted_fast,'accounted_fast')#","width" : "150px" ,"set" : "5" },
                            { "field" : "description","title" : "@lang('acc_voucher.description')" ,"width" : "200px" ,aggregates: ['count'], footerTemplate: "<p>Total Count: #=count#</p>","set" : "6"  },
                            { "field" : "debit","title" :"@lang('acc_voucher.debt_account')" ,editor: ItemsDropDownEditor , "select" : "OnchangeItem" ,template : "#=getDataItemName(debit,'debit')#" ,"width" : "150px" ,"set" : "2" , "key" :true },
                            { "field" : "credit","title" :"@lang('acc_voucher.credit_account')" ,editor: ItemsDropDownEditor , "select" : "OnchangeItem" ,template : "#=getDataItemName(credit,'credit')#" ,"width" : "150px","set" : "2" , "key" :true},
                            { "field" : "amount","title" : "@lang('acc_voucher.amount')" ,"width" : "200px",format: "{0:n{{$decimal}}}",decimals: {{$decimal}} ,aggregates: ['sum'],footerTemplate: "<p id='amount_total'>#=FormatNumberDecimal(sum,{{$decimal}})#</p>" },
                            { "field" : "rate","title" :"@lang('acc_voucher.rate')",format: "{0:n{{$decimal}}}",decimals: {{$decimal}},"width" : "150px"  },
                            { "field" : "amount_rate","title" : "@lang('acc_voucher.amount_rate')" ,"width" : "200px",format: "{0:n{{$decimal}}}",decimals: {{$decimal}} ,aggregates: ['sum'] , template: "#=calculateAmountRate(amount, rate, {{$decimal}} )#" ,footerTemplate: "<p id='amount_rate_total'>#=calculateTotalRateAggregate({{$decimal}})#</p>" },
                            { "field" : "subject_id", hidden: true ,"set" : "6" },
                            { "field" : "subject_code","title" : "@lang('acc_voucher.subject_code')"  ,width : '150px',editor: ItemsDropDownEditor , "select" : "OnchangeGroup" ,template : "#=getDataItemName(subject_code,'subject_code')#" ,"set" : "2" , "group" : "1" },
                            { "field" : "subject_name","title" : "@lang('acc_voucher.subject_name')"  ,width : '150px',"set" : "6" ,  "group" : "1"},
                            { "field" : "department","title" :"@lang('acc_voucher.department')",editor: ItemsDropDownEditor , "select" : "OnchangeItem" ,template : "#=getDataItemName(department,'department')#"  ,"width" : "150px" ,"set" : "2" },
                            { "field" : "bank_account","title" :"@lang('acc_voucher.bank_account')",editor: ItemsDropDownEditor , "select" : "OnchangeItem" ,template : "#=getDataItemName(bank_account,'bank_account')#"  ,"width" : "150px" ,"set" : "2" },
                            { "field" : "cost_code","title" :"@lang('acc_voucher.cost_code')",editor: ItemsDropDownEditor , "select" : "OnchangeItem" ,template : "#=getDataItemName(cost_code,'cost_code')#","width" : "150px" ,"set" : "2" },
                            { "field" : "case_code","title" :"@lang('acc_voucher.case_code')",editor: ItemsDropDownEditor , "select" : "OnchangeItem" ,template : "#=getDataItemName(case_code,'case_code')#","width" : "150px" ,"set" : "2" },
                            { "field" : "statistical_code","title" :"@lang('acc_voucher.statistical_code')",editor: ItemsDropDownEditor , "select" : "OnchangeItem" ,template : "#=getDataItemName(statistical_code,'statistical_code')#" ,"width" : "150px" ,"set" : "2" },
                            { "field" : "work_code","title" :"@lang('acc_voucher.work_code')",editor: ItemsDropDownEditor , "select" : "OnchangeItem" ,template : "#=getDataItemName(work_code,'work_code')#" ,"width" : "150px"  ,"set" : "2"},
                            { "field" : "lot_number","title" : "@lang('acc_voucher.lot_number')" ,"width" : "150px" },
                            { "field" : "contract","title" : "@lang('acc_voucher.contract')" ,"width" : "150px" },
                            { "field" : "order","title" : "@lang('acc_voucher.order')" ,"width" : "200px"} ];


        Ermis.field = {
            id : {field :"id" ,defaultValue: 0},
            description:     {field : "description"},
            amount:     {field : "amount",type:"number" , defaultValue : 0 , validation: { min: 1, required: true }},
            rate:     {field : "rate",type:"number", defaultValue : jQuery(".rate").val() , validation: { min: 0, required: true }},
            amount_rate:     {field : "amount_rate",type:"number" , defaultValue : 0 , validation: { min: 1, required: true }},
            case_code: { field : "case_code", defaultValue: {id: 0 , code: "---SELECT---", name: "---SELECT---"} },
            cost_code: { field : "cost_code", defaultValue: {id: 0 , code: "---SELECT---", name: "---SELECT---"} },
            statistical_code: { field : "statistical_code", defaultValue: {id: 0 , code: "---SELECT---", name: "---SELECT---"} },
            subject_code: { field : "subject_code", defaultValue: {id: 0 , code: "---SELECT---", name: "---SELECT---"} },
            work_code: { field : "work_code", defaultValue: {id: 0 , code: "---SELECT---", name: "---SELECT---"} },
            department: { field : "department", defaultValue: {id: 0 , code: "---SELECT---", name: "---SELECT---"} },
            bank_account: { field : "bank_account", defaultValue: {id: 0 , code: "---SELECT---", name: "---SELECT---"} },
            debit: { field : "debit", defaultValue: {id: "{{$debt_default->id}}", code: "{{$debt_default->code}}", name: "{{$debt_default->code}}" }, validation: { min: 1 ,required: true }},
            credit: { field : "credit", defaultValue:{id: "{{$credit_default['id']}}" , code: "{{$credit_default['code']}}" , name: "{{$credit_default['name']}}"}, validation: { min: 1, required: true }},
            accounted_fast: { field : "accounted_fast", defaultValue: {id: 0 , code: "---SELECT---", name: "---SELECT---"} },
            lot_number:     {field : "lot_number"},
            contract:     {field : "contract"},
            order:     {field : "order"},
        };

        Ermis.field_tax = {
            id : {field :"id" ,defaultValue: 0},
            date_invoice : {field :"date_invoice" , defaultValue : "{{ Carbon\Carbon::now()->format('Y-m-d') }}" },
            invoice_form:     {field : "invoice_form"},
            invoice_symbol:     {field : "invoice_symbol"},
            invoice:     {field : "invoice"},
            subject_code:     {field : "subject_code",editable: false},
            subject_name:     {field : "subject_name"},
            tax_code:     {field : "tax_code"},
            address:     {field : "address"},
            description:     {field : "description"},
            vat_type:     {field : "vat_type"},
            amount:     {field : "amount",type:"number" , defaultValue : 0, validation: { min: 0, required: true }},
            tax:     {field : "tax", defaultValue: {id: 0 , code : 0 , name: "---SELECT---"}, validation: { required: true }},
            total_amount:     {field : "total_amount",type:"number" , validation: { min: 0, required: true }},

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
document.write('<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.de-DE.min.js') }}"></script>')
document.write('<script>kendo.culture('de-DE')</script>')
@endif
<script src="{{ url('addon/scripts/ermis/ermis-form-global-detail.js') }}"></script>
<script src="{{ url('addon/scripts/ermis/ermis-form-4-detail-dom-file.js') }}"></script>
@endsection
