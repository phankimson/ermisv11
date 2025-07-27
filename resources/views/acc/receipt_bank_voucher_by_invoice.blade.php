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
@include('window.window_4')
@include('window.window_5')
@include('window.window_8')
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
    @include('action.content_4',['voucher'=>$voucher])
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
        Ermis.total_payment = ".total_payment";
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

      
        Ermis.columns    = [{  "title": 'Select All',headerTemplate: '<input type="checkbox" id="header-chb-invoice" class="k-checkbox"><label class="k-checkbox-label" for="header-chb-invoice"></label>',template: function(dataItem){                   
                                return '<input type="checkbox" id="'+ dataItem.vat_detail_id+'" '+dataItem.checkbox+'  class="k-checkbox invoice"><label class="k-checkbox-label" for="'+ dataItem.vat_detail_id +'"></label>'
                            },width: 50},
                            {"field" :"id", hidden : true},
                            {"field" :"detail_id", hidden : true},
                            {"field" :"vat_detail_id", hidden : true},
                            { "field" : "invoice","title" : "@lang('acc_voucher.invoice')"  ,width : '150px'},
                            { "field" : "date_invoice","title" : "@lang('acc_voucher.date_invoice')",template: '#= FormatDate(date_invoice) #',width : '150px'  },
                            { "field" : "description","title" : "@lang('acc_voucher.description')",aggregates: ['count'], footerTemplate: "<p>@lang('acc_voucher.total_count'): #=count#</p>",width : '200px'  },                           
                            { "field" : "total_amount","title" : "@lang('acc_voucher.total_amount')" ,format: "{0:n{{$decimal}}}",decimals: "{{$decimal}}" ,aggregates: ['sum'] ,footerTemplate: "<p id='total_amount'>#=FormatNumberDecimal(sum,{{$decimal}})#</p>" ,width : '150px'},
                            { "field" : "paid","title" : "@lang('acc_voucher.paid')" ,format: "{0:n{{$decimal}}}",decimals: "{{$decimal}}" ,aggregates: ['sum'] ,footerTemplate: "<p id='total_paid'>#=FormatNumberDecimal(sum,{{$decimal}})#</p>"  ,width : '150px'},
                            { "field" : "remaining","title" : "@lang('acc_voucher.remaining')" ,format: "{0:n{{$decimal}}}",decimals: "{{$decimal}}" ,aggregates: ['sum'] ,footerTemplate: "<p id='total_remaining'>#=FormatNumberDecimal(sum,{{$decimal}})#</p>"  ,width : '150px'},
                            { "field" : "payment",editor: MaxValueEditor,"maxValueColumn" : "remaining","title" : "@lang('acc_voucher.payment')" ,format: "{0:n{{$decimal}}}",decimals: "{{$decimal}}" ,aggregates: ['sum'] ,footerTemplate: "<p id='total_payment'>#=FormatNumberDecimalLinkInput(sum,{{$decimal}},Ermis.total_payment)#</p>" ,width : '150px'},
                            { "field" : "rate","title" :"@lang('acc_voucher.rate')",format: "{0:n{{$decimal}}}",decimals: "{{$decimal}}","width" : "150px"  },
                            { "field" : "payment_rate","title" : "@lang('acc_voucher.payment_rate')" ,"width" : "200px",format: "{0:n{{$decimal}}}",decimals: "{{$decimal}}" ,aggregates: ['sum'] ,footerTemplate: "<p id='payment_rate_total'>#=calculateTotalRatePaymentAggregate({{$decimal}})#</p>" },];
                      
        Ermis.field = {
            id : {field :"id"},
            detail_id : {field :"detail_id"},
            vat_detail_id : {field :"vat_detail_id"},
            checkbox:     {field : "checkbox",defaultValue: ""},
            invoice:     {field : "invoice",editable: false},
            date_invoice:     {field : "date_invoice",editable: false},
            description:     {field : "description",editable: false},
            total_amount:     {field : "total_amount",type:"number" , defaultValue : 0 ,editable: false},
            paid:     {field : "paid",type:"number" , defaultValue : 0 , editable: false},
            remaining:     {field : "remaining",type:"number" , defaultValue : 0 , editable: false},
            payment:     {field : "payment", type:"number" , defaultValue : 0 , validation: { min: 0 , required: true }},  
            rate:     {field : "rate",type:"number", defaultValue : parseInt(jQuery(".rate").val()) , validation: { min: 0, required: true }},
            payment_rate:     {field : "payment_rate",type:"number" , defaultValue : 0 , validation: { min: 1, required: true }},         
        };

        Ermis.aggregate = [ { field: "description", aggregate: "count" },
                            { field: "total_amount", aggregate: "sum" },
                            { field: "paid", aggregate: "sum" },
                            { field: "remaining", aggregate: "sum" },
                            { field: "payment", aggregate: "sum" },
                            { field: "payment_rate", aggregate: "sum" }
                        ];
    });
</script>


@endsection
@section('scripts_end')
@if($decimal_symbol === ".")
<script src="{{ asset('library/kendoui/js/cultures/kendo.culture.en-DE.min.js') }}"></script>
<script>kendo.culture('en-DE')</script>
@endif
<script src="{{ url('addon/scripts/ermis/ermis-form-global-detail.js') }}"></script>
<script src="{{ url('addon/scripts/ermis/ermis-form-5-detail-dom-file.js') }}"></script>
@endsection
