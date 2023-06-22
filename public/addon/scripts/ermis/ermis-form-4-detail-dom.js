var Ermis = function () {
    var $kGrid = jQuery('#grid');
    var $kGridVoucher = jQuery('#grid_voucher');
    var $kGridVat = jQuery('#grid_vat');
    var $kGridSubject = jQuery('#grid_subject');
    var $kGridReference = jQuery('#grid_reference');
    var $kGridBarcode = jQuery('#grid_barcode');
    var $auto = jQuery("#accounted_auto");
    var myWindow = jQuery("#form-window-filter");
    var $kWindow = '';
    var myWindow1 = jQuery("#form-window-barcode");
    var $kWindow1 = '';
    var myWindow2 = jQuery("#form-window-reference");
    var $kWindow2 = '';
    var myWindow3 = jQuery("#form-window-voucher");
    var $kWindow3 = '';
    var dataSource = '';
    var key = '';
    var ds = '';
    var a = []; var b; var data = [];
    var status = 0;
    var voucher = '';
    var storedarrId = [];
    var index = 0;
    var currentIndex = 0;

    var initGetShortKey = function(){
        return key = Ermis.short_key;
    };


    var initLoadData = function (dataId) {
        var postdata = { data: JSON.stringify(dataId) };
        ErmisTemplateAjaxPost0(e,postdata,Ermis.link+'-bind',
            function (result) {
              if(result.general){
                initActive(result.general.active);
                SetDataAjax(data.columns,result.general);
                var grid = $kGrid.data("kendoGrid");
                var grid_vat = $kGridVat.data("kendoGrid");
                ds = new kendo.data.DataSource({ data: result.detail, schema: { model: { fields: Ermis.field } }, aggregate: Ermis.aggregate });
                grid.setDataSource(ds);
                dataSource = new kendo.data.DataSource({ data:  result.tax, schema: { model: { fields: Ermis.field_tax } },  aggregate: Ermis.aggregate });
                grid_vat.setDataSource(dataSource);
                $kGrid.addClass('disabled');
                calculatePriceBind(result.detail);
              }else{
                initStatus(7);
              }
            },
            function (result) {
                initStatus(7);
            });
    };

    var initBindData = function () {
        if (sessionStorage.dataId) {
            var dataId = sessionStorage.dataId;
            initLoadData(dataId);
          } else {
               initStatus(1);
           }
    };


    var initGetColunm = function () {
        data = GetAllDataForm('#form-action',2);
        return data;
    };

    var initVoucherMasker = function () {
      return voucher = initErmisBarcodeMaskerHide(Ermis.voucher);
    };

    var initCheckSession = function () {
      return status = initErmisCheckSession();
    };


    var initKendoGridVoucher = function () {
        ErmisKendoGridVoucherTemplate($kGridVoucher, {data: [] }, "row", jQuery(window).height() * 0.5, true,  Ermis.columns_voucher);

        $kGridVoucher.dblclick(function (e) {
            initChooseVoucher(e);
        });

    };

    var initChooseVoucher = function (e) {
      ErmisTemplateEvent1(e,function(){
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

    var initSearchGridVoucher = function () {
        jQuery('#search_voucher').on('click', function () {
            var filter = GetAllDataForm('#form-window-voucher', 2);
            var c = GetDataAjax(filter.columns);
            var postdata = { data: JSON.stringify(c.object) };
            ErmisTemplateAjaxPost0(e,postdata,Ermis.link+'-find',function (result) {
              var grid = $kGridVoucher.data("kendoGrid");
              var ds = new kendo.data.DataSource({ data: result.data });
              grid.setDataSource(ds);
              grid.dataSource.page(1);
            },function (result) {
              kendo.alert(result.message);
            });
        });
    };


    var initKendoGridSubject = function () {
      ErmisKendoGridVoucherTemplate($kGridSubject, {data: [] }, "row", jQuery(window).height() * 0.5, true,  Ermis.columns_subject);

        $kGridSubject.dblclick(function (e) {
            initChoose(e);
        });

    };

    var initSearchGridSubject = function () {
        jQuery('#search_data').on('click', function (e) {
            var filter = GetAllDataForm('#form-window-filter', 2);
            var c = GetDataAjax(filter.columns);
            var postdata = { data: JSON.stringify(c.obj) };
            ErmisTemplateAjaxPost0(e,postdata,Ermis.link+'-get',function (result) {
              var grid = $kGridSubject.data("kendoGrid");
              var ds = new kendo.data.DataSource({ data: result.data });
              grid.setDataSource(ds);
              grid.dataSource.page(1);
            },function (result) {
              kendo.alert(result.message);
            });
        });

    };

    var initMonthDate = function () {
      ErmisKendoMonthPickerTemplate(".month-picker","year","year","MM/yyyy");
    };

    var initTabsTrip = function () {
        var ts = jQuery("#tabstrip");
        ts.kendoTabStrip();
        ts.find('ul').show();
    };
    var initScanBarcode = function(e){
      var obj = {};
      var $this = e.currentTarget ? e.currentTarget : e
      obj.value = jQuery($this).val();
      if(obj.value){
      obj.id = sessionStorage.dataId;
      var postdata = { data: JSON.stringify(obj) };
      ErmisTemplateAjaxPost0(e,postdata,Ermis.link+'-scan',function (result) {
        var i = result.data;
        var grid = $kGrid.data("kendoGrid");
        var dataItem  = grid.dataSource.get(i.id);
        if(dataItem){
          var row = grid.tbody.find("tr[data-uid='" + dataItem.uid + "']");
          var selectedItem = grid.dataItem(row);
          selectedItem.set("quantity", dataItem.quantity + 1);
        }else{
          i.quantity = 1 ;
          grid.dataSource.insert(0 , i);
        }

        setTimeout(function() {
          jQuery($this).val("");
          jQuery($this).focus();
        }, 1);
      },function (result) {
        kendo.alert(result.message);
      });

      }
    }

    var initKendoGridVat = function () {
      ErmisKendoGridTemplate3($kGridVat,Ermis.data,Ermis.aggregate,"",50,true,jQuery(window).height() * 0.5,Ermis.column_grid);
   };

    var initKendoGridBarcode = function () {
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
         content: function (e) {
             var target = e.target; // element for which the tooltip is shown
             return $(target).text();
         }
     });

       grid.table.on("click", ".k-checkbox", selectRow);
        //bind click event to the checkbox
        //grid.table.on("click", ".k-checkbox" , selectRow);
        jQuery('#header-chb-b').change(function(ev){
            var checked = ev.target.checked;
            $kGridBarcode.find('.k-checkbox').not("#header-chb-b").each(function (idx, item) {
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

        jQuery(".choose_barcode").bind("click", function () {
            var checked = [];
            for(var i of checkedData){
              var grid = $kGrid.data("kendoGrid");
              var dataItem  = grid.dataSource.get(i.id);
              if(dataItem){
                var row = grid.tbody.find("tr[data-uid='" + dataItem.uid + "']");
                var selectedItem = grid.dataItem(row);
                selectedItem.set("quantity", dataItem.quantity + 1);
              }else{
                i.quantity = 1 ;
                grid.dataSource.insert(0 , i);
                $kGridBarcode.find('.k-checkbox[id="'+i.id+'"]').click();
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
            jQuery("#barcode").on("blur",initScanBarcode)

    };

    var initKendoGridReference = function () {
      var array = [];
      ErmisKendoGridCheckboxTemplate($kGridReference,{data: []},jQuery(window).height() * 0.5,"row",50,"reference" ,"id",Ermis.columns_reference,$kWindow2, function (checked) {
            reference_by = [];
            array = $.map(checked, function (value, index) {
                reference_by[index] = value.id;
                return [value.voucher];
            });
            jQuery("input[name='reference']").val(array);
          });
    };


    var initKendoGrid = function () {
       ErmisKendoGridTemplate3($kGrid, Ermis.data, Ermis.aggregate, Ermis.field, 50, { confirmation: false }, jQuery(window).height() * 0.5, Ermis.columns);
    };

    var initKendoUiContextMenuGrid = function () {
        jQuery("#context-menu-grid").kendoContextMenu({
            target: "#grid",
            select: function (e) {
                var $this = e;
                var grid = $kGrid.data("kendoGrid");
                if (jQuery($this.item).children().hasClass('remove_row')) {
                    $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('global.message'))).then(function (confirmed) {
                        if (confirmed) {
                            grid.removeRow($kGrid.find('tr.k-state-selected'));
                        }
                    });
                } else if (jQuery($this.item).children().hasClass('new_row')) {
                    grid.addRow();
                } else if (jQuery($this.item).children().hasClass('close_row')) {
                    grid.cancelRow();
                } else if (jQuery($this.item).children().hasClass('remove_all_row')) {
                    $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('global.message'))).then(function (confirmed) {
                        if (confirmed) {
                            grid.cancelChanges(); // CLOSE ALL
                        }
                    });
                }
            }
        });
        $kGrid.on("mousedown", "tr[role='row']", function (e) {
            if (e.which === 3) {
                $kGrid.find(" tbody tr").removeClass("k-state-selected");
                jQuery(this).addClass("k-state-selected");
            }
        });
    };

    var initKendoUiContextMenu = function () {
        jQuery("#context-menu").kendoContextMenu({
            target: "#form-action"
        });
    };

    var initStatus = function (flag) {
        shortcut.remove(key + "A");
        shortcut.remove(key + "E");
        shortcut.remove(key + "S");
        shortcut.remove(key + "C");
        shortcut.remove(key + "D");
        shortcut.remove(key + ".");
        shortcut.remove(key + ",");
        shortcut.remove(key + "I");
        jQuery('.add,.edit,.delete,.back,.forward,.print,.cancel,.save,.choose,.filter,.pageview,.reference,.write_item,.unwrite_item,.advance_teacher,.advance_employee').addClass('disabled');
        jQuery('.add,.edit,.delete,.back,.forward,.print-item,.cancel,.save,.choose,.pageview,.filter,.reference,.write_item,.unwrite_item,.advance_teacher,.advance_employee').off('click');
        jQuery('input,textarea').not(".start,.end").not('.header_main_search_input').not('#files').not('.k-filter-menu input').addClass('disabled');
        jQuery(".droplist").addClass('disabled');
        jQuery('input:checkbox').parent().addClass('disabled');
        jQuery('.date-picker').not(".start,.end").addClass('disabled');
        jQuery(".k-input").not(".start,.end").addClass('disabled');
        jQuery('.choose_voucher').on('click', initChooseVoucher);
        jQuery('.cancel-window').on('click', initClose);
        shortcut.add(key + "I", function (e) { initChooseVoucher(e); });
        $kGrid.addClass('disabled');
        if (flag === 1) {//ADD
            sessionStorage.removeItem("dataId");
            jQuery('.cancel,.save,.choose,.cancel-window,.filter,.reference,.advance_teacher,.advance_employee').removeClass('disabled');
            jQuery('.cancel').on('click', initCancel);
            jQuery('.save').on('click', initSave);
            jQuery('.choose').on('click', initChoose);
            jQuery('.cancel-window').on('click', initClose);
            jQuery('.filter').on('click', initFilterForm);
            jQuery('.barcode').on('click', initBarcodeForm);
            jQuery('.reference').on('click', initReferenceForm);
            shortcut.add(key + "S", function (e) { initSave(e); });
            shortcut.add(key + "C", function (e) { initCancel(e); });
            jQuery('input,textarea').removeClass('disabled');
            jQuery('.k-button').removeClass('disabled');
            jQuery(".droplist").removeClass('disabled');
            jQuery(".k-input").removeClass('disabled');
            jQuery('input:checkbox').parent().removeClass('disabled');
            jQuery('.date-picker,.month-picker').removeClass('disabled');
            jQuery('input').not(".start,.end").not('[type=radio]').not(".date-picker,.start,.end,.month-picker,.voucher").val("");
            jQuery(".date-picker,.end,.start").val(kendo.toString(kendo.parseDate(new Date()), 'dd/MM/yyyy'));
            jQuery(".voucher").val(voucher);
            $kGrid.data('kendoGrid').dataSource.data([]);
            $kGrid.removeClass('disabled');
        } else if (flag === 2) {//SAVE
            jQuery('.add,.edit,.print,.back,.forward,.delete').removeClass('disabled');
            shortcut.add(key + "A", function (e) { initAdd(e); });
            shortcut.add(key + "E", function (e) { initEdit(e); });
            shortcut.add(key + ",", function (e) { initBack(e); });
            shortcut.add(key + ".", function (e) { initForward(e); });
            jQuery('.add').on('click', initAdd);
            jQuery('.edit').on('click', initEdit);
            jQuery('.print-item').on('click', initPrint);
            jQuery('.back').on('click', initBack);
            jQuery('.forward').on('click', initForward);
            jQuery('.delete').on('click', initDelete);
            $kGrid.addClass('disabled');
        } else if (flag === 3) { //EDIT
            jQuery('.cancel,.save,.filter,.reference,.advance_teacher,.advance_employee').removeClass('disabled');
            jQuery('.cancel').on('click', initCancel);
            jQuery('.save').on('click', initSave);
            jQuery('.filter').on('click', initFilterForm);
            jQuery('.barcode').on('click', initBarcodeForm);
            jQuery('.reference').on('click', initReferenceForm);
            jQuery('.cancel-window').on('click', initClose);
            shortcut.add(key + "S", function (e) { initSave(e); });
            shortcut.add(key + "C", function (e) { initCancel(e); });
            jQuery('input,textarea').removeClass('disabled');
            jQuery('.k-button').removeClass('disabled');
            jQuery(".droplist").removeClass('disabled');
            jQuery(".k-input").removeClass('disabled');
            jQuery('input:checkbox').parent().removeClass('disabled');
            jQuery('.date-picker,.month-picker').removeClass('disabled');
            $kGrid.removeClass('disabled');
        } else if (flag === 4) { //CANCEL
            jQuery('.add,.edit,.print,.back,.forward,.delete,.pageview').removeClass('disabled');
            shortcut.add(key + "A", function (e) { initAdd(e); });
            shortcut.add(key + "E", function (e) { initEdit(e); });
            shortcut.add(key + ",", function (e) { initBack(e); });
            shortcut.add(key + ".", function (e) { initForward(e); });
            jQuery('.add').on('click', initAdd);
            jQuery('.edit').on('click', initEdit);
            jQuery('.print-item').on('click', initPrint);
            jQuery('.back').on('click', initBack);
            jQuery('.forward').on('click', initForward);
            jQuery('.delete').on('click', initDelete);
            jQuery('.pageview').on('click', initVoucherForm);
            shortcut.add(key + "I", function (e) { initChooseVoucher(e); });
            if (!sessionStorage.dataId) {
                jQuery('.print,.delete,.edit').addClass('disabled');
                jQuery('.print,.delete,.edit').off('click');
            }
            jQuery('input').not('[type=radio]').not(".date-picker,.start,.end,.month-picker,.voucher").val("");
            $kGrid.data('kendoGrid').dataSource.data([]);
            $kGrid.addClass('disabled');
        } else if (flag === 5) { //BIND
            jQuery('.add,.edit,.print,.back,.forward,.delete').removeClass('disabled');
            shortcut.add(key + "A", function (e) { initAdd(e); });
            shortcut.add(key + "E", function (e) { initEdit(e); });
            shortcut.add(key + ",", function (e) { initBack(e); });
            shortcut.add(key + ".", function (e) { initForward(e); });
            jQuery('.add').on('click', initAdd);
            jQuery('.edit').on('click', initEdit);
            jQuery('.print-item').on('click', initPrint);
            jQuery('.back').on('click', initBack);
            jQuery('.forward').on('click', initForward);
            jQuery('.delete').on('click', initDelete);
        } else if (flag === 6) { //Write = 1
            jQuery('.add,.print,.back,.forward,.pageview').removeClass('disabled');
            shortcut.add(key + "A", function (e) { initAdd(e); });
            shortcut.add(key + ".", function (e) { initBack(e); });
            shortcut.add(key + ",", function (e) { initForward(e); });
            jQuery('.add').on('click', initAdd);
            jQuery('.print-item').on('click', initPrint);
            jQuery('.back').on('click', initBack);
            jQuery('.forward').on('click', initForward);
            jQuery('.pageview').on('click', initVoucherForm);
            shortcut.add(key + "I", function (e) { initChooseVoucher(e); });
            if (!sessionStorage.dataId) {
                jQuery('.print,.delete,.edit').addClass('disabled');
                jQuery('.print,.delete,.edit').off('click');
            }
        }else if(flag === 7) { //Default not find
           jQuery('.add,.pageview').removeClass('disabled');
           shortcut.add(key + "A", function (e) { initAdd(e); });
           jQuery('.pageview').on('click', initVoucherForm);
           shortcut.add(key + "V", function (e) { initChooseVoucher(e); });
           jQuery('.add').on('click', initAdd);
           jQuery('input').not('[type=radio]').not(".date-picker,.month-picker").val("");
           jQuery(".date-picker,.date-value").val(kendo.toString(kendo.parseDate(new Date()), 'dd/MM/yyyy'));
           jQuery(".voucher").val(voucher);
           $kGrid.data('kendoGrid').dataSource.data([]);
        }
    };

    var initActive = function (active) {
        shortcut.remove(key + "W");
        shortcut.remove(key + "U");
            if (active === "1" || active == 1) {
            initStatus(6);
            jQuery(".unwrite_item").show();
            jQuery('.unwrite_item').removeClass('disabled');
            jQuery('.unwrite_item').on('click', initUnWrite);
            shortcut.add(key + "U", function (e) { initUnWrite(e); });
            jQuery('.write_item').addClass('disabled');
            jQuery('.write_item').off('click');
            jQuery(".write_item").hide();
        } else {
            initStatus(5);
            shortcut.add(key + "W", function (e) { initWrite(e); });
            jQuery('.write_item').on('click', initWrite);
            jQuery('.write_item').removeClass('disabled');
            jQuery(".write_item").show();
            jQuery(".unwrite_item").hide();
            jQuery('.unwrite_item').addClass('disabled');
            jQuery('.unwrite_item').off('click');
        }
    }

    var initKendoStartEndDatePicker = function () {
      ErmisKendoStartEndDroplistTemplate("#start","#end","dd/MM/yyyy","#fast_date","contains");
      ErmisKendoStartEndDroplistTemplate("#start_a","#end_a","dd/MM/yyyy","#fast_date_a","contains");
    };



    var initKendoDatePicker = function () {
      ErmisKendoDatePickerTemplate(".date-picker","dd/MM/yyyy");
    };

    var initKendoUiDropList = function () {
        ErmisKendoDroplistTemplate(".droplist", "contains");
    };

    var initChangeAuto = function(){
        function OnChangeAuto(e){
          var value = this.value;
          var postdata = { data: JSON.stringify(value) };
          ErmisTemplateAjaxPost0(e,postdata,Ermis.link+'-auto',
              function (result) {
                jQuery('#form-action').find("input[name='description']").val(result.data.description);
                var grid = $kGrid.data("kendoGrid");
                grid.dataSource.data([]);
                jQuery.each(result.data.accounted_auto_detail, function (k, m) {
                grid.addRow();
                var data = grid.dataSource.data()[0];
                jQuery.each(Ermis.columns, function (i, v) {
                      if (v.set === "1") {
                          data[v.field] = m[v.field] ? m[v.field] : 0;
                      } else if (v.set === "2" || v.set === "5") {
                          if (m[v.field] !== null) {
                              data[v.field].id = m[v.field];
                          }
                      } else if (v.set === "3") {
                          data[v.field] = m[v.field] ? FormatNumber(parseInt(m[v.field])) : 0;
                      }else if (v.set === "4") {
                          data[v.field] = 1 ;
                      }
                });
              });
            },
              function () {

              });

        };
        $auto.bind("change",OnChangeAuto);
    }


    var initKendoUiDialog = function () {
        $kWindow = ErmisKendoWindowTemplate(myWindow, "600px", "");
        $kWindow1 = ErmisKendoWindowTemplate(myWindow1, "800px", "");
        $kWindow2 = ErmisKendoWindowTemplate(myWindow2, "1000px", "");
        $kWindow3 = ErmisKendoWindowTemplate(myWindow3, "1000px", "");
        $kWindow.title("Tìm kiếm đối tượng");
        $kWindow1.title("Tìm kiếm hàng hóa");
        $kWindow2.title("Tham chiếu");
        $kWindow3.title("Tìm kiếm chứng từ");
    };
    var initFilterForm = function () {
        $kWindow.open();
    };
    var initBarcodeForm = function () {
        $kWindow1.open();
    };
    var initReferenceForm = function () {
        $kWindow2.open();
    };
    var initVoucherForm = function () {
        $kWindow3.open();
    };

    var initGetDataBarcode = function () {
      jQuery('#search_barcode').on('click', function (e) {
          var filter = GetAllDataForm('#form-window-barcode', 2);
          var obj = GetDataAjax(filter.columns, filter.elem);
          var postdata = { data: JSON.stringify(obj) };
          ErmisTemplateAjaxPost0(e,postdata,Ermis.link+'-load',function (result) {
            var grid = $kGridBarcode.data("kendoGrid");
            var ds = new kendo.data.DataSource({ data: result.data , pageSize: Ermis.page_size_1 });
            grid.setDataSource(ds);
            grid.dataSource.page(1);
          },function (result) {
            kendo.alert(result.message);
          });
      });
    };

    var initGetDataReference = function () {
        jQuery('#get_data').on('click', function (e) {
            var filter = GetAllDataForm('#form-window-reference', 2);
            var c = GetDataAjax(filter.columns, filter.elem);
            var postdata = { data:JSON.stringify(c.obj) };
            ErmisTemplateAjaxPost0(e,postdata,Ermis.link+'-reference',function (result) {
              var grid = $kGridReference.data("kendoGrid");
              var ds = new kendo.data.DataSource({ data: result.data, pageSize: Ermis.page_size_1, schema: { model: { fields: Ermis.field_reference } } });
              grid.setDataSource(ds);
              grid.dataSource.page(1);
            },function (result) {
              kendo.alert(result.message);
            });
        });
    };

    var initWrite = function (e) {
      ErmisTemplateEvent0(e, Ermis.per.e,
              function () {
              var dataId = sessionStorage.dataId;
              var postdata = { data: JSON.stringify(dataId) };
                ErmisTemplateAjaxPost0(e,postdata,Ermis.link+'-write',
                    function () {
                        initActive("1");
                    },
                    function (result) {
                        kendo.alert(result.message);
                    });
            },
            function () {
                kendo.alert(Lang.get('messages.you_not_permission_write'));
            });
    };

    var initUnWrite = function (e) {
      ErmisTemplateEvent0(e, Ermis.per.e,
              function () {
              var dataId = sessionStorage.dataId;
              var postdata = { data: JSON.stringify(dataId) };
                ErmisTemplateAjaxPost0(e,postdata,Ermis.link+'-unwrite',
                    function () {
                        initActive("0");
                    },
                    function (result) {
                        kendo.alert(result.message);
                    });
            },
            function () {
                kendo.alert(Lang.get('messages.you_not_permission_unwrite'));
            });
    };

    var initSave = function (e) {
      var obj = {};
      obj.detail = $kGrid.data("kendoGrid").dataSource.data();
      obj.vat = $kGridVat.data("kendoGrid").dataSource.data();
      obj.reference_by = reference_by;
      obj.type = jQuery('#tabstrip').find('.k-state-active').attr("data-search");
      obj.total_number = ConvertNumber(jQuery('#quantity_total').html(),Ermis.decimal_symbol);
      obj.total_amount = ConvertNumber(jQuery('#amount_total').html(),Ermis.decimal_symbol);
      ErmisTemplateAjaxPost7(e, "#tabs_anim", Ermis.link+'-save', sessionStorage.dataId, obj, obj.detail.length > 0,
            function (result) {
                sessionStorage.dataId = result.dataId;
                storedarrId.push(result.dataId);
                sessionStorage.arrId = JSON.stringify(storedarrId);
                initStatus(2);
                initActive("1");
                jQuery('.voucher').val(result.voucher_name);
                var grid = $kGrid.data("kendoGrid");
                ds = new kendo.data.DataSource({ data: result.detail, pageSize: Ermis.page_size_1, schema: { model: { fields: Ermis.field } }, aggregate: Ermis.aggregate });
                grid.setDataSource(ds);
                ds = new kendo.data.DataSource({ data: result.tax, schema: { model: { fields: Ermis.field_tax } }, aggregate: Ermis.aggregate });
                grid_expand.setDataSource(ds);
                sessionStorage.dataId = result.dataId;
            },
            function () {

            },
            function (result) {
                kendo.alert(result.message);
            },
            function () {
                kendo.alert(Lang.get('messages.please_fill_form_detail'));
            },
            function () {
                kendo.alert(Lang.get('messages.please_fill_field'));
            });

      };

    var initAdd = function (e) {
      ErmisTemplateEvent0(e, Ermis.per.a,
         function () {
           initStatus(1);
           sessionStorage.removeItem('dataID');
         },
         function () {
             kendo.alert(Lang.get('messages.you_not_permission_add'));
         });
    };
    var initDelete = function (e) {
      ErmisTemplateEvent0(e, Ermis.per.d,
              function () {
                      $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('global.message'))).then(function (confirmed) {
                        if (confirmed) {
                          var dataId = sessionStorage.dataId;
                          var postdata = { data: JSON.stringify(dataId) };
                            ErmisTemplateAjaxPost0(e,postdata,Ermis.link+'-delete',
                                function (result) {
                                  //var current = sessionStorage.current;
                                  storedarrId = storedarrId.filter(function(e) { return e !== sessionStorage.dataId })
                                  //storedarrId.sort();
                                  sessionStorage.arrId = JSON.stringify(storedarrId);
                                  //if (storedarrId.length > 0) {
                                  //    storedarrId.length = storedarrId.length - 1;
                                  //}
                                  if (storedarrId.length > 0) {
                                      index =  index - 1;
                                      var dataId = getAtIndex(index,storedarrId);
                                      initLoadData(dataId);
                                    } else {
                                        sessionStorage.removeItem('dataId');
                                        initStatus(4);
                                    }
                                },
                                function (result) {
                                    kendo.alert(result.message);
                                });
                        }
                    });
            },
            function () {
                kendo.alert(Lang.get('messages.you_not_permission_delete'));
            });
    };

    var initEdit = function (e) {
      ErmisTemplateEvent0(e, Ermis.per.e,
         function () {
           initStatus(3);
         },
         function () {
             kendo.alert(Lang.get('messages.you_not_permission_edit'));
         });
    };

    var initCancel = function (e) {
      ErmisTemplateEvent1(e, function () {
            $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('global.message'))).then(function (confirmed) {
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
    var initPrint = function (e) {
        var obj = {};
        obj.id = sessionStorage.dataId;
        obj.voucher = jQuery(this).attr('data-id');
        ErmisTemplateAjaxPost0(e,obj,Ermis.link+'-print',function (result) {
          if (result.detail_content) {
            var decoded = $("<div/>").html(result.print_content).text();
              decoded = decoded.replace('<tr class="detail_content"></tr>', result.detail_content);
              PrintForm(jQuery('#print'), decoded);
              jQuery('#print').html("");
          }else if(result.section_content){
            var decoded = $("<div/>").html(result.print_content).text();
              decoded = decoded.replace('<div class="section_content"></div>', result.section_content);
              PrintForm(jQuery('#print'), decoded);
              jQuery('#print').html("");
          }else if (result.download){
              window.open(Ermis.link+'-downloadExcel');
          }
        },function (result) {
          kendo.alert(result.message);
        })

    };
    var initGetStoredArrId = function () {
        if (sessionStorage.arrId) {
            storedarrId = JSON.parse(sessionStorage.arrId);
            return storedarrId;
        }
    };
    var initBack = function (e) {
      ErmisTemplateEvent1(e,function(){
        index =  index - 1;
        var dataId = getAtIndex(index,storedarrId);
        sessionStorage.dataId = dataId;
        initLoadData(dataId);
      })
    };
    var initForward = function (e) {
      ErmisTemplateEvent1(e,function(){
        index =  index + 1;
        var dataId = getAtIndex(index,storedarrId);
        sessionStorage.dataId = dataId;
        initLoadData(dataId);
      })
    };

    var initClick = function (e) {
        jQuery("#page_content_inner").not("#grid").click(function (e) {
            $kGrid.find(" tbody tr").removeClass("k-state-selected");
            if (jQuery(e.target).closest('#grid').length) {
                return false;
            } else if (jQuery('.k-grid-edit-row').length > 0) {
                //$kGrid.data("kendoGrid").cancelChanges(); // CLOSE ALL
                //$kGrid.data("kendoGrid").closeCell();
                $kGrid.data("kendoGrid").cancelRow();
            }
        });
    };

    var initKeyCode = function () {
        jQuery(document).keyup(function (e) {
            var grid = $kGrid.data("kendoGrid");
            $kGrid.find(" tbody tr").removeClass("k-state-selected");
            if (e.keyCode === 13 && !$kGrid.hasClass('disabled')) {
              if(e.target.id == "barcode"){
                initScanBarcode(e.target);
              }else{
                grid.addRow();
              }
            } else if (e.keyCode === 27 && !$kGrid.hasClass('disabled')) {
                grid.cancelChanges();
            }
        });
    };

    var initChoose = function (e) {
        ErmisTemplateEvent1(e,function(){
            if ($kGridSubject.find('tr.k-state-selected').length > 0) {
                var grid = $kGridSubject.data("kendoGrid");
                var dataItem = grid.dataItem($kGridSubject.find('tr.k-state-selected'));
                $kWindow.close();
                jQuery.each(Ermis.columns_subject, function (i, v) {
                    jQuery('#form-action').find('input[name="' + v.field + '"]').val(dataItem[v.field]);
                });
            } else {
                kendo.alert(Lang.get('messages.please_select_line_choose'));
            }
        });
    };


    var initClose = function (e) {
        ErmisTemplateEvent1(e,function(){
            if ($kWindow.element.is(":hidden") === false) {
                $kWindow.close();
            } else if ($kWindow1.element.is(":hidden") === false) {
                $kWindow1.close();
            }else if ($kWindow2.element.is(":hidden") === false) {
                $kWindow2.close();
            }else if ($kWindow3.element.is(":hidden") === false) {
                $kWindow3.close();
            }
        });
    };


    Onchange = function (e) {
        var dataItem = this.dataItem(e.item);
        var data = $kGrid.data("kendoGrid").dataSource.data()[0];
        jQuery.each(Ermis.columns, function (i, v) {
            if (v.set === "1") {
                data[v.field] = dataItem[v.field] ? dataItem[v.field] : 0;
            } else if (v.set === "2") {
                if (dataItem[v.field] !== null) {
                    data[v.field].id = dataItem[v.field];
                }
            } else if (v.set === "3") {
                data[v.field] = dataItem[v.field] ? FormatNumber(parseInt(dataItem[v.field])) : 0;
            }else if (v.set === "4") {
                data[v.field] = 1 ;
            }
        });
    };

    OnchangeCancel = function (e) {
        var dataItem = this.dataItem(e.item);
        if (dataItem == undefined) {
            $kGrid.data("kendoGrid").closeCell();
        } else {
            var data = $kGrid.data("kendoGrid").dataSource.data()[0];
            jQuery.each(Ermis.columns, function (i, v) {
                if (v.set === "1") {
                    data[v.field] = dataItem[v.field] ? dataItem[v.field] : "";
                } else if (v.set === "2") {
                    if (dataItem[v.field] !== null) {
                        data[v.field].id = dataItem[v.field];
                    }
                } else if (v.set === "3") {
                    data[v.field] = dataItem[v.field] ? FormatNumber(parseInt(dataItem[v.field])) : 0;
                }
            });
        }
    };


   PriItemsDropDownEditor = function (container, options) {
      jQuery('<input required id="' + options.field + '" class="dropdown-list" name="' + options.field + '"/>')
             .appendTo(container)
             .kendoDropDownList({
                 filter: "contains",
                 dataTextField: "code",
                 dataValueField: "id",
                 optionLabel: "---SELECT---",
                 select: Onchange,
                 headerTemplate: '<div class="dropdown-header k-widget k-header">' +
                               '<span>Code</span>' +
                               '<span>Name</span>' +
                           '</div>',
                 template: '<span class="k-state-default">#: data.code #</span> - ' +
                       '<span class="k-state-default">#: data.name #</span>',
                virtual: {
                     itemHeight: 26,
                     valueMapper: function(options) {
                           options.success([options.value || 0]); //return the value <-> item index mapping;
                      }
                },
                autoBind: false,
                 dataSource: {
                     pageSize: Ermis.page_size,
                     type: "odata",
                     data: eval(a[options.field])
                 }
             });
   };

   SleItemsDropDownEditor = function (container, options) {
       jQuery('<input required id="' + options.field + '" class="dropdown-list" name="' + options.field + '"/>')
              .appendTo(container)
              .kendoDropDownList({
                  filter: "contains",
                  dataTextField: "code",
                  dataValueField: "id",
                  optionLabel: "---SELECT---",
                  select: OnchangeCancel,
                  headerTemplate: '<div class="dropdown-header k-widget k-header">' +
                                '<span>Code</span>' +
                                '<span>Name</span>' +
                            '</div>',
                  template: '<span class="k-state-default">#: data.code #</span> - ' +
                        '<span class="k-state-default">#: data.name #</span>',
                virtual: {
                  itemHeight: 26,
                  valueMapper: function(options) {
                           options.success([options.value || 0]); //return the value <-> item index mapping;
                   }
                },
                 autoBind: false,
                  dataSource: {
                      pageSize: Ermis.page_size,
                      type: "odata",
                      data: eval(a[options.field])
                  }
              });
   };

   ItemsDropDownEditor = function (container, options) {
       jQuery('<input required id="' + options.field + '" class="dropdown-list" name="' + options.field + '"/>')
            .appendTo(container)
            .kendoDropDownList({
                select: function (e) {
                    var select = this.dataItem(e.item);
                    if (select == undefined) {
                    $kGrid.data("kendoGrid").closeCell();
                  };
                },
                filter: "contains",
                dataTextField: "code",
                dataValueField: "id",
                optionLabel: "---SELECT---",
                headerTemplate: '<div class="dropdown-header k-widget k-header">' +
                              '<span>Code</span>' +
                              '<span>Name</span>' +
                          '</div>',
                template: '<span class="k-state-default"> #:data.code #</span> - ' +
                      '<span class="k-state-default">#: data.name #</span>',
                      virtual: {
                        itemHeight: 26,
                        valueMapper: function(options) {
                           options.success([options.value || 0]); //return the value <-> item index mapping;
                         }
                      },
                autoBind: false,
                dataSource: {
                    pageSize: Ermis.page_size,
                    type: "odata",
                    data: eval(a[options.field])
                }
            });
   };

   getDataItemName = function(model, field) {
       var value = '';
       b = field;
       a[b] = jQuery('#'+field+"_dropdown_list").data("json");;
       if (model.id > 0 || model.id != "") {
           var result = $.grep(eval(a[b]), function (n, i) {
               return n.id === model.id;
           });
           if (result.length > 0) {
               value = result[0].code;
           }else{
             value = '----SELECT-----';
           }
       } else {
           value = '----SELECT-----';
       }
       return value;
   };


    return {

        init: function () {
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
            initBindData();
            initGetStoredArrId();
            initMonthDate();
        }

    };

}();


jQuery(document).ready(function () {
    Ermis.init();
});
