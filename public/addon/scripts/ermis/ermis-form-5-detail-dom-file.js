var Ermis = function() {
    // Payment Receipt by invoice
    var initGetShortKey = function() {
        return key = Ermis.short_key;
    };


    var initGlobalRegister = function(){
        // MonthPickerTemplate
        ErmisKendoMonthPickerTemplate(".month-picker","year","year","MM/yyyy");
        //StartEndDroplistTemplate
        ErmisKendoStartEndDroplistTemplate("#start","#end","dd/MM/yyyy","#fast_date","contains");
        ErmisKendoStartEndDroplistTemplate("#start_a","#end_a","dd/MM/yyyy","#fast_date_a","contains");
        ErmisKendoStartEndDroplistTemplate("#start_b","#end_b","dd/MM/yyyy","#fast_date_b","contains");
        //DroplistTemplate
        jQuery('.droplist.read').each(function() {
            ErmisKendoDroplistReadTemplate(this, "contains");
          }); 
          ErmisKendoDroplistTemplate(".droplist:not(.read)", "contains");
        //DatePickerTemplate
        ErmisKendoDatePickerTemplate(".date-picker","dd/MM/yyyy");
        //ContextMenu
        ErmisKendoContextMenuTemplate("#context-menu", "#form-action");
        // NumbericTemplate
        ErmisKendoNumbericTemplate(".number", "n" + Ermis.decimal, null, null, null, 1);
        ErmisKendoNumbericTemplate(".number-price", "n" + Ermis.decimal, null, null, null, 1000);
        // KendoWindowTemplate
        $kWindow = ErmisKendoWindowTemplate(myWindow, "600px", ""); 
        $kWindow3 = ErmisKendoWindowTemplate(myWindow3, "1000px", "");
        $kWindow4 = ErmisKendoWindowTemplate(myWindow4, "400px", "");
        $kWindow6 = ErmisKendoWindowTemplate(myWindow6, "800px", "");       
        $kWindow.title(Lang.get('acc_voucher.search_for_object'));
        $kWindow3.title(Lang.get('acc_voucher.search_for_voucher'));
        $kWindow4.title(Lang.get('acc_voucher.attach'));
        $kWindow6.title(Lang.get('global.get_data'));
        // Grid
        ErmisKendoGridCheckboxTemplate3($kGrid, Ermis.data, Ermis.aggregate, Ermis.field, Ermis.page_size , {
            confirmation: false
        }, jQuery(window).height() * 0.5, Ermis.columns,onSave,grid_header_key,onChecked,"vat_detail_id");
        initKendoGridChange();
    }

    var onChecked = function(t,dataItem){        
        if(t == 0){
            dataItem.set("payment", 0); 
            dataItem.set("checkbox", "");   
            initDescription(2,dataItem['invoice']);         
        }else{
            dataItem.set("payment", dataItem['remaining']); 
            dataItem.set("checkbox", "checked"); 
            initDescription(1,dataItem['invoice']);               
        };   
    }
    

    var onSave = function(data){  
       // var grid = this;
       //     setTimeout(function() {
                //grid.refresh();
       //     });        
    }

    var initLoadData = function(dataId) {
        var postdata = {
            data: JSON.stringify(dataId)
        };

        ErmisTemplateAjaxPost0(null, postdata, Ermis.link + '-bind',
            function(result) {
                if (result.data) {
                    initActive(result.data.active);
                    SetDataAjax(data.columns, result.data);
                    initLoadGrid(result.data.detail);
                    var grid = $kGrid.data("kendoGrid");
                    var checkHeader = false;
                    checked_header_grid(grid,grid_header_key,checkHeader);
                    sessionStorage.dataId = result.data.id;
                    initKendoGridChange();
                    //$kGrid.addClass('disabled');
                    //calculatePriceBind(result.data.detail);
                } else {
                    initStatus(7);
                }
            },
            function() {
                initStatus(7);
            });
    };

    var initLoadGrid = function(dataLoad){
          var grid = $kGrid.data("kendoGrid");
          ds = new kendo.data.DataSource({
              data: dataLoad,
              schema: {
                  model: {
                      fields: Ermis.field,
                      id: "id"
                  }
              },
              aggregate: Ermis.aggregate
          });
          grid.setDataSource(ds);
    }

     var initBindData = function() {
        if (sessionStorage.dataId) {
            var dataId = sessionStorage.dataId;
            if(sessionStorage.hasOwnProperty(Ermis.type)){
                var storedId = JSON.parse(sessionStorage[Ermis.type]);
                var hasId = storedId.includes(dataId);
                if(hasId == false){
                 dataId = storedId[0];   
                }
                 initLoadData(dataId);
            }else{
                initStatus(7);
            }                      
        } else {
            initStatus(1);
        }
    };

    
    var initGetColunm = function() {
        data = GetAllDataForm('#form-action', 2);
        return data;
    };

    var initVoucherMasker = function() {
        if(Ermis.voucher.change_voucher == 1){
            return voucher = initErmisVoucher(Ermis.voucher);
        }else{
            return voucher = initErmisBarcodeMaskerHide(Ermis.voucher);
        }
        
    };

    var initCheckSession = function() {
        return status = initErmisCheckSession();
    };


    var initKendoGridVoucher = function() {
        ErmisKendoGridVoucherTemplate($kGridVoucher, {
            data: []
        }, "row", jQuery(window).height() * 0.5, true, Ermis.columns_voucher);

        $kGridVoucher.dblclick(function(e) {
            initChooseVoucher(e);
        });

    };

    var initGetDataGrid = function(){
        jQuery('.get_data').on('click', function(e) {
            var d = GetAllDataForm('#form-window-get-data', 2);
            var c = GetDataAjax(d.columns);
            var postdata = {
                data: JSON.stringify(c.obj)
            };
            ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-get-data', function(result) {
                initLoadGrid(result.data);
                initKendoGridChange();
                initDefaultIdGrid();
                jQuery('#header-chb-invoice')[0].checked = false; 
                $currency = jQuery("#currency").data("kendoDropDownList");
                $currency.value(result.currency);   
                $currency.trigger("change");
                $kWindow6.close();
            }, function(result) {
                kendo.alert(result.message);
            });
        });
    }

    var initChooseVoucher = function(e) {
        ErmisTemplateEvent1(e, function() {
            if ($kGridVoucher.find('tr.k-state-selected').length > 0) {
                var grid = $kGridVoucher.data("kendoGrid");
                var dataItem = grid.dataItem($kGridVoucher.find('tr.k-state-selected'));
                $kWindow3.close();
                initLoadData(dataItem.id)
            } else {
                kendo.alert(Lang.get('messages.please_select_line_choose'));
            }
        })
    };

    var initSearchGridVoucher = function() {
        jQuery('#search_voucher').on('click', function(e) {
            var filter = GetAllDataForm('#form-window-voucher', 2);
            var c = GetDataAjax(filter.columns);
                c.obj.type = Ermis.type;
            var postdata = {
                data: JSON.stringify(c.obj)
            };
            ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-find', function(result) {
                var grid = $kGridVoucher.data("kendoGrid");
                var ds = new kendo.data.DataSource({
                    data: result.data
                });
                jQuery.each(result.data, function(i, item) {
                    storedarrId.push(item.id);
                });
                grid.setDataSource(ds);
                grid.dataSource.page(1);
            }, function(result) {
                kendo.alert(result.message);
            });
        });
    };


    var initKendoGridSubject = function() {
        ErmisKendoGridVoucherTemplate($kGridSubject, {
            data: []
        }, "row", jQuery(window).height() * 0.5, true, Ermis.columns_subject);

        $kGridSubject.dblclick(function(e) {
            initChoose(e);
        });

    };

    var initSearchGridSubject = function() {
        jQuery('#search_data').on('click', function(e) {
            var filter = GetAllDataForm('#form-window-filter', 2);
            var c = GetDataAjax(filter.columns);
            var postdata = {
                data: JSON.stringify(c.obj)
            };
            ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-get', function(result) {
                var grid = $kGridSubject.data("kendoGrid");
                var ds = new kendo.data.DataSource({
                    data: result.data
                });
                grid.setDataSource(ds);
                grid.dataSource.page(1);
            }, function(result) {
                kendo.alert(result.message);
            });
        });

    };

    var initTabsTrip = function() {
        var ts = jQuery("#tabstrip");
        var tabStrip = ts.kendoTabStrip().data("kendoTabStrip");
        tabStrip.bind("select", onSelectedTabStrip);
        $kGridTab_column = Ermis.columns;
        ts.find('ul').show();
    };

    var onSelectedTabStrip = function(e){
      type = jQuery(e.item).index();
      if (type == 0) {
          $kGridTab = $kGrid;
          $kGridTab_column = Ermis.columns;
      }
    }


    var initKendoGridChange = function() {
        var grid = $kGrid.data("kendoGrid");
        grid.dataSource.bind("change", function(e) {
            // checks to see if the action is a change and the column being changed is what is expected
            var item = e.items[0];
            if (e.action === "itemchange" && (e.field === "payment" || e.field === "rate")) {
                // here you can access model items using e.items[0].modelName;
                item.rate == 0 ? item.set("payment_rate" , 0) : item.set("payment_rate", item.payment * item.rate);
                if(e.field === "payment" &&  item.remaining == item.payment){
                     item.set("checkbox", "checked");  
                       var a = jQuery('.k-checkbox.' + grid_header_key+":checked").not('#header-chb-' + grid_header_key).length;
                        if(a+1 == grid.items().length){
                             jQuery('#header-chb-' + grid_header_key)[0].checked = true;
                        };                       
                }else if(e.field === "payment" &&  item.remaining != item.payment){
                     item.set("checkbox", ""); 
                     var checkHeader = false;
                     jQuery('#header-chb-' + grid_header_key)[0].checked = checkHeader;   
                }else{

                }                                    
            }else if(e.action === "itemchange" && e.field === "payment_rate" ){
                // here you can access model items using e.items[0].modelName;
                item.set("payment_rate", item.payment * item.rate);
            }else{

            }         
            
             if(e.action === "itemchange" && (e.field === "payment" || e.field === "payment_rate")){
                    grid.refresh();
            }  
        });

    }


    var initStatus = function(flag) {
        shortcut.remove(key + "A");
        shortcut.remove(key + "X");
        shortcut.remove(key + "E");
        shortcut.remove(key + "S");
        shortcut.remove(key + "C");
        shortcut.remove(key + "D");
        shortcut.remove(key + ".");
        shortcut.remove(key + ",");
        shortcut.remove(key + "T");
        jQuery('.add,.copy,.edit,.delete,.back,.forward,.print,.import,.cancel,.save,.choose,.filter,.pageview,.write_item,.unwrite_item,.advance_teacher,.advance_employee').not('.back_to').addClass('disabled');
        jQuery('.add,.copy,.edit,.delete,.back,.forward,.print-item,.cancel,.save,.choose,.pageview,.filter,.write_item,.unwrite_item,.advance_teacher,.advance_employee').not('.back_to').off('click');
        jQuery('input,textarea').not(".start,.end").not('.header_main_search_input').not('#files').not('.k-filter-menu input').addClass('disabled');
        jQuery(".droplist").not('.not_disabled').addClass('disabled');
        jQuery('input:checkbox').parent().addClass('disabled');
        jQuery('.date-picker').not(".start,.end").addClass('disabled');
        jQuery(".k-input").not(".start,.end").addClass('disabled');
        jQuery('.choose_voucher').on('click', initChooseVoucher);
        jQuery('.cancel-window').on('click', initClose);  
        shortcut.add(key + "I", function(e) {
            initChooseVoucher(e);
        });
        $kGrid.addClass('disabled');
        dataDefaultGrid.data = initGetDefaultKeyArray(Ermis.field);
        if (flag === 1) { //ADD
            sessionStorage.removeItem("dataId");
            jQuery('.cancel,.save,.choose,.cancel-window,.filter,.import,.advance_teacher,.advance_employee').removeClass('disabled');
            jQuery('.cancel').on('click', initCancel);
            jQuery('.save').on('click', initSave);
            jQuery('.choose').on('click', initChoose);
            jQuery('.cancel-window').on('click', initClose);
            jQuery('.filter').on('click', initFilterForm);
            jQuery('.import').on('click', initImport);
            jQuery('.attach').on('click', initAttachForm);
            shortcut.add(key + "S", function(e) {
                initSave(e);
            });
            shortcut.add(key + "C", function(e) {
                initCancel(e);
            });
            shortcut.add(key + "I", function(e) {
                initImport(e);
            });
            jQuery('input,textarea').removeClass('disabled');
            jQuery('.k-button').removeClass('disabled');
            jQuery(".droplist").removeClass('disabled');
            jQuery(".k-input").removeClass('disabled');
            jQuery(".k-textbox").removeClass('disabled');
            jQuery('input:checkbox').parent().removeClass('disabled');
            jQuery('.date-picker,.month-picker').removeClass('disabled');
            jQuery('input').not('[type=radio]').not(".date-picker,.start,.end,.month-picker,.voucher,.fast_date,.rate,.no_clear").val("");
            jQuery(".date-picker,.end,.start").val(kendo.toString(kendo.parseDate(new Date()), 'dd/MM/yyyy'));
            jQuery(".no_copy_value").val(0);
            jQuery(".voucher").val(voucher);
            jQuery('.get-data').on('click', initGetDataForm);
            $kGrid.data('kendoGrid').dataSource.data([]);
            $kGrid.removeClass('disabled');
            shortcut.add(key + "T", function(e) {
                initDeleteRowAll(e);
            });
        } else if (flag === 2) { //SAVE
            jQuery('.add,.edit,.copy,.print,.back,.forward,.delete').removeClass('disabled');
            shortcut.add(key + "A", function(e) {
                initAdd(e);
            });
            shortcut.add(key + "X", function(e) {
                initCopy(e);
            });
            shortcut.add(key + "E", function(e) {
                initEdit(e);
            });
            shortcut.add(key + ",", function(e) {
                initBack(e);
            });
            shortcut.add(key + ".", function(e) {
                initForward(e);
            });
            jQuery('.add').on('click', initAdd);
            jQuery('.copy').on('click', initCopy);
            jQuery('.edit').on('click', initEdit);
            jQuery('.print-item').on('click', initPrint);
            jQuery('.back').on('click', initBack);
            jQuery('.forward').on('click', initForward);
            jQuery('.delete').on('click', initDelete);
            $kGrid.addClass('disabled');
            shortcut.remove(key + "T");
        } else if (flag === 3) { //EDIT
            jQuery('.cancel,.save,.filter,.advance_teacher,.advance_employee').removeClass('disabled');
            jQuery('.cancel').on('click', initCancel);
            jQuery('.save').on('click', initSave);
            jQuery('.filter').on('click', initFilterForm);
            jQuery('.attach').on('click', initAttachForm);
            jQuery('.cancel-window').on('click', initClose);
            shortcut.add(key + "S", function(e) {
                initSave(e);
            });
            shortcut.add(key + "C", function(e) {
                initCancel(e);
            });
            jQuery('input,textarea').removeClass('disabled');
            jQuery('.k-button').removeClass('disabled');
            jQuery(".droplist").removeClass('disabled');
            jQuery(".k-input").removeClass('disabled');
            jQuery(".k-textbox").removeClass('disabled');
            jQuery('input:checkbox').parent().removeClass('disabled');
            jQuery('.date-picker,.month-picker').removeClass('disabled');
            $kGrid.removeClass('disabled');
            shortcut.add(key + "T", function(e) {
                initDeleteRowAll(e);
            });
        } else if (flag === 4) { //CANCEL
            jQuery('.add,.copy,.edit,.print,.back,.forward,.delete,.pageview').removeClass('disabled');
            shortcut.add(key + "A", function(e) {
                initAdd(e);
            });
            shortcut.add(key + "X", function(e) {
                initCopy(e);
            });
            shortcut.add(key + "E", function(e) {
                initEdit(e);
            });
            shortcut.add(key + ",", function(e) {
                initBack(e);
            });
            shortcut.add(key + ".", function(e) {
                initForward(e);
            });
            jQuery('.add').on('click', initAdd);
            jQuery('.copy').on('click', initCopy);
            jQuery('.edit').on('click', initEdit);
            jQuery('.print-item').on('click', initPrint);
            jQuery('.back').on('click', initBack);
            jQuery('.forward').on('click', initForward);
            jQuery('.delete').on('click', initDelete);
            jQuery('.pageview').on('click', initVoucherForm);
            shortcut.add(key + "I", function(e) {
                initChooseVoucher(e);
            });
            if (!sessionStorage.dataId) {
                jQuery('.print,.delete,.edit').addClass('disabled');
                jQuery('.print,.delete,.edit').off('click');
            }
            jQuery('input').not('[type=radio]').not(".date-picker,.start,.end,.month-picker,.voucher,.fast_date,.rate,.no_clear").val("");
            $kGrid.data('kendoGrid').dataSource.data([]);
            $kGrid.addClass('disabled');
            shortcut.remove(key + "R");
            shortcut.remove(key + "T");
        } else if (flag === 5) { //BIND
            jQuery('.add,.copy,.edit,.print,.back,.forward,.delete,.pageview').removeClass('disabled');
            shortcut.add(key + "A", function(e) {
                initAdd(e);
            });
            shortcut.add(key + "X", function(e) {
                initCopy(e);
            });
            shortcut.add(key + "E", function(e) {
                initEdit(e);
            });
            shortcut.add(key + ",", function(e) {
                initBack(e);
            });
            shortcut.add(key + ".", function(e) {
                initForward(e);
            });
            jQuery('.add').on('click', initAdd);
            jQuery('.copy').on('click', initCopy);
            jQuery('.edit').on('click', initEdit);
            jQuery('.print-item').on('click', initPrint);
            jQuery('.back').on('click', initBack);
            jQuery('.forward').on('click', initForward);
            jQuery('.delete').on('click', initDelete);
            jQuery('.pageview').on('click', initVoucherForm);
        } else if (flag === 6) { //Write = 1
            jQuery('.add,.copy,.print,.back,.forward,.pageview').removeClass('disabled');
            shortcut.add(key + "A", function(e) {
                initAdd(e);
            });
            shortcut.add(key + "X", function(e) {
                initCopy(e);
            });
            shortcut.add(key + ".", function(e) {
                initBack(e);
            });
            shortcut.add(key + ",", function(e) {
                initForward(e);
            });
            jQuery('.add').on('click', initAdd);
            jQuery('.copy').on('click', initCopy);
            jQuery('.print-item').on('click', initPrint);
            jQuery('.back').on('click', initBack);
            jQuery('.forward').on('click', initForward);
            jQuery('.pageview').on('click', initVoucherForm);
            jQuery('.get-data').on('click', initGetDataForm);
            shortcut.add(key + "I", function(e) {
                initChooseVoucher(e);
            });
            if (!sessionStorage.dataId) {
                jQuery('.print,.delete,.edit').addClass('disabled');
                jQuery('.print,.delete,.edit').off('click');
            }
        } else if (flag === 7) { //Default not find
            jQuery('.add,.pageview').removeClass('disabled');
            shortcut.add(key + "A", function(e) {
                initAdd(e);
            });
            jQuery('.pageview').on('click', initVoucherForm);
            jQuery('.get-data').on('click', initGetDataForm);
            shortcut.add(key + "V", function(e) {
                initChooseVoucher(e);
            });
            jQuery('.add').on('click', initAdd);
            jQuery('input').not('[type=radio]').not(".date-picker,.start,.end,.month-picker,.voucher,.fast_date,.rate,.no_clear").val("");
            jQuery(".date-picker,.date-value").val(kendo.toString(kendo.parseDate(new Date()), 'dd/MM/yyyy'));
            jQuery(".voucher").val(voucher);
            $kGrid.data('kendoGrid').dataSource.data([]);
        }else if (flag === 8) { // Copy
          sessionStorage.removeItem("dataId");
          jQuery('.cancel,.save,.filter,.advance_teacher,.advance_employee').removeClass('disabled');
          jQuery('.cancel').on('click', initCancel);
          jQuery('.save').on('click', initSave);
          jQuery('.filter').on('click', initFilterForm);
          jQuery('.attach').on('click', initAttachForm);
          jQuery('.cancel-window').on('click', initClose);
          shortcut.add(key + "S", function(e) {
              initSave(e);
          });
          shortcut.add(key + "C", function(e) {
              initCancel(e);
          });
          jQuery('input,textarea').removeClass('disabled');
          jQuery('.k-button').removeClass('disabled');
          jQuery(".droplist").removeClass('disabled');
          jQuery(".k-input").removeClass('disabled');
          jQuery(".k-textbox").removeClass('disabled');
          jQuery('input:checkbox').parent().removeClass('disabled');
          jQuery('.date-picker,.month-picker').removeClass('disabled');
          $kGrid.removeClass('disabled');
          shortcut.add(key + "T", function(e) {
              initDeleteRowAll(e);
          });
            jQuery(".date-picker,.end,.start").val(kendo.toString(kendo.parseDate(new Date()), 'dd/MM/yyyy'));
            jQuery(".no_copy").val("");
            jQuery(".voucher").val(voucher);           
            jQuery(".no_copy_value").val(0);
            initDefaultIdGrid();
        }else{

        }
    };

    var initActive = function(active) {
        shortcut.remove(key + "W");
        shortcut.remove(key + "U");
        if (active === "1" || active == 1) {
            initStatus(6);
            jQuery(".unwrite_item").show();
            jQuery('.unwrite_item').removeClass('disabled');
            jQuery('.unwrite_item').on('click', initUnWrite);
            shortcut.add(key + "U", function(e) {
                initUnWrite(e);
            });
            jQuery('.write_item').addClass('disabled');
            jQuery('.write_item').off('click');
            jQuery(".write_item").hide();
        } else {
            initStatus(5);
            shortcut.add(key + "W", function(e) {
                initWrite(e);
            });
            jQuery('.write_item').on('click', initWrite);
            jQuery('.write_item').removeClass('disabled');
            jQuery(".write_item").show();
            jQuery(".unwrite_item").hide();
            jQuery('.unwrite_item').addClass('disabled');
            jQuery('.unwrite_item').off('click');
        }
    }

      
    var initDefaultIdGrid = function(){
        var grid = $kGrid.data("kendoGrid");
        var r = grid.dataSource.data();
        dataDefaultGrid.data["id"] = ""; 
        jQuery.each(r, function(l, k) {
              // Không xài k.set('id',"")
              k.id = "";
          });
          grid.refresh();            
    }

    var initChangeAuto = function() {
        function OnChangeAuto(e) {
            var value = this.value;
            var postdata = {
                data: JSON.stringify(value)
            };
            ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-auto',
                function(result) {
                    jQuery('#form-action').find("input[name='description']").val(result.data.description);
                    var grid = $kGrid.data("kendoGrid");
                    grid.dataSource.data([]);
                    jQuery.each(result.data.accounted_auto_detail, function(k, m) {
                        grid.addRow();
                        var rs = grid.dataSource.data()[0];
                        initLoadColumn(rs, m);
                    });
                    grid.refresh();
                },
                function() {

                });

        };
        $auto.bind("change", OnChangeAuto);
    }

    var initImport = function (e) {
        ErmisTemplateEvent0(e, $import,
        function () {
            $import.data("kendoDialog").open();
        },
        function () {
              initKendoUiImportDialog();
        });
    };
    
      var initKendoUiImportDialog = function () {      
        $import = ErmisKendoDialogTemplate("#import","400px","Import",'<form id="import-form" enctype="multipart/form-data" role="form" method="post"><input name="files" id="files" type="file" /></form>','Import File','Download File',"Close",onImportFile,onDownloadFile);
        ErmisKendoUploadTemplate("#files", false);
        function onImportFile(e) {
          var arr = {};
          arr.action = 'import';
          arr.com = Chat.com;
          arr.key = Ermis.link;
          ErmisTemplateAjaxPostAdd3(e,'#import-form',Ermis.link+'-import',arr,
        function(results){
            SetDataAjax(data.columns, results.data);    
            initLoadGrid(results.data[0].detail);   
            AddChooseObjectResult(results.data['object']);
          },
         function(){},
         function(results){
           kendo.alert(results.message);
         });
        }
        function onDownloadFile(e) {
            var url = Ermis.link+'-DownloadExcel';
            window.open(url);
        }  
    };

    var initChangeCurrency = function() {
        function OnChangeCurrency(e) {
            var value = this.value;
            var postdata = {
                data: JSON.stringify(value)
            };
            ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-currency',
                function(result) {
                    setTimeout(function(){
                    $rate.data("kendoNumericTextBox").value(result.data);
                    $rate.trigger("change");
                }, 0)
                },
                function() {

                });

        };

        function OnChangeRate(e) {
            var value = this.value;
            var grid = $kGrid.data("kendoGrid");
            var r = grid.dataSource.data();
            dataDefaultGrid.data["rate"] = value; 
            jQuery.each(r, function(l, k) {
                  k.set("rate",value);
                  k.set("payment_rate",value*k["payment"]);
              }); 
        }
        $currency.bind("change", OnChangeCurrency);
        $rate.on("change", OnChangeRate);
    };
    
    var initDescription = function($type,$voucher){
        var text = jQuery("input[name='description']").val();
        if($type == 1){            
            if(text == ""){
                text = Lang.get('acc_voucher.receipt_from_invoice')+" : "+$voucher;
            }else{
                var check_text = text.indexOf($voucher);
                if(check_text < 0){
                    var length_text = text.length;
                    var subtext = text.substr(length_text-2, length_text).trim();
                    var char = "";
                    if(subtext != ":"){
                        char = ",";
                    }    
                    text = text+char+$voucher;
                }            
            }
        }else{
            var check_text = text.indexOf($voucher);
                if(check_text > 0){
                    text = text.replace(","+$voucher, '');
                    text = text.replace($voucher, '');
                }     
           
        }       
        jQuery("input[name='description']").val(text);
    }


    var initChangePercentPayment = function(){
        jQuery(".percent").on("change",function(){
            var total_remaining = jQuery("#total_remaining").html();
            var total_remaining_convert = parseInt(FormatNumberHtml(total_remaining,Ermis.decimal_symbol));
            var percent = parseInt(jQuery(this).val());
            if(total_remaining_convert>0 && percent>0){                
                var total_payment = Math.round(total_remaining_convert * percent/100)
                jQuery(Ermis.total_payment).val(total_payment).trigger("change");
            }            
        })
    }
    
    var initChangeTotalPayment = function(){
        jQuery(Ermis.total_payment).on("change",function(){
           var total_remaining = jQuery("#total_remaining").html();
           var total_remaining_convert = parseInt(FormatNumberHtml(total_remaining,Ermis.decimal_symbol));
           var total_payment_value = parseInt(FormatNumberHtml(jQuery(this).val(),Ermis.decimal_symbol));
           var grid = $kGrid.data("kendoGrid");
           var dataItem = grid.dataSource.data();
           var total = 0;
           if(total_payment_value > total_remaining_convert){
            jQuery(this).val(total_remaining_convert);
            kendo.alert(Lang.get('acc_voucher'+Ermis.total_payment)+" "+Lang.get('acc_voucher.exceed_the_amount_is')+" "+ total_remaining);                
           } 
            jQuery.each(dataItem, function(l, k) {   
                var remaining = total_payment_value - total; 
                var remaining_val = k['remaining'];
                if(remaining >= remaining_val){
                    onChecked(1,k);     
                    total += remaining_val;                                        
                }else{
                    k.set("checkbox", "");     
                    k.set("payment", remaining);    
                    total += remaining;             
                    if(remaining>0){
                        initDescription(1,k['invoice']);      
                    }else{
                        initDescription(2,k['invoice']);
                    } 
                }                 
            }); 
            var a = jQuery('.k-checkbox.' + grid_header_key+":checked").not('#header-chb-' + grid_header_key).length;
            if(a == dataItem.length && dataItem.length > 0){
                jQuery('#header-chb-' + grid_header_key)[0].checked = true; 
            }else{
                jQuery('#header-chb-' + grid_header_key)[0].checked = false; 
            }                   
        })
    }

    var initFilterForm = function() {
        $kWindow.open();
    };
    var initVoucherForm = function() {
        $kWindow3.open();
    };

    var initAttachForm = function() {
        $kWindow4.open();
    };

    var initGetDataForm = function() {
        $kWindow6.open();
    };

    var initWrite = function(e) {
        ErmisTemplateEvent0(e, Ermis.per.e,
            function() {
                var dataId = sessionStorage.dataId;
                var postdata = {
                    data: JSON.stringify(dataId)
                };
                ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-write',
                    function() {
                        initActive("1");
                    },
                    function(result) {
                        kendo.alert(result.message);
                    });
            },
            function() {
                kendo.alert(Lang.get('messages.you_not_permission_write'));
            });
    };

    var initUnWrite = function(e) {
        ErmisTemplateEvent0(e, Ermis.per.e,
            function() {
                var dataId = sessionStorage.dataId;
                var postdata = {
                    data: JSON.stringify(dataId)
                };
                ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-unwrite',
                    function() {
                        initActive("0");
                    },
                    function(result) {
                        kendo.alert(result.message);
                    });
            },
            function() {
                kendo.alert(Lang.get('messages.you_not_permission_unwrite'));
            });
    };

    var initSave = function(e) {
        var obj = {};
        obj.detail = $kGrid.data("kendoGrid").dataSource.view();
        var crit1 = initValidationGrid(obj.detail,Ermis.field);
        var crit2 = initValidationGridColumnKey(obj.detail,Ermis.columns);
        var crit = crit1.concat(crit2);
        if(crit.length == 0){
          obj.type = jQuery('#tabstrip').find('.k-state-active').attr("data-search");
          //obj.total_number = ConvertNumber(jQuery('#quantity_total').html(),Ermis.decimal_symbol);
          obj.total_amount = ConvertNumber(jQuery('#total_payment').html(),Ermis.decimal_symbol);
          obj.total_amount_rate = ConvertNumber(jQuery('#payment_rate_total').html(),Ermis.decimal_symbol);
          ErmisTemplateAjaxPost11(e, "#attach", data.columns, Ermis.link + '-save', sessionStorage.dataId, obj, obj.detail.length > 0,
              function(result) {
                  sessionStorage.dataId = result.dataId;
                  sessionStorage.link = Ermis.link;
                  storedarrId.push(result.dataId);
                  sessionStorage[Ermis.type] = JSON.stringify(storedarrId);
                  initStatus(2);
                  initActive("1");
                  jQuery('.voucher').val(result.voucher_name);
                  initLoadGrid(result.data.detail);
                  initKendoGridChange();
                  // Thay đổi bảng tìm kiếm
                  var grid = $kGridVoucher.data("kendoGrid");
                  var dataItem = grid.dataSource.get(result.data.id);
                  if(dataItem != undefined){
                  initEditDefault(result.data,grid,Ermis.columns_voucher);
                  }
                  //sessionStorage.dataId = result.dataId;
              },
              function() {

              },
              function(result) {
                  kendo.alert(result.message);
              },
              function() {
                  kendo.alert(Lang.get('messages.please_fill_form_detail'));
              },
              function() {
                  kendo.alert(Lang.get('messages.please_fill_field'));
              });
        }else{
              var mes = initShowValidationGrid(obj.detail,crit,$kGrid);
              kendo.alert(mes.join("</br>"));
        }

    };

    var initAdd = function(e) {
        ErmisTemplateEvent0(e, Ermis.per.a,
            function() {
                initStatus(1);
                sessionStorage.removeItem('dataId');
            },
            function() {
                kendo.alert(Lang.get('messages.you_not_permission_add'));
            });
    };

    var initCopy = function(e) {
        ErmisTemplateEvent0(e, Ermis.per.a,
            function() {
                initStatus(8);
                sessionStorage.removeItem('dataId');
            },
            function() {
                kendo.alert(Lang.get('messages.you_not_permission_add'));
            });
    };

    var initDelete = function(e) {
        ErmisTemplateEvent0(e, Ermis.per.d,
            function() {
                $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('global.message'))).then(function(confirmed) {
                    if (confirmed) {
                        var dataId = sessionStorage.dataId;
                        var postdata = {
                            data: JSON.stringify(dataId)
                        };
                        ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-delete',
                            function(result) {
                                //var current = sessionStorage.current;
                                storedarrId = storedarrId.filter(function(e) {
                                    return e !== sessionStorage.dataId
                                    //return e != parseInt(sessionStorage.dataId) use id(int)
                                })
                                //storedarrId.sort();
                                sessionStorage[Ermis.type] = JSON.stringify(storedarrId);
                                //if (storedarrId.length > 0) {
                                //    storedarrId.length = storedarrId.length - 1;
                                //}
                                jQuery('#header-chb-' + grid_header_key)[0].checked = false; 
                                // Xóa dòng trong tìm chứng từ
                                var grid = $kGridVoucher.data("kendoGrid");
                                var dataItem = grid.dataSource.get(dataId);
                                if(dataItem != undefined){
                                var row = grid.tbody.find("tr[data-uid='" + dataItem.uid + "']");
                                grid.removeRow(row);  
                                }                                                       
                                if (storedarrId.length > 0) {
                                    index = index - 1;
                                    initClickBackForward(index);
                                } else {
                                    sessionStorage.removeItem('dataId');
                                    initStatus(4);
                                }
                            },
                            function(result) {
                                kendo.alert(result.message);
                            });
                    }
                });
            },
            function() {
                kendo.alert(Lang.get('messages.you_not_permission_delete'));
            });
    };

    var initEdit = function(e) {
        ErmisTemplateEvent0(e, Ermis.per.e,
            function() {
                initStatus(3);
            },
            function() {
                kendo.alert(Lang.get('messages.you_not_permission_edit'));
            });
    };

    var initCancel = function(e) {
        ErmisTemplateEvent1(e, function() {
            $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('global.message'))).then(function(confirmed) {
                if (confirmed) {
                    if (sessionStorage.dataId) {
                        var dataId = sessionStorage.dataId;
                        initLoadData(dataId);
                        initStatus(5);
                    } else {
                        initStatus(7);
                    }
                }
            });
        });
    };
    var initPrint = function(e) {
        var obj = {};
        obj.id = sessionStorage.dataId;
        obj.voucher = jQuery(this).attr('data-id');
        ErmisTemplateAjaxPost0(e, obj, Ermis.link + '-print', function(result) {
            if (result.print_content) {
                  var decoded = $("<div/>").html(result.print_content).text();                    
                    PrintForm(jQuery('#print'), decoded);
                    jQuery('#print').html("");
                }
        }, function(result) {
            kendo.alert(result.message);
        })

    };

    var initGetStoredArrId = function() {
        if (sessionStorage[Ermis.type]) {
            storedarrId = JSON.parse(sessionStorage[Ermis.type]);
            return storedarrId;
        }
    };

    var initCheckIndex = function(){
          if(storedarrId.indexOf(sessionStorage.dataId)){
                index = storedarrId.indexOf(sessionStorage.dataId);
            }
            return index;    
    }

    var initClickBackForward = function(index){        
        var a_index = Math.abs(index) % storedarrId.length;
        var dataId = getAtIndex(a_index, storedarrId);
        sessionStorage.dataId = dataId;
        initLoadData(dataId);
    }   

    var initBack = function(e) {
        ErmisTemplateEvent1(e, function() {      
          index = index - 1;
          initClickBackForward(index);
        })
    };

    var initForward = function(e) {
        ErmisTemplateEvent1(e, function() {          
            index = index + 1;
            initClickBackForward(index);
        })
    };

    
    var initDeleteRowAll = function(e) {
      var grid = $kGridTab.data("kendoGrid");
        $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('global.message'))).then(function(confirmed) {
            if (confirmed) {
                grid.cancelChanges(); // CLOSE ALL
            }
        });
    }


    var initChoose = function(e) {
        ErmisTemplateEvent1(e, function() {
            if ($kGridSubject.find('tr.k-state-selected').length > 0) {
                var grid = $kGridSubject.data("kendoGrid");
                var dataItem = grid.dataItem($kGridSubject.find('tr.k-state-selected'));
                $kWindow.close();
                AddChooseObjectResult1(dataItem);
            } else {
                kendo.alert(Lang.get('messages.please_select_line_choose'));
            }
        });
    };


    var initClose = function(e) {
        ErmisTemplateEvent1(e, function() {
            if ($kWindow.element.is(":hidden") === false) {
                $kWindow.close();
            } else if ($kWindow3.element.is(":hidden") === false) {
                $kWindow3.close();
            } else if ($kWindow6.element.is(":hidden") === false) {
                $kWindow6.close();
            }else{

            }
        });
    };

    var initLoadColumn = function(data, dataItem) {
        jQuery.each($kGridTab_column, function(i, v) {          
            if (v.set === "1") {
                data[v.field] = dataItem[v.field] ? dataItem[v.field] : 0;
            } else if (v.set === "2" || v.set === "5") {         
                if(v.url){
                    dataTextField = "text";
                    dataValueField = "value"; 
                }else{
                    dataTextField = "code";
                    dataValueField = "id"; 
                }
                data_check =  data[v.field] == null ? data[v.field] :  data[v.field][dataValueField];
                if (dataItem[v.field] && dataItem[v.field] != data_check) {                                      
                    if(v.url && a[v.field] == undefined){
                        var sytax =  v.url.includes("?") ? "&" : "?"; 
                         RequestURLcallback(v.url+sytax+"value="+dataItem[v.field],function(rs){
                            initLoadDropdownGrid(data,v.field,dataValueField,dataTextField,rs);  
                            // Bắt buộc refresh lại grid mới hiển thị dữ liệu
                             $kGridTab.data("kendoGrid").refresh();                          
                        });                                          
                    }else{
                        var f = findObjectByKey(a[v.field],dataValueField,dataItem[v.field]);    
                        initLoadDropdownGrid(data,v.field,dataValueField,dataTextField,f);                      
                    }                                  
                }
            } else if (v.set === "3") {
                data[v.field] = dataItem[v.field] ? FormatNumber(parseInt(dataItem[v.field])) : 0;
            } else if (v.set === "4") {
                data[v.field] = 1;
            } else if (v.set === "6") {
                data[v.field] = dataItem[v.field];
            }else{
                
            }
           
        }); 
              
    }

    var initLoadGroupColumn = function(data, dataItem,group) {
        jQuery.each($kGridTab_column, function(i, v) {
            if (v.group === group) {
              if(dataItem == undefined){
                  data[v.field] = '';
              }else{
                  data[v.field] = dataItem[v.field];
              }
            }         
        });   
    }

    OnDataBoundDropDownEditor = function(e){
        var b = jQuery(e.sender.element).prop("id");
        a[b] = e.sender.dataSource.data();
    }

    Onchange = function(e) {
        var dataItem = this.dataItem(e.item);
       // var field = e.sender.element.prop("id");
        var row = e.sender.element.closest("tr").index();
        //var col = e.sender.element.closest("td");        
        var grid = $kGridTab.data("kendoGrid");
        var data = grid.dataSource.data()[row];
        initLoadColumn(data, dataItem );
        initFixScrollGrid();
    };

    OnchangeCancel = function(e) {        
        var dataItem = this.dataItem(e.item);
        //var field = e.sender.element.prop("id");
        var row = e.sender.element.closest("tr").index();
        //var col = e.sender.element.closest("td");        
        if (dataItem == undefined) {
            $kGridTab.data("kendoGrid").refresh();
        } else {
            var grid = $kGridTab.data("kendoGrid");
            var data = grid.dataSource.data()[row];      
            initLoadColumn(data, dataItem);
            initFixScrollGrid();
        }
   
    };

    OnchangeItem = function(e){
         var select = this.dataItem(e.item);            
         initFixScrollGrid();
         if (select == undefined) {
         $kGridTab.data("kendoGrid").refresh();
       };
    };

    var initBackTo = function (e) {
        jQuery(".back_to").on("click", function () {
            window.history.go(-1);
        })
    };

    OnchangeGroup = function(e) {
        var dataItem = this.dataItem(e.item);
        var row = e.sender.element.closest("tr").index();     
        var grid = $kGridTab.data("kendoGrid");
        var a = grid.columns
        var columnTitle = e.sender.element.attr("id");
        var searchResultArray = findObjectByKey(a ,'field', columnTitle);
        var data = grid.dataSource.data()[row];
        initLoadGroupColumn(data, dataItem ,searchResultArray['group']);
        initFixScrollGrid();
    };



    return {

        init: function() {
            initGetShortKey();
            initGetColunm();
            initVoucherMasker();
            initCheckSession();
            initTabsTrip();
            initGlobalRegister();
            initStatus(status);
            initCheckIndex();
            initBackTo();
            initChangeAuto();
            initKendoGridSubject();
            initSearchGridSubject();
            initBindData();
            initGetStoredArrId();
            initChangeCurrency();
            initKendoGridVoucher();
            initSearchGridVoucher();
            initGetDataGrid();
            initChangePercentPayment();
            initChangeTotalPayment();
        }

    };

}();


jQuery(document).ready(function() {
    Ermis.init();
});
