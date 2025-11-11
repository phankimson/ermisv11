var Ermis = function() {

    var initGetShortKey = function() {
        return key = Ermis.short_key;
    };

    var initGlobalRegister = function(){
        // MonthPickerTemplate
        ErmisKendoMonthPickerTemplate(".month-picker","year","year","MM/yyyy");
        //StartEndDroplistTemplate
        ErmisKendoStartEndDroplistTemplate("#start","#end","dd/MM/yyyy","#fast_date","contains");
        ErmisKendoStartEndDroplistTemplate("#start_a","#end_a","dd/MM/yyyy","#fast_date_a","contains");
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
        $kWindow2 = ErmisKendoWindowTemplate(myWindow2, "1000px", "");
        $kWindow3 = ErmisKendoWindowTemplate(myWindow3, "1000px", "");
        $kWindow4 = ErmisKendoWindowTemplate(myWindow4, "400px", "");
        $kWindow5 = ErmisKendoWindowTemplate(myWindow5, "800px", "");
        $kWindow7 = ErmisKendoWindowTemplate(myWindow7, "500px", "");
        $kWindow2.title(Lang.get('acc_voucher.reference'));
        $kWindow3.title(Lang.get('acc_voucher.search_for_voucher'));
        $kWindow4.title(Lang.get('acc_voucher.attach'));
        $kWindow5.title(Lang.get('acc_voucher.change_voucher'));
        $kWindow7.title(Lang.get('acc_voucher.check_subject'));    
        // Grid Vat
        ErmisKendoGridTemplate3($kGridVat, Ermis.data, Ermis.aggregate, Ermis.field_tax, Ermis.page_size , true, jQuery(window).height() * 0.5, Ermis.column_grid,onSaveVat);
        initKendoGridVatChange();
        // Grid
        ErmisKendoGridTemplate3($kGrid, Ermis.data, Ermis.aggregate, Ermis.field, Ermis.page_size , {
            confirmation: false
        }, jQuery(window).height() * 0.5, Ermis.columns,onSave);
        initKendoGridChange();
    }

    var onSave = function(data){  
        //var grid = this;
           // setTimeout(function() {
               // grid.refresh();
           // });        
    }

    var onSaveVat = function(data){        
            if (data.values.amount) {
                var test = data.model.set("tax", data.values.amount * data.model.vat_tax/100);
       }
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
                        initLoadGrid(result.data);
                        sessionStorage.dataId = result.data.id;
                        initKendoGridVatChange();
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
          var grid_vat = $kGridVat.data("kendoGrid");
          ds = new kendo.data.DataSource({
              data: dataLoad.detail,
              schema: {
                  model: {
                      fields: Ermis.field,
                      id: "id"
                  }
              },
              aggregate: Ermis.aggregate
          });
          grid.setDataSource(ds);
          dataSource = new kendo.data.DataSource({
              data: dataLoad.tax,
              schema: {
                  model: {
                      fields: Ermis.field_tax,
                      id: "id"
                  }
              },
              aggregate: Ermis.aggregate
          });
          grid_vat.setDataSource(dataSource);
    }

        var initBindData = function() {
        if(sessionStorage.data){
             var arr = JSON.parse(sessionStorage.data);      
             setTimeout(() => {
                SetDataAjax(data.columns, arr);
                initLoadGrid(arr);
                initStatus(8);  
                }, 2000);       
                       
        }else if (sessionStorage.dataId) {
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

    var initChooseVoucher = function(e) {
        ErmisTemplateEvent1(e, function() {
            if ($kGridVoucher.find('tr.k-state-selected').length > 0) {
                var grid = $kGridVoucher.data("kendoGrid");
                var dataItem = grid.dataItem($kGridVoucher.find('tr.k-state-selected'));
                $kWindow3.close();
                initLoadData(dataItem.id);
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

    var initVoucherChange = function() {
        jQuery('#voucher-change').on('click', function(e) {
            var filter = GetAllDataForm('#form-window-voucher-change', 2);
            var c = GetDataAjax(filter.columns);
            c.obj.accounting_date =formatDateDefault(jQuery("input[name='accounting_date']").val());
            var postdata = {
                data: JSON.stringify(c.obj)
            };
            ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-voucher-change', function(result) {
                var voucher = initVoucherMasker();
                jQuery(".voucher").val(voucher);
                $kWindow5.close();
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
      } else {
          $kGridTab = $kGridVat;
          $kGridTab_column = Ermis.column_grid;
      };
    }

    var initScanBarcode = function(e) {
        var obj = {};
        var $this = e.currentTarget ? e.currentTarget : e
        obj.value = jQuery($this).val();
        if (obj.value) {
            obj.id = sessionStorage.dataId;
            var postdata = {
                data: JSON.stringify(obj)
            };
            ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-scan', function(result) {
                var i = result.data;
                var grid = $kGrid.data("kendoGrid");
                var dataItem = grid.dataSource.get(i.id);
                if (dataItem) {
                    var row = grid.tbody.find("tr[data-uid='" + dataItem.uid + "']");
                    var selectedItem = grid.dataItem(row);
                    selectedItem.set("quantity", dataItem.quantity + 1);
                } else {
                    i.quantity = 1;
                    grid.dataSource.insert(0, i);
                }

                setTimeout(function() {
                    jQuery($this).val("");
                    jQuery($this).focus();
                }, 1);
            }, function(result) {
                kendo.alert(result.message);
            });

        }
    }

    var initKendoGridVatChange = function() {
        var gridVat = $kGridVat.data("kendoGrid");
        gridVat.dataSource.bind("change", function(e) {
          var item = e.items[0];         
            // checks to see if the action is a change and the column being changed is what is expected
            if (e.action === "itemchange" && (e.field === "amount" || e.field === "vat_type")) {
                // here you can access model items using e.items[0].modelName;
                var a = ((item.vat_tax * item.amount)/100).toFixed(Ermis.decimal);
                item.set("tax", a);
                item.set("tax_amount", item.amount + item.tax );
            }else if(e.action === "itemchange" && e.field === "tax_amount" ){
                var b = (item.tax_amount/(1+item.vat_tax/100)).toFixed(Ermis.decimal);
                item.set("amount", b);
                item.set("tax_amount_rate", item.tax_amount * item.tax_rate );
            }else if(e.action === "itemchange" && (e.field === "tax_amount_rate" || e.field === "tax_rate") ){
                item.set("tax_amount_rate", item.tax_amount * item.tax_rate );
            }else{

            }
            // finally, refresh the grid to show the changes
            // Không bỏ refresh được
            if(e.action === "itemchange" && (e.field === "amount" || e.field === "vat_type" || e.field === "tax_amount_rate" || e.field === "tax" || e.field === "tax_rate" ||  e.field === "tax_amount" )){
                    gridVat.refresh();
            }            
        });

        jQuery("input[name='description']").on("change", function(e) {
            AddChangeDescriptionResult(jQuery(e.target).val());
        });
    }    

    
    var initKendoGridChange = function() {
        var grid = $kGrid.data("kendoGrid");
        grid.dataSource.bind("change", function(e) {
            // checks to see if the action is a change and the column being changed is what is expected
            var item = e.items[0];
            if (e.action === "itemchange" && (e.field === "amount" || e.field === "rate")) {
                // here you can access model items using e.items[0].modelName;
                item.rate == 0 ? item.set("amount_rate",0) : item.set("amount_rate",item.amount * item.rate);
            }else if(e.action === "itemchange" && e.field === "amount_rate" ){
                item.set("amount_rate",item.amount * item.rate);
            }else if(e.action === "itemchange" && e.field.substring(0,7) === "subject" ){
                AddChooseObjectResult2(item,e.field.substring(0,7),e.field);
            }else{

            } 
              if(e.action === "itemchange" && (e.field === "amount_rate" || e.field === "rate" || e.field === "amount" )){
                    grid.refresh();
            }               
        });
         // finally, refresh the grid to show the changes
         //grid.refresh();
    }

    var initKendoGridReference = function() {
        var array = [];
        ErmisKendoGridCheckboxTemplate($kGridReference, [] , jQuery(window).height() * 0.5,Ermis.page_size_1, "multiple, row", "reference","id", Ermis.columns_reference, function(checkedIds) {
            reference_by = [];
            array = $.map(checkedIds, function(value, index) {
                reference_by[index] = value.id;
                return [value.voucher];
            });
            jQuery("input[name='reference']").val(array);
            $kWindow2.close();
        });

    };



    var initKendoUiContextMenuGrid = function() {
        jQuery("#context-menu-grid").kendoContextMenu({
            target: "#grid,#grid_vat",
            select: function(e) {
                var $this = e;
                var grid = $kGridTab.data("kendoGrid");
                var row = $kGridTab.find('tr.k-state-selected');
                var dataItem = $kGridTab.data("kendoGrid").dataSource.data()[0];
                if (type == 0) {
                    var dataGrid = dataDefaultGrid.data;
                } else {
                    var dataGrid = dataDefaultGrid.vat;
                };
                if (jQuery($this.item).children().hasClass('remove_row')) {
                    $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('global.message'))).then(function(confirmed) {
                        if (confirmed) {
                            grid.removeRow(row);
                        }
                    });
                } else if (jQuery($this.item).children().hasClass('copy_row')) {
                    if (dataItem) {
                        grid.dataSource.add(dataItem.toJSON());
                    } else {
                        kendo.alert(Lang.get('messages.no_row'));
                    }
                } else if (jQuery($this.item).children().hasClass('new_row')) {
                  if(dataGrid.hasOwnProperty("description") || dataGrid.hasOwnProperty("subject_code_debit") || dataGrid.hasOwnProperty("subject_code_credit")){
                     grid.dataSource.add(dataGrid);
                  }else{
                     grid.addRow();
                  }
                } else if (jQuery($this.item).children().hasClass('close_row')) {
                    grid.cancelRow();
                } else if (jQuery($this.item).children().hasClass('remove_all_row')) {
                    $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('global.message'))).then(function(confirmed) {
                        if (confirmed) {
                            grid.cancelChanges(); // CLOSE ALL
                        }
                    });
                }else{

                }
            }
        });
        $kGrid.on("mousedown", "tr[role='row']", function(e) {
            if (e.which === 3) {
                $kGrid.find(" tbody tr").removeClass("k-state-selected");
                jQuery(this).addClass("k-state-selected");
            }
        });
        $kGridVat.on("mousedown", "tr[role='row']", function(e) {
            if (e.which === 3) {
                $kGridVat.find(" tbody tr").removeClass("k-state-selected");
                jQuery(this).addClass("k-state-selected");
            }
        });
    };


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
        jQuery('.add,.copy,.edit,.delete,.back,.forward,.print,.import,.cancel,.save,.choose,.filter,.pageview,.reference,.write_item,.unwrite_item,.advance_teacher,.advance_employee,.open_subject').not('.back_to').addClass('disabled');
        jQuery('.add,.copy,.edit,.delete,.back,.forward,.print-item,.cancel,.save,.choose,.pageview,.filter,.reference,.write_item,.unwrite_item,.advance_teacher,.advance_employee,.open_subject').not('.back_to').off('click');
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
        $kGridVat.addClass('disabled');
        dataDefaultGrid.data = initGetDefaultKeyArray(Ermis.field);
        dataDefaultGrid.vat = initGetDefaultKeyArray(Ermis.field_tax);
        if (flag === 1) { //ADD
            sessionStorage.removeItem("dataId");
            sessionStorage.removeItem("data");
            sessionStorage.removeItem("compare");
            jQuery('.cancel,.save,.choose,.cancel-window,.filter,.reference,.import,.advance_teacher,.advance_employee,.open_subject').removeClass('disabled');
            jQuery('.cancel').on('click', initCancel);
            jQuery('.save').on('click', initSave);
            jQuery('.cancel-window').on('click', initClose);
            jQuery('.reference').on('click', initReferenceForm);
            jQuery('.attach').on('click', initAttachForm);
            jQuery('.open_subject').on('click', initCheckForm);
            jQuery('.voucher-change').on('click', initVoucherChangeForm);
            jQuery('.import').on('click', initImport);
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
            $kGrid.data('kendoGrid').dataSource.data([]);
            $kGrid.removeClass('disabled');
            $kGridVat.data('kendoGrid').dataSource.data([]);
            $kGridVat.removeClass('disabled');
            $kGridReference.data('kendoGrid').dataSource.data([]);
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
            $kGridVat.addClass('disabled');
            sessionStorage.removeItem("compare");
            shortcut.remove(key + "T");
        } else if (flag === 3) { //EDIT
            jQuery('.cancel,.save,.filter,.reference,.advance_teacher,.advance_employee,.open_subject').removeClass('disabled');
            jQuery('.cancel').on('click', initCancel);
            jQuery('.save').on('click', initSave);
            jQuery('.reference').on('click', initReferenceForm);
            jQuery('.attach').on('click', initAttachForm);
            jQuery('.open_subject').on('click', initCheckForm);
            jQuery('.voucher-change').on('click', initVoucherChangeForm);
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
            $kGridVat.removeClass('disabled');
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
            $kGridVat.data('kendoGrid').dataSource.data([]);
            $kGridVat.addClass('disabled');
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
            shortcut.add(key + "V", function(e) {
                initChooseVoucher(e);
            });
            jQuery('.add').on('click', initAdd);
            jQuery('input').not('[type=radio]').not(".date-picker,.start,.end,.month-picker,.voucher,.fast_date,.rate,.no_clear").val("");
            jQuery(".date-picker,.date-value").val(kendo.toString(kendo.parseDate(new Date()), 'dd/MM/yyyy'));
            jQuery(".voucher").val(voucher);
            $kGrid.data('kendoGrid').dataSource.data([]);
            $kGridVat.data('kendoGrid').dataSource.data([]);
        }else if (flag === 8) { // Copy
          sessionStorage.removeItem("dataId");
          if(sessionStorage.data){
            sessionStorage.removeItem("data");
            jQuery(".end,.start").val(kendo.toString(kendo.parseDate(new Date()), 'dd/MM/yyyy'));
          }else{
            jQuery(".date-picker,.end,.start").val(kendo.toString(kendo.parseDate(new Date()), 'dd/MM/yyyy'));
          }
          
          jQuery('.cancel,.save,.filter,.reference,.advance_teacher,.advance_employee,.open_subject').removeClass('disabled');
          jQuery('.cancel').on('click', initCancel);
          jQuery('.save').on('click', initSave);
          jQuery('.reference').on('click', initReferenceForm);
          jQuery('.attach').on('click', initAttachForm);
          jQuery('.open_subject').on('click', initCheckForm);
          jQuery('.voucher-change').on('click', initVoucherChangeForm);
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
          $kGridVat.removeClass('disabled');
          shortcut.add(key + "T", function(e) {
              initDeleteRowAll(e);
          });            
            jQuery(".no_copy").val("");
            jQuery(".voucher").val(voucher);           
            jQuery(".no_copy_value").val(0);
            initDefaultIdGrid();
            reference_by =[];
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
        var grid_vat = $kGridVat.data("kendoGrid");
        var rv = grid_vat.dataSource.data();
        dataDefaultGrid.vat["id"] = ""; 
        jQuery.each(r, function(l, k) {
            // Không xài được k.set('id',"")
            k.id = "";
          });
        grid.refresh();   
        jQuery.each(rv, function(l, k) {
            // Không xài được k.set('id',"")
            k.id = "";
        });
        grid_vat.refresh();           
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
                    //Không xóa refresh
                    //grid.refresh();
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
            initLoadGrid(results.data[0]);   
            //AddChooseObjectResult(results.data['object']);
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

      var initCheckSubject = function() {
        jQuery('.check_subject').on('click', function(e) {
            var data = jQuery("#check_subject_tax_code").val().trim();
             var postdata = {
                data: JSON.stringify(data)
            };
            ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-check-subject', function(result) {
              jQuery.each(result.data, function(i, v) { 
                if(i == "subject_active"){
                    if(v == 1){
                        jQuery("."+i).find("a").removeClass("md-btn-danger").removeClass("md-btn-primary").addClass("md-btn-success");
                        jQuery("."+i).find("a").html(Lang.get('global.is_active'));
                    }else if(v == 2){
                        jQuery("."+i).find("a").removeClass("md-btn-danger").removeClass("md-btn-success").addClass("md-btn-primary");
                        jQuery("."+i).find("a").html(Lang.get('global.address_unknown'));
                    }else{
                        jQuery("."+i).find("a").removeClass("md-btn-success").removeClass("md-btn-primary").addClass("md-btn-danger");
                        jQuery("."+i).find("a").html(Lang.get('global.none_active'));
                    }
                }else{
                    jQuery("."+i).find("span").html(v);
                }              
               });  
             jQuery(".load_check_subject").removeClass('hidden');
            }, function(result) {               
                kendo.alert(result.message);
            });   
        });
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
                  k.set("rate",value)
                  k.set("amount_rate",value*k["amount"]);
              });  
        }
        $currency.bind("change", OnChangeCurrency);
        $rate.on("change", OnChangeRate);
    }   

    var initReferenceForm = function() {
        $kWindow2.open();
    };
    var initVoucherForm = function() {
        $kWindow3.open();
    };
    var initAttachForm = function() {
        $kWindow4.open();
    };
     var initCheckForm = function() {
        $kWindow7.open();
    };
    var initVoucherChangeForm = function() {
        if(Ermis.voucher.change_voucher == 1){
            initLoadVoucherChange();
        }
        $kWindow5.open();
    };


    var initGetDataReference = function() {
        jQuery('#get_data').on('click', function(e) {
            var filter = GetAllDataForm('#form-window-reference', 2);
            var c = GetDataAjax(filter.columns, filter.elem);
                c.obj.general_id = sessionStorage.dataId?sessionStorage.dataId:0;
            var postdata = {
                data: JSON.stringify(c.obj)
            };
            ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-reference', function(result) {
                ErmisKendoGridAddData($kGridReference,result.data,Ermis.page_size_1,Ermis.field_reference);
                initLoadCheckboxGrid(result.data,'reference','reference_by');
            }, function(result) {
                kendo.alert(result.message);
            });
        });
    };

    var initLoadVoucherChange = function(e){    
        var obj = {};
        obj.id = Ermis.voucher.id;
        obj.accounting_date = formatDateDefault(jQuery("input[name='accounting_date']").val());
            var postdata = {
                data: JSON.stringify(obj)
            };
            ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-load-voucher-change', function(result) {
               jQuery("input[name='length_number']").val(result.data.length_number);
               jQuery("input[name='number']").val(result.data.number);
            }, function(result) {
               jQuery("input[name='length_number']").val(0);
               jQuery("input[name='number']").val(0);
                kendo.alert(result.message);
            });       
    }

    var initLoadCheckboxGrid = function (data,key,column_check){
      jQuery.each(data, function(k, m) {
        $item = jQuery('.k-checkbox.' + key+'#'+m.id);
            if(m[column_check] != 0){
              if (!$($item).is(':checked')) {
                $item.click();
                reference_by[k] = m.id;
              }
            }else{
              if ($($item).is(':checked')) {
                  $($item).click();
              }
            }
      });
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
        obj.compare = sessionStorage.compare;
        obj.detail = $kGrid.data("kendoGrid").dataSource.view();
        obj.tax = $kGridVat.data("kendoGrid").dataSource.data();
        obj.reference_by = reference_by;
        var crit1 = initValidationGrid(obj.detail,Ermis.field);
        var crit2 = initValidationGridColumnKey(obj.detail,Ermis.columns);
        var crit3 = initValidationGrid(obj.tax,Ermis.field_tax);
        var crit4 = initValidationGridColumnKey(obj.tax,Ermis.column_grid);
        var crit = crit1.concat(crit2);
        var crit_tax = crit3.concat(crit4);
        if(crit.length == 0 && crit_tax.length == 0){
          obj.type = jQuery('#tabstrip').find('.k-state-active').attr("data-search");
          obj.total_number = ConvertNumber(jQuery('#quantity_total').html(),Ermis.decimal_symbol);
          obj.total_amount = ConvertNumber(jQuery('#amount_total').html(),Ermis.decimal_symbol);
          obj.total_amount_rate = ConvertNumber(jQuery('#amount_rate_total').html(),Ermis.decimal_symbol);
          var tax_amount = ConvertNumber(jQuery('#total_amount').html(),Ermis.decimal_symbol);
          var tax_amount_rate = ConvertNumber(jQuery('#total_amount_rate').html(),Ermis.decimal_symbol);
          var message_confirmed = '';
          if(tax_amount>0 && tax_amount_rate> 0 && tax_amount != obj.total_amount && tax_amount_rate != obj.total_amount_rate){
              message_confirmed = Lang.get('messages.amount_diffirent_are_you_sure');  
          }else{
              message_confirmed = Lang.get('messages.are_you_sure');  
          }    
              initSaveDetail(e,obj,message_confirmed);     
        }else{        
             var mes1 = initShowValidationGrid(obj.detail,crit,$kGrid);        
             var mes2 = initShowValidationGrid(obj.tax,crit_tax,$kGridVat);    
             kendo.alert(mes1.join("</br>")+mes2.join("</br>"));
        }

    };

    var initSaveDetail = function(e,obj,message_confirmed){
         ErmisTemplateAjaxPost12(e, "#attach", data.columns, Ermis.link + '-save', sessionStorage.dataId, obj, obj.detail.length > 0,
              function(result) {
                  sessionStorage.dataId = result.dataId;
                  sessionStorage.link = Ermis.link;
                  storedarrId.push(result.dataId);
                  sessionStorage[Ermis.type] = JSON.stringify(storedarrId);
                  initStatus(2);
                  initActive("1");
                  jQuery('.voucher').val(result.voucher_name);
                  initLoadGrid(result.data);
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
              },
            message_confirmed);
    }

    var initAdd = function(e) {
        ErmisTemplateEvent0(e, Ermis.per.a,
            function() {
                initStatus(1);
                //sessionStorage.removeItem('dataId');
            },
            function() {
                kendo.alert(Lang.get('messages.you_not_permission_add'));
            });
    };

    var initCopy = function(e) {
        ErmisTemplateEvent0(e, Ermis.per.a,
            function() {
                initStatus(8);
                //sessionStorage.removeItem('dataId');
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
                                 // Xóa dòng trong tìm chứng từ
                                var grid = $kGridVoucher.data("kendoGrid");
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
         var postdata = { data: JSON.stringify(obj) };
        ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-print', function(result) {
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

    var initClick = function(e) {
        jQuery("#page_content_inner").not("#grid").not('#grid_vat').click(function(e) {
            $kGridTab.find(" tbody tr").removeClass("k-state-selected");
            if (jQuery(e.target).closest('#grid').length || jQuery(e.target).closest('#grid_vat').length) {
                return false;
            } else if (jQuery('.k-grid-edit-row').length > 0) {
                //$kGrid.data("kendoGrid").cancelChanges(); // CLOSE ALL
                //$kGrid.data("kendoGrid").closeCell();
                $kGridTab.data("kendoGrid").cancelRow();
            }else{

            }
        });
    };

    var initKeyCode = function() {
        jQuery(document).keyup(function(e) {
          var grid = $kGridTab.data("kendoGrid");
          var row = $kGridTab.find('tr.k-state-selected');
          var dataItem = $kGridTab.data("kendoGrid").dataSource.data()[0];
          if (type == 0) {
              var dataGrid = dataDefaultGrid.data;
          } else {
              var dataGrid = dataDefaultGrid.vat;
          };
            $kGridTab.find(" tbody tr").removeClass("k-state-selected");
            if (e.keyCode === 13) {
                if (e.target.id == "barcode") {
                    initScanBarcode(e.target);
                } else {
                  if(dataGrid.hasOwnProperty("description") || dataGrid.hasOwnProperty("subject_code_debit") || dataGrid.hasOwnProperty("subject_code_credit")){
                     grid.dataSource.add(dataGrid);
                  }else{
                     grid.addRow();
                  }
                }
            } else if (e.keyCode === 45) {
                var dataItem = grid.dataSource.data()[0];
                if (dataItem) {
                    grid.dataSource.add(dataItem.toJSON());
                } else {
                    kendo.alert(Lang.get('messages.no_row'));
                }
            } else if (e.keyCode === 27) {
                grid.cancelChanges();
            } else if (e.keyCode === 46) {
                if (dataItem) {
                    var row = grid.tbody.find("tr[data-uid='" + dataItem + "']");
                    grid.removeRow(row);
                } else {
                    kendo.alert(Lang.get('messages.no_row'));
                }
            }else{

            }
        });
    };

    var initDeleteRowAll = function(e) {
      var grid = $kGridTab.data("kendoGrid");
        $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('global.message'))).then(function(confirmed) {
            if (confirmed) {
                grid.cancelChanges(); // CLOSE ALL
            }
        });
    }



    var initClose = function(e) {
        ErmisTemplateEvent1(e, function() {
            if ($kWindow2.element.is(":hidden") === false) {
                $kWindow2.close();
            } else if ($kWindow3.element.is(":hidden") === false) {
                $kWindow3.close();
            } else if ($kWindow4.element.is(":hidden") === false) {
                $kWindow4.close();
            } else if ($kWindow5.element.is(":hidden") === false) {
                $kWindow5.close();
            }else if ($kWindow7.element.is(":hidden") === false) {
                $kWindow7.close();
            }else{

            }
        });
    };

    var initLoadColumn = function(data, dataItem) {
        jQuery.each($kGridTab_column, function(i, v) {          
            if (v.set === "1") {
                var value = dataItem[v.field] ? dataItem[v.field] : 0;
                data.set(v.field,value);
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
                var value = dataItem[v.field] ? FormatNumber(parseInt(dataItem[v.field])) : 0
                data.set(v.field,value);                
            } else if (v.set === "4") {
                data.set(v.field,1);
            } else if (v.set === "6") {
                data.set(v.field,dataItem[v.field]);                
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
        //var field = e.sender.element.prop("id");
        var row = e.sender.element.closest("tr").index();
        //var col = e.sender.element.closest("td");        
        var grid = $kGridTab.data("kendoGrid");
        var data = grid.dataSource.data()[row];
        initLoadColumn(data, dataItem);
        initFixScrollGrid();
    };

    OnchangeCancel = function(e) {        
        var dataItem = this.dataItem(e.item);
        //var field = e.sender.element.prop("id");
        var row = e.sender.element.closest("tr").index();
        //var col = e.sender.element.closest("td");        
        if (dataItem == undefined) {
            // TEST
            //$kGridTab.data("kendoGrid").refresh();
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
            // TEST
         //$kGridTab.data("kendoGrid").refresh();
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
            //initClick();
            initCheckIndex();
            initBackTo();
            initKeyCode();
            initChangeAuto();
            initKendoUiContextMenuGrid();
            initKendoGridReference();
            initKendoGridVoucher();
            initSearchGridVoucher();
            initGetDataReference();
            initBindData();
            initGetStoredArrId();
            initChangeCurrency();
            initVoucherChange();
            initCheckSubject();
        }

    };

}();


jQuery(document).ready(function() {
    Ermis.init();
});
