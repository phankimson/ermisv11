<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Kendo UI Snippet</title>
    <link rel="icon" type="image/png" href="http://localhost:8000/addon/img/icon.png" sizes="16x16">
     <link rel="icon" type="image/png" href="http://localhost:8000/addon/img/icon.png" sizes="32x32">


     <title>Công ty TNHH ACB - ERMIS ACC - Currency</title>
      <link rel="stylesheet" href="http://localhost:8000/library/handsontable/dist/handsontable.min.css" media="all">




     <!-- uikit -->
     <link rel="stylesheet" href="http://localhost:8000/library/uikit/css/uikit.almost-flat.min.css" media="all">

     <!-- flag icons -->
     <link rel="stylesheet" href="http://localhost:8000/assets/icons/flags/flags.min.css" media="all">

     <link rel="stylesheet" href="http://localhost:8000/library/kendoui/styles/kendo.common.min.css" />
     <link rel="stylesheet" href="http://localhost:8000/library/kendoui/styles/kendo.material.min.css" />
     <link rel="stylesheet" href="http://localhost:8000/library/kendoui/styles/kendo.default.mobile.min.css" />
     <link rel="stylesheet" href="http://localhost:8000/addon/css/customize2.css" />
     <link rel="stylesheet" href="http://localhost:8000/addon/css/customize1.css" />
     <!-- altair admin -->
     <link rel="stylesheet" href="http://localhost:8000/assets/css/main.min.css" media="all">

       <meta name="csrf-token" content="DSboL315qPHi1WRmW3TXWybVtgApUEOg1CmDvRe7" />
       <meta name="locale" content="en"/>



<div id="example">
   <div id="window">
     <ul class="uk-tab" data-uk-tab>
         <li class="uk-active" data-id ="0"><a href="javascript:;" >@lang('acc_currency.info')</a></li>
         <li data-id ="1"><a href="javascript:;" >@lang('acc_currency.read')</a></li>
         <li data-id ="2"><a href="javascript:;" >@lang('acc_currency.denominations')</a></li>
         <li data-id ="3"><a href="javascript:;">@lang('global.expand')</a></li>
     </ul>
                <div id="tabs_content" class="uk-switcher-hot uk-margin">
                <div class="uk-tab-content">
                    <div id="ab"></div>
                </div>
                <div class="uk-tab-content">
                b
                </div>
                <div class="uk-tab-content">
                  c
                </div>
                <div class="uk-tab-content">
                  d
                </div>
              </div>
     </div>
</div>
    <span id="undo" class="k-button hide-on-narrow">Click here to open the window.</span>

    <div class="responsive-message"></div>
    <!-- common functions -->
    <!-- uikit functions -->
    <!-- common functions -->
    <script src="{{ asset('assets/js/common.min.js') }}"></script>
    <!-- uikit functions -->
    <script src="{{ asset('assets/js/uikit_custom.min.js') }}"></script>
    <!-- altair common functions/helpers -->
    <script src="{{ asset('mix/socket.js')}}"></script>
    <script src="{{ asset('assets/js/altair_admin_common.min.js') }}"></script>
    <!-- altair common functions/helpers -->

    <script src="http://localhost:8000/library/kendoui/js/kendo.all.min.js"></script>
    <script src="http://localhost:8000/library/handsontable/dist/handsontable.full.min.js"></script>


    <script>
        $(document).ready(function () {
      var data = [
      ['', 'Ford', 'Tesla', 'Toyota', 'Honda'],
      ['2017', 10, 11, 12, 13],
      ['2018', 20, 11, 14, 13],
      ['2019', 30, 15, 12, 13]
    ];
    var container = document.getElementById('ab');

      var hot = new Handsontable(container, {
      data: data,
      rowHeaders: true,
      colHeaders: true,
      filters: true,
      dropdownMenu: true,
      contextMenu: true,
      licenseKey: 'non-commercial-and-evaluation'
    });
    jQuery('#tabs_anim .uk-tab-content').fadeOut("fast","linear");
    jQuery('ul.uk-tab').on('change.uk.tab', function(e, active, previous) {
        jQuery('#tabs_anim .uk-tab-content').fadeOut("fast","linear");
        var a = jQuery(active.context).attr('data-id');
        if(!a){
          a = jQuery('ul.uk-tab .uk-active').attr('data-id');
        };
        var b = jQuery('#tabs_anim .uk-tab-content:eq('+parseInt(a)+')');
        b.fadeIn("fast","linear");
 });

            var myWindow = $("#window"),
                undo = $("#undo");
            undo.click(function() {
                myWindow.data("kendoWindow").open();
                undo.fadeOut();
            });

            function onClose() {
                undo.fadeIn();
            }

            myWindow.kendoWindow({
                width: "600px",
                title: "About Alvar Aalto",
                visible: false,
                actions: [
                    "Pin",
                    "Minimize",
                    "Maximize",
                    "Close"
                ],
                modal: true,
                close: onClose
            }).data("kendoWindow").center().close();
        });
    </script>

    <style>


        #example {
            min-height: 500px;
        }

        #undo {
            text-align: center;
            position: absolute;
            white-space: nowrap;
            padding: 1em;
            cursor: pointer;
        }

        .armchair {
            float: left;
            margin: 30px 30px 120px 30px;
            text-align: center;
        }

        .armchair img {
            display: block;
            margin-bottom: 10px;
        }

        .k-window-content a {
            color: #BBB;
        }

        .k-window-content p {
            margin-bottom: 1em;
        }

        @media screen and (max-width: 1023px) {
            div.k-window {
                display: none !important;
            }
        }
    </style>
</div>


</body>
</html>
