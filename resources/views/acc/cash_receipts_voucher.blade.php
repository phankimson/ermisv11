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
@include('window.window_2',['code' => $voucher->code])
@include('window.window_3')
@include('window.window_4')
@include('window.window_5')
@include('window.window_6')
@include('window.window_10')
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
  @include('action.content_1',['voucher'=>$voucher,'menu'=>$menu])
</div>
@endsection

@section('tabs')
<div id="tabstrip">
    <ul style="display:none">
        <li class="k-state-active">@lang('acc_voucher.detail')</li>
        <li>@lang('acc_voucher.vat')</li>
        <li>@lang('global.expand')</li>
    </ul>
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
<!--<div class="uk-width-medium-4-4">
    <div id="grid"></div>
</div>-->
@endsection

@push('context_action')
@include('action.context_5')
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
        Ermis.type = "{{$menu}}";
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
                                {"field" : "tax_code", hidden: true},
                                {"field" : "invoice_form", hidden: true},
                                {"field" : "invoice_symbol", hidden: true},]

        Ermis.columns_reference = [{title: 'Select All',headerTemplate: '<input type="checkbox" id="header-chb-reference" class="k-checkbox reference"><label class="k-checkbox-label" for="header-chb-reference"></label>',template: function(dataItem){return '<input type="checkbox" id="'+ dataItem.id+'" class="k-checkbox reference"><label class="k-checkbox-label" for="'+ dataItem.id +'"></label>'},width: 80},
                                                {"field" : "voucher_date","title" : "@lang('acc_voucher.voucher_date')",template: '#= FormatDate(voucher_date)#' },
                                                {"field" : "voucher","title" : "@lang('acc_voucher.voucher')" },
                                                {"field" : "description","title" : "@lang('acc_voucher.description')" },
                                                {"field" : "total_amount","title" :  "@lang('acc_voucher.total_amount')" ,template: '#= FormatNumberDecimal(total_amount, {{$decimal}} )#' } ];

               Ermis.columns_barcode = [{title: 'Select All',headerTemplate: '<input type="checkbox" id="header-chb-barcode" class="k-checkbox barcode"><label class="k-checkbox-label" for="header-chb-barcode"></label>',template: function(dataItem){return '<input type="checkbox" id="'+ dataItem.id+'" class="k-checkbox barcode"><label class="k-checkbox-label" for="'+ dataItem.id +'"></label>'},width: 80},
                                                {"field" : "code","title" : "@lang('acc_supplies_goods.code')" },
                                                {"field" : "name","title" : "@lang('acc_supplies_goods.name')" },
                                                {"field" : "unit","title" : "@lang('acc_supplies_goods.unit')" },
                                                {"field" : "quantity_in_stock","title" : "@lang('acc_voucher.quantity_in_stock')",format: "{0:n{{$decimal}}}",decimals: "{{$decimal}}" },
                                                {"field" : "price","title" :  "@lang('acc_supplies_goods.price')" ,format: "{0:n0}" ,decimals: 0 },
                                                {"field" : "price_purchase","title" : "@lang('acc_supplies_goods.price_purchase')" ,format: "{0:n0}" ,decimals: 0 } ];

        Ermis.column_grid = [{ "field" : "id",hidden: true },
                            { "field" : "date_invoice","title" : "@lang('acc_voucher.date_invoice')", type:"date" ,editor : DatePickerEditor ,template: '#= FormatDate(date_invoice) #' ,width : '150px'},
                            { "field" : "invoice_form","title" : "@lang('acc_voucher.invoice_form')"  ,width : '150px'},
                            { "field" : "invoice_symbol","title" : "@lang('acc_voucher.invoice_symbol')"  ,width : '150px'},
                            { "field" : "invoice","title" : "@lang('acc_voucher.invoice')"  ,width : '150px'},
                            { "field" : "subject_id",hidden: true },
                            { "field" : "subject_code","title" : "@lang('acc_voucher.subject_code')"  ,width : '150px'},
                            { "field" : "subject_name","title" : "@lang('acc_voucher.subject_name')"  ,width : '150px'},
                            { "field" : "tax_code","title" : "@lang('acc_voucher.tax_code')"  ,width : '150px'},
                            { "field" : "address","title" : "@lang('acc_voucher.address')"  ,width : '200px'},
                            { "field" : "description","title" : "@lang('acc_voucher.description')",width : '200px',aggregates: ['count'], footerTemplate: "<p>@lang('acc_voucher.total_count'): #=count#</p>"},
                            { "field" : "vat_type","title" : "@lang('acc_voucher.vat_type')",width : '200px',"url" : "{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.vat-tax').'?type=1'}}",editor: ItemsReadDropDownEditor , "select" : "OnchangeGroup" ,template : "#=getUrlAjaxItemName(vat_type,'vat_type')#", "group" : "1" },
                            { "field" : "vat_tax","title" : "@lang('acc_voucher.vat_tax')",width : '100px', "group" : "1"},
                            { "field" : "amount","title" : "@lang('acc_voucher.amount')" ,type:"number",format: "{0:n{{$decimal}}}",decimals: "{{$decimal}}" ,aggregates: ['sum'],footerTemplate: "<p id='amount_total_tax'>#=FormatNumberDecimal(sum,{{$decimal}})#</p>" ,width : '150px'},
                            { "field" : "tax","title" : "@lang('acc_voucher.tax')" ,type:"number",format: "{0:n{{$decimal}}}",aggregates: ['sum'], decimals: "{{$decimal}}" ,aggregates: ['sum'],footerTemplate: "<p id='total_tax'>#=calculateVatAggregate({{$decimal}})#</p>" ,width : '150px'},
                            { "field" : "tax_amount","title" : "@lang('acc_voucher.total_amount')" ,format: "{0:n{{$decimal}}}",decimals: "{{$decimal}}" ,aggregates: ['sum'] ,footerTemplate: "<p id='total_amount'>#=calculateTotalVatAggregate({{$decimal}})#</p>" ,width : '150px'},
                            { "field" : "tax_rate","title" :"@lang('acc_voucher.rate')",format: "{0:n{{$decimal}}}",decimals: "{{$decimal}}","width" : "150px"  },
                            { "field" : "tax_amount_rate","title" : "@lang('acc_voucher.total_amount_rate')" ,"width" : "200px",format: "{0:n{{$decimal}}}",decimals: "{{$decimal}}" ,aggregates: ['sum'] ,footerTemplate: "<p id='total_amount_rate'>#=calculateTotalRateVatAggregate({{$decimal}})#</p>" },];


        Ermis.columns    = [{"field" :"id", hidden : true},
                            { "field" : "accounted_fast","title" :"@lang('acc_voucher.accounted_fast')","url" : "{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.accounted-fast').'?pro='.$menu}}" ,editor: ItemsReadDropDownEditor , "select" : "OnchangeCancel" ,template : "#=getUrlAjaxItemName(accounted_fast,'accounted_fast')#","width" : "150px" ,"set" : "5" },
                            { "field" : "description","title" : "@lang('acc_voucher.description')" ,"width" : "200px" ,aggregates: ['count'], footerTemplate: "<p>@lang('acc_voucher.total_count'): #=count#</p>","set" : "6"  },
                            { "field" : "debit","title" :"@lang('acc_voucher.debt_account')" ,"url" : "{!!route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.account-voucher-filter').'?menu='.$menu.'&type=1&option=true'!!}",editor: ItemsReadDropDownEditor , "select" : "OnchangeItem" ,template : "#=getUrlAjaxItemName(debit,'debit')#" ,"width" : "150px" ,"set" : "2" , "key" :true },
                            { "field" : "credit","title" :"@lang('acc_voucher.credit_account')" ,"url" : "{!!route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.account-voucher-filter').'?menu='.$menu.'&type=2&option=true'!!}",editor: ItemsReadDropDownEditor , "select" : "OnchangeItem" ,template : "#=getUrlAjaxItemName(credit,'credit')#" ,"width" : "150px","set" : "2" , "key" :true},
                            { "field" : "amount","title" : "@lang('acc_voucher.amount')" ,"width" : "200px",format: "{0:n{{$decimal}}}",decimals: "{{$decimal}}" ,aggregates: ['sum'],footerTemplate: "<p id='amount_total'>#=FormatNumberDecimal(sum,{{$decimal}})#</p>" },
                            { "field" : "rate","title" :"@lang('acc_voucher.rate')",format: "{0:n{{$decimal}}}",decimals: "{{$decimal}}","width" : "150px"  },
                            { "field" : "amount_rate","title" : "@lang('acc_voucher.amount_rate')" ,"width" : "200px",format: "{0:n{{$decimal}}}",decimals: "{{$decimal}}" ,aggregates: ['sum']  ,footerTemplate: "<p id='amount_rate_total'>#=calculateTotalRateAggregate({{$decimal}})#</p>" },
                            { "field" : "subject_id", hidden: true ,"set" : "6" , "group" : "1"},
                            { "field" : "subject_code","title" : "@lang('acc_voucher.subject_code')"  ,"url" : "{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.object')}}" ,width : '150px',editor: ItemsReadDropDownEditor , "select" : "OnchangeGroup" ,template : "#=getUrlAjaxItemName(subject_code,'subject_code','subject_code')#" ,"set" : "2" , "group" : "1" },
                            { "field" : "subject_name","title" : "@lang('acc_voucher.subject_name')"  ,width : '150px',"set" : "6" ,  "group" : "1"},
                            { "field" : "department","title" :"@lang('acc_voucher.department')", "url" : "{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.department')}}" ,editor: ItemsReadDropDownEditor , "select" : "OnchangeItem" ,template : "#=getUrlAjaxItemName(department,'department')#"  ,"width" : "150px" ,"set" : "2" },
                            { "field" : "bank_account","title" :"@lang('acc_voucher.bank_account')","url" : "{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.bank-account')}}" , editor: ItemsReadDropDownEditor , "select" : "OnchangeItem" ,template : "#=getUrlAjaxItemName(bank_account,'bank_account')#"  ,"width" : "150px" ,"set" : "2" },
                            { "field" : "cost_code","title" :"@lang('acc_voucher.cost_code')","url" : "{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.cost-code')}}" ,editor: ItemsReadDropDownEditor , "select" : "OnchangeItem" ,template : "#=getUrlAjaxItemName(cost_code,'cost_code')#","width" : "150px" ,"set" : "2" },
                            { "field" : "case_code","title" :"@lang('acc_voucher.case_code')","url" : "{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.case-code')}}",editor: ItemsReadDropDownEditor , "select" : "OnchangeItem" ,template : "#=getUrlAjaxItemName(case_code,'case_code')#","width" : "150px" ,"set" : "2" },
                            { "field" : "statistical_code","title" :"@lang('acc_voucher.statistical_code')", "url" : "{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.statistical-code')}}" ,editor: ItemsReadDropDownEditor , "select" : "OnchangeItem" ,template : "#=getUrlAjaxItemName(statistical_code,'statistical_code')#" ,"width" : "150px" ,"set" : "2" },
                            { "field" : "work_code","title" :"@lang('acc_voucher.work_code')","url" : "{{route(env('URL_API').'.acc.'.env('URL_DROPDOWN').'.work-code')}}",editor: ItemsReadDropDownEditor , "select" : "OnchangeItem" ,template : "#=getUrlAjaxItemName(work_code,'work_code')#" ,"width" : "150px"  ,"set" : "2"},
                            //{ "field" : "work_code","title" :"@lang('acc_voucher.work_code')",editor: ItemsDropDownEditor , "select" : "OnchangeItem" ,template : "#=getUrlItemName(work_code,'work_code')#" ,"width" : "150px"  ,"set" : "2"},
                            { "field" : "lot_number","title" : "@lang('acc_voucher.lot_number')" ,"width" : "150px" },
                            { "field" : "contract","title" : "@lang('acc_voucher.contract')" ,"width" : "150px" },
                            { "field" : "order","title" : "@lang('acc_voucher.order')" ,"width" : "200px"} ];


        Ermis.field = {
            id : {field :"id" ,defaultValue: 0},
            description:     {field : "description"},
            amount:     {field : "amount",type:"number" , defaultValue : 0 , validation: { min: 1, required: true }},
            rate:     {field : "rate",type:"number", defaultValue : parseInt(jQuery(".rate[name='rate']").val())  , validation: { min: 0, required: true }},
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

        Ermis.field_tax = {
            id : {field :"id" ,defaultValue: 0},
            date_invoice : {field :"date_invoice" , defaultValue : "{{ Carbon\Carbon::now()->format('Y-m-d') }}" },
            invoice_form:     {field : "invoice_form"},
            invoice_symbol:     {field : "invoice_symbol"},
            invoice:     {field : "invoice"},
            subject_id : {field :"subject_id" ,defaultValue: 0},
            subject_code:     {field : "subject_code",editable: false},
            subject_name:     {field : "subject_name"},
            tax_code:     {field : "tax_code"},
            address:     {field : "address"},
            description:     {field : "description"},
            vat_type:     {field : "vat_type", defaultValue: DefaultReadValueField(), validation: { required: true }},
            vat_tax:     {field : "vat_tax" , defaultValue : 0, editable: false},
            amount:     {field : "amount",type:"number" , defaultValue : 0, validation: { min: 0, required: true }},
            tax:     {field : "tax" ,type:"number", defaultValue : 0 },
            tax_amount:     {field : "tax_amount",type:"number" , defaultValue : 0, validation: { min: 0, required: true }},
            tax_rate:     {field : "tax_rate",type:"number", defaultValue : parseInt(jQuery(".rate").val()) , validation: { min: 0, required: true }},
            tax_amount_rate:     {field : "tax_amount_rate",type:"number" , defaultValue : 0 , validation: { min: 1, required: true }},
        };
        Ermis.aggregate = [ { field: "description", aggregate: "count" },
                            { field: "amount", aggregate: "sum" },
                            { field: "amount_rate", aggregate: "sum" },
                            { field: "tax", aggregate: "sum" },
                            { field: "tax_amount", aggregate: "sum" },
                            { field: "tax_amount_rate", aggregate: "sum" }];

                                                    
    });
</script>


@endsection
@section('scripts_end')
@if($decimal_symbol === ".")
<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.en-DE.min.js') }}"></script>
<script>kendo.culture('en-DE')</script>
@endif
<script src="{{ url('addon/scripts/ermis/ermis-form-global-detail.js') }}"></script>
<script src="{{ url('addon/scripts/ermis/ermis-form-4-detail-dom-file.js') }}"></script>
@endsection
