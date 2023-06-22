var Ermis = function() {

    var initGetShortKey = function() {
        return key = Ermis.short_key;
    };

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
            function(result) {
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
        if (sessionStorage.dataId) {
            var dataId = sessionStorage.dataId;
            initLoadData(dataId);
        } else {
            initStatus(1);
        }
    };


    var initGetColunm = function() {
        data = GetAllDataForm('#form-action', 2);
        return data;
    };

    var initVoucherMasker = function() {
        return voucher = initErmisBarcodeMaskerHide(Ermis.voucher);
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
            var postdata = {
                data: JSON.stringify(c.obj)
            };
            ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-find', function(result) {
                var grid = $kGridVoucher.data("kendoGrid");
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

    var initVoucherChange = function() {
        jQuery('#voucher-change').on('click', function(e) {
            var filter = GetAllDataForm('#form-window-voucher-change', 2);
            var c = GetDataAjax(filter.columns);
            var postdata = {
                data: JSON.stringify(c.obj)
            };
            ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-voucher-change', function(result) {
                var voucher = initErmisBarcodeMaskerHide(c.obj);
                jQuery(".voucher").val(voucher);
                $kWindow5.close();
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

    var initMonthDate = function() {
        ErmisKendoMonthPickerTemplate(".month-picker", "year", "year", "MM/yyyy");
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

    var initKendoGridVat = function() {
        ErmisKendoGridTemplate3($kGridVat, Ermis.data, Ermis.aggregate, Ermis.field_tax, Ermis.page_size , true, jQuery(window).height() * 0.5, Ermis.column_grid);
        initKendoGridVatChange();
    };

    var initKendoGridVatChange = function() {
        var gridVat = $kGridVat.data("kendoGrid");
        gridVat.dataSource.bind("change", function(e) {
          var item = e.items[0];
            // checks to see if the action is a change and the column being changed is what is expected
            if (e.action === "itemchange" && (e.field === "amount" || e.field === "tax")) {
                // here you can access model items using e.items[0].modelName;
                item.total_amount = (item.amount * item.tax.code) / 100;
            }else if(e.action === "itemchange" && e.field === "total_amount" ){
              // here you can access model items using e.items[0].modelName;
              item.amount = (item.total_amount / item.tax.code) * 100;
            }
            // finally, refresh the grid to show the changes
            gridVat.refresh();
        });

        jQuery("input[name='description']").on("change", function(e) {
            AddChangeDescriptionResult(jQuery(e.target).val());
        });
    }

    var initKendoGridBarcode = function() {
        var grid = $kGridBarcode.kendoGrid({
            dataSource: {
                data: []
            },
            selectable: "multiple, row",
            height: jQuery(window).height() * 0.5,
            sortable: true,
            pageable: true,
            filterable: true,
            columns: Ermis.columns_barcode
        }).data("kendoGrid");
        grid.thead.kendoTooltip({
            filter: "th",
            content: function(e) {
                var target = e.target; // element for which the tooltip is shown
                return $(target).text();
            }
        });

        grid.table.on("click", ".k-checkbox", selectRow);
        //bind click event to the checkbox
        //grid.table.on("click", ".k-checkbox" , selectRow);
        jQuery('#header-chb-b').change(function(ev) {
            var checked = ev.target.checked;
            $kGridBarcode.find('.k-checkbox').not("#header-chb-b").each(function(idx, item) {
                  if (checked) {
                      if (!$(item).is(':checked')) {
                          $(item).click();
                      }
                  } else {
                      if ($(item).is(':checked')) {
                          $(item).click();
                      }
                  }
            });

        });

        jQuery(".choose_barcode").bind("click", function() {
            var checked = [];
            for (var i of checkedData) {
                var grid = $kGrid.data("kendoGrid");
                var dataItem = grid.dataSource.get(i.id);
                if (dataItem) {
                    var row = grid.tbody.find("tr[data-uid='" + dataItem.uid + "']");
                    var selectedItem = grid.dataItem(row);
                    selectedItem.set("quantity", dataItem.quantity + 1);
                } else {
                    i.quantity = 1;
                    grid.dataSource.insert(0, i);
                    $kGridBarcode.find('.k-checkbox[id="' + i.id + '"]').click();
                }
            }
            $kWindow1.close();
            checkedData = [];
        });
        var checkedData = [];

        //on click of the checkbox:
        function selectRow() {
            var checked = this.checked,
                row = $(this).closest("tr"),
                grid = $kGridBarcode.data("kendoGrid"),
                dataItem = grid.dataItem(row);
            if (checked) {
                checkedData.push(dataItem)
                //-select the row
                row.addClass("k-state-selected");
            } else {
                checkedData = checkedData.filter(x => x.id != dataItem.id)
                //-remove selection
                row.removeClass("k-state-selected");
            }
        }
        jQuery("#barcode").on("blur", initScanBarcode)

    };

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


    var initKendoGrid = function() {
        ErmisKendoGridTemplate3($kGrid, Ermis.data, Ermis.aggregate, Ermis.field, Ermis.page_size , {
            confirmation: false
        }, jQuery(window).height() * 0.5, Ermis.columns);
        initKendoGridChange();
    };

    var initKendoGridChange = function() {
        var grid = $kGrid.data("kendoGrid");
        grid.dataSource.bind("change", function(e) {
            // checks to see if the action is a change and the column being changed is what is expected
            var item = e.items[0];
            if (e.action === "itemchange" && (e.field === "amount" || e.field === "rate")) {
                // here you can access model items using e.items[0].modelName;
                item.rate == 0 ? (item.amount_rate = 0) : (item.amount_rate = item.amount / item.rate);
            }else if(e.action === "itemchange" && e.field === "amount_rate" ){
                // here you can access model items using e.items[0].modelName;
                item.amount = item.amount_rate * item.rate;
            }
            // finally, refresh the grid to show the changes
            grid.refresh();
        });
    }

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
                  if(dataGrid.hasOwnProperty("description") || dataGrid.hasOwnProperty("subject_code")){
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

    var initKendoUiContextMenu = function() {
        ErmisKendoContextMenuTemplate("#context-menu", "#form-action");
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
        jQuery('.add,.copy,.edit,.delete,.back,.forward,.print,.cancel,.save,.choose,.filter,.pageview,.reference,.write_item,.unwrite_item,.advance_teacher,.advance_employee').addClass('disabled');
        jQuery('.add,.copy,.edit,.delete,.back,.forward,.print-item,.cancel,.save,.choose,.pageview,.filter,.reference,.write_item,.unwrite_item,.advance_teacher,.advance_employee').off('click');
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
            jQuery('.cancel,.save,.choose,.cancel-window,.filter,.reference,.advance_teacher,.advance_employee').removeClass('disabled');
            jQuery('.cancel').on('click', initCancel);
            jQuery('.save').on('click', initSave);
            jQuery('.choose').on('click', initChoose);
            jQuery('.cancel-window').on('click', initClose);
            jQuery('.filter').on('click', initFilterForm);
            jQuery('.barcode').on('click', initBarcodeForm);
            jQuery('.reference').on('click', initReferenceForm);
            jQuery('.attach').on('click', initAttachForm);
            jQuery('.voucher-change').on('click', initVoucherChangeForm);
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
            jQuery('input').not('[type=radio]').not(".date-picker,.start,.end,.month-picker,.voucher,.fast_date,.rate,.no_clear").val("");
            jQuery(".date-picker,.end,.start").val(kendo.toString(kendo.parseDate(new Date()), 'dd/MM/yyyy'));
            jQuery(".no_copy_value").val(0);
            jQuery(".voucher").val(voucher);
            $kGrid.data('kendoGrid').dataSource.data([]);
            $kGrid.removeClass('disabled');
            $kGridVat.data('kendoGrid').dataSource.data([]);
            $kGridVat.removeClass('disabled');
            $kGridReference.data('kendoGrid').dataSource.data([]);
            $kGridBarcode.data('kendoGrid').dataSource.data([]);
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
            shortcut.remove(key + "T");
        } else if (flag === 3) { //EDIT
            jQuery('.cancel,.save,.filter,.reference,.advance_teacher,.advance_employee').removeClass('disabled');
            jQuery('.cancel').on('click', initCancel);
            jQuery('.save').on('click', initSave);
            jQuery('.filter').on('click', initFilterForm);
            jQuery('.barcode').on('click', initBarcodeForm);
            jQuery('.reference').on('click', initReferenceForm);
            jQuery('.attach').on('click', initAttachForm);
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
          jQuery('.cancel,.save,.filter,.reference,.advance_teacher,.advance_employee').removeClass('disabled');
          jQuery('.cancel').on('click', initCancel);
          jQuery('.save').on('click', initSave);
          jQuery('.filter').on('click', initFilterForm);
          jQuery('.barcode').on('click', initBarcodeForm);
          jQuery('.reference').on('click', initReferenceForm);
          jQuery('.attach').on('click', initAttachForm);
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
            jQuery(".date-picker,.end,.start").val(kendo.toString(kendo.parseDate(new Date()), 'dd/MM/yyyy'));
            jQuery(".voucher").val(voucher);
            jQuery(".no_copy").val("");
            jQuery(".no_copy_value").val(0);
            reference_by =[];
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

    var initKendoStartEndDatePicker = function() {
        ErmisKendoStartEndDroplistTemplate("#start", "#end", "dd/MM/yyyy", "#fast_date", "contains");
        ErmisKendoStartEndDroplistTemplate("#start_a", "#end_a", "dd/MM/yyyy", "#fast_date_a", "contains");
    };



    var initKendoDatePicker = function() {
        ErmisKendoDatePickerTemplate(".date-picker", "dd/MM/yyyy");
    };

    var initKendoUiDropList = function() {
        ErmisKendoDroplistTemplate(".droplist", "contains");
    };

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

    var initChangeCurrency = function() {
        function OnChangeCurrency(e) {
            var value = this.value;
            var postdata = {
                data: JSON.stringify(value)
            };
            ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-currency',
                function(result) {
                    jQuery('#form-action').find("input[name='rate']").val(result.data);
                },
                function() {

                });

        };

        function OnChangeRate(e) {
            var value = this.value;
            var dataItem = $kGrid.data("kendoGrid").dataSource.data();
            jQuery.each(dataItem, function(i, v) {
                v.set("rate", value);
            })
        }
        $currency.bind("change", OnChangeCurrency);
        $rate.on("change", OnChangeRate);
    }

    var initKendoUiNumber = function() {
        ErmisKendoNumbericTemplate(".number", "n" + Ermis.decimal, null, null, null, 1);
        ErmisKendoNumbericTemplate(".number-price", "n" + Ermis.decimal, null, null, null, 1000);
    };


    var initKendoUiDialog = function() {
        $kWindow = ErmisKendoWindowTemplate(myWindow, "600px", "");
        $kWindow1 = ErmisKendoWindowTemplate(myWindow1, "800px", "");
        $kWindow2 = ErmisKendoWindowTemplate(myWindow2, "1000px", "");
        $kWindow3 = ErmisKendoWindowTemplate(myWindow3, "1000px", "");
        $kWindow4 = ErmisKendoWindowTemplate(myWindow4, "400px", "");
        $kWindow5 = ErmisKendoWindowTemplate(myWindow5, "800px", "");
        $kWindow.title(Lang.get('acc_voucher.search_for_object'));
        $kWindow1.title(Lang.get('acc_voucher.search_for_goods'));
        $kWindow2.title(Lang.get('acc_voucher.reference'));
        $kWindow3.title(Lang.get('acc_voucher.search_for_voucher'));
        $kWindow4.title(Lang.get('acc_voucher.attach'));
        $kWindow5.title(Lang.get('acc_voucher.change_voucher'));
    };
    var initFilterForm = function() {
        $kWindow.open();
    };
    var initBarcodeForm = function() {
        $kWindow1.open();
    };
    var initReferenceForm = function() {
        $kWindow2.open();
    };
    var initVoucherForm = function() {
        $kWindow3.open();
    };
    var initAttachForm = function() {
        $kWindow4.open();
    };
    var initVoucherChangeForm = function() {
        $kWindow5.open();
    };

    var initGetDataBarcode = function() {
        jQuery('#search_barcode').on('click', function(e) {
            var filter = GetAllDataForm('#form-window-barcode', 2);
            var obj = GetDataAjax(filter.columns, filter.elem);
            var postdata = {
                data: JSON.stringify(obj)
            };
            ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-load', function(result) {
                ErmisKendoGridAddData($kGridBarcode,result.data,Ermis.page_size_1,null);
            }, function(result) {
                kendo.alert(result.message);
            });
        });
    };

    var initGetDataReference = function() {
        jQuery('#get_data').on('click', function(e) {
            var filter = GetAllDataForm('#form-window-reference', 2);
            var c = GetDataAjax(filter.columns, filter.elem);
              c.obj.general_id = sessionStorage.dataId;
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
        obj.detail = $kGrid.data("kendoGrid").dataSource.view();
        obj.tax = $kGridVat.data("kendoGrid").dataSource.data();
        obj.reference_by = reference_by;
        var crit1 = initValidationGrid(obj.detail,Ermis.field);
        var crit2 = initValidationGridColumnKey(obj.detail,Ermis.columns);
        var crit = crit1.concat(crit2);
        if(crit.length == 0){
          obj.type = jQuery('#tabstrip').find('.k-state-active').attr("data-search");
          obj.total_number = ConvertNumber(jQuery('#quantity_total').html(),Ermis.decimal_symbol);
          obj.total_amount = ConvertNumber(jQuery('#amount_total').html(),Ermis.decimal_symbol);
          obj.total_amount_rate = ConvertNumber(jQuery('#amount_rate_total').html(),Ermis.decimal_symbol);
          ErmisTemplateAjaxPost11(e, "#attach", data.columns, Ermis.link + '-save', sessionStorage.dataId, obj, obj.detail.length > 0,
              function(result) {
                  sessionStorage.dataId = result.dataId;
                  storedarrId.push(result.dataId);
                  sessionStorage.arrId = JSON.stringify(storedarrId);
                  initStatus(2);
                  initActive("1");
                  jQuery('.voucher').val(result.voucher_name);
                  initLoadGrid(result.data);
                  sessionStorage.dataId = result.dataId;
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
              initShowValidationGrid(obj.detail,crit,$kGrid);
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
                                sessionStorage.arrId = JSON.stringify(storedarrId);
                                //if (storedarrId.length > 0) {
                                //    storedarrId.length = storedarrId.length - 1;
                                //}
                                if (storedarrId.length > 0) {
                                    index = index - 1;
                                    var dataId = getAtIndex(index, storedarrId);
                                    initLoadData(dataId);
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
            if (result.detail_content) {
                var decoded = $("<div/>").html(result.print_content).text();
                decoded = decoded.replace('<tr class="detail_content"></tr>', result.detail_content);
                PrintForm(jQuery('#print'), decoded);
                jQuery('#print').html("");
            } else if (result.section_content) {
                var decoded = $("<div/>").html(result.print_content).text();
                decoded = decoded.replace('<div class="section_content"></div>', result.section_content);
                PrintForm(jQuery('#print'), decoded);
                jQuery('#print').html("");
            } else if (result.download) {
                window.open(Ermis.link + '-downloadExcel');
            }
        }, function(result) {
            kendo.alert(result.message);
        })

    };

    var initGetStoredArrId = function() {
        if (sessionStorage.arrId) {
            storedarrId = JSON.parse(sessionStorage.arrId);
            return storedarrId;
        }
    };

    var initBack = function(e) {
        ErmisTemplateEvent1(e, function() {
            index = index - 1;
            var dataId = getAtIndex(index, storedarrId);
            sessionStorage.dataId = dataId;
            initLoadData(dataId);
        })
    };

    var initForward = function(e) {
        ErmisTemplateEvent1(e, function() {
            index = index + 1;
            var dataId = getAtIndex(index, storedarrId);
            sessionStorage.dataId = dataId;
            initLoadData(dataId);
        })
    };

    var initClick = function(e) {
        jQuery("#page_content_inner").not("#grid").click(function(e) {
            $kGridTab.find(" tbody tr").removeClass("k-state-selected");
            if (jQuery(e.target).closest('#grid').length) {
                return false;
            } else if (jQuery('.k-grid-edit-row').length > 0) {
                //$kGrid.data("kendoGrid").cancelChanges(); // CLOSE ALL
                //$kGrid.data("kendoGrid").closeCell();
                $kGridTab.data("kendoGrid").cancelRow();
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
                  if(dataGrid.hasOwnProperty("description") || dataGrid.hasOwnProperty("subject_code")){
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


    var initChoose = function(e) {
        ErmisTemplateEvent1(e, function() {
            if ($kGridSubject.find('tr.k-state-selected').length > 0) {
                var grid = $kGridSubject.data("kendoGrid");
                var dataItem = grid.dataItem($kGridSubject.find('tr.k-state-selected'));
                $kWindow.close();
                AddChooseObjectResult(dataItem);
            } else {
                kendo.alert(Lang.get('messages.please_select_line_choose'));
            }
        });
    };


    var initClose = function(e) {
        ErmisTemplateEvent1(e, function() {
            if ($kWindow.element.is(":hidden") === false) {
                $kWindow.close();
            } else if ($kWindow1.element.is(":hidden") === false) {
                $kWindow1.close();
            } else if ($kWindow2.element.is(":hidden") === false) {
                $kWindow2.close();
            } else if ($kWindow3.element.is(":hidden") === false) {
                $kWindow3.close();
            } else if ($kWindow4.element.is(":hidden") === false) {
                $kWindow4.close();
            } else if ($kWindow5.element.is(":hidden") === false) {
                $kWindow5.close();
            }
        });
    };

    var initLoadColumn = function(data, dataItem) {
        jQuery.each($kGridTab_column, function(i, v) {
            if (v.set === "1") {
                data[v.field] = dataItem[v.field] ? dataItem[v.field] : 0;
            } else if (v.set === "2" || v.set === "5") {
                if (dataItem[v.field] !== null) {
                  var f = findObjectByKey(a[v.field],"id",dataItem[v.field]);
                  if(f != undefined || f != null){
                    data[v.field].id = dataItem[v.field];
                    data[v.field].code = f.code;
                  }else{
                    data[v.field].code =  '---SELECT---';
                    data[v.field].id = 0;
                  }
                }
            } else if (v.set === "3") {
                data[v.field] = dataItem[v.field] ? FormatNumber(parseInt(dataItem[v.field])) : 0;
            } else if (v.set === "4") {
                data[v.field] = 1;
            } else if (v.set === "6") {
                data[v.field] = dataItem[v.field];
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

    Onchange = function(e) {
        var dataItem = this.dataItem(e.item);
        var row = e.sender.element.closest("tr").index();
        //var col = e.sender.element.closest("td");
        var grid = $kGridTab.data("kendoGrid");
        var data = grid.dataSource.data()[row];
        initLoadColumn(data, dataItem);
        initFixScrollGrid();
    };

    OnchangeCancel = function(e) {
        var dataItem = this.dataItem(e.item);
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
    }

    OnchangeGroup = function(e) {
        var dataItem = this.dataItem(e.item);
        var row = e.sender.element.closest("tr").index();
        //var col = e.sender.element.closest("td");
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
            initKendoGrid();
            initKendoDatePicker();
            initKendoUiDropList();
            initStatus(status);
            initClick();
            initKeyCode();
            initChangeAuto();
            initKendoUiDialog();
            initKendoGridVat();
            initKendoGridSubject();
            initSearchGridSubject();
            initKendoUiContextMenu();
            initKendoUiContextMenuGrid();
            initKendoGridReference();
            initKendoGridBarcode();
            initKendoGridVoucher();
            initSearchGridVoucher();
            initGetDataBarcode();
            initGetDataReference();
            initKendoStartEndDatePicker();
            initKendoUiNumber();
            initBindData();
            initGetStoredArrId();
            initMonthDate();
            initChangeCurrency();
            initVoucherChange();
        }

    };

}();


jQuery(document).ready(function() {
    Ermis.init();
});
