var Ermis = function () {
    var $kGrid = jQuery('#grid');
    var $kDetailGrid = jQuery('#grid-detail');
    var $kGridVoucher = jQuery('#grid_voucher');
    var key = '';
    var dataId = '';
    var voucherId = '';
    var count_number = 0;
    var $import = '';
    var data = [];
    var dataSource = '';
    var dataSourceGeneral = '';
    var myWindow = jQuery("#form-window-voucher");
    var $kWindow = '';
    var type = '';
    var link = '';

    var initGetColunm = function () {
        data = GetAllDataForm('#form-search');
        return data;
    };
    var initGlobalRegister = function(){
      $kWindow = ErmisKendoWindowTemplate(myWindow, "1000px", "");
      $kWindow.title(Lang.get('acc_voucher.search_for_voucher'));
      ErmisKendoUploadTemplate("#files", false);
      ErmisKendoContextMenuTemplate("#context-menu",".md-card-content");
      //StartEndDroplistTemplate
      ErmisKendoStartEndDroplistTemplate("#start","#end","dd/MM/yyyy","#fast_date","contains");
      ErmisKendoNumbericTemplateDate("#day","n0",1,31);
      ErmisKendoNumbericTemplateDate("#month","n0",1,12);
      ErmisKendoNumbericTemplateDate("#year","float",null,null);
      ErmisKendoDroplistTemplate(".droplist", "contains");
    }

    var initGetActionNew = function(){
      return link = Ermis.action.new;
    };

    var initGetType = function(){
      return type = Ermis.action.type;
    };

    var initGetShortKey = function(){
        return key = Ermis.short_key;
    };

    var initKendoGrid = function () {
        dataSourceGeneral = {
            data: Ermis.data,
            pageSize: Ermis.page_size
        };
        ErmisKendoGridTemplate2($kGrid, dataSourceGeneral, onChange, "row", jQuery(window).height() * 0.5, true, Ermis.column_grid);
        initKendoGridColorActive();
    };

    var initKendoGridVoucher = function() {
        ErmisKendoGridTemplate3($kGridVoucher, [], Ermis.aggregate, Ermis.field_voucher, "row" , true, jQuery(window).height() * 0.5, Ermis.columns_voucher);      
    };

      var initSearchGridVoucher = function(e) {
            var filter = GetAllDataForm('#form-window-voucher', 2);
            var c = GetDataAjax(filter.columns);
            c.obj.type = type;
            var postdata = {
                data: JSON.stringify(c.obj)
            };
            ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-revoucher', function(result) {
                var grid = $kGridVoucher.data("kendoGrid");
                var ds = new kendo.data.DataSource({
                    data: result.data
                });
                grid.setDataSource(ds);
                grid.dataSource.page(1);
            }, function(result) {
                kendo.alert(result.message);
            });
    };

    var initStartVoucher = function(e) {
      var filter = GetAllDataForm('#form-window-voucher', 2);
      var c = GetDataAjax(filter.columns);
      c.obj.type = type;
      var postdata = {
          data: JSON.stringify(c.obj)
      };
      ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-start-voucher', function(result) {
          var grid = $kGridVoucher.data("kendoGrid");          
          var items = grid.dataSource.data();
          voucherId = result.data.id;
          for (var i = 0; i < items.length; i++) {
              count_number = i+1;
              var voucher = initErmisCountVoucher(result.data,count_number);
              items[i]["revoucher"] = voucher;
          };
          grid.refresh();
      }, function(result) {
          kendo.alert(result.message);
      });
    };

    var initChangeVoucher = function(e) {
      var obj = {};
      var grid = $kGridVoucher.data("kendoGrid");          
      obj['items'] = grid.dataSource.data();
      obj['voucherId'] = voucherId;
      obj['number'] = count_number;
      var postdata = {
          data: JSON.stringify(obj)
      };
      ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-change-voucher', function(result) {
          var grid = $kGridVoucher.data("kendoGrid");          
          var items = grid.dataSource.data();
          for (var i = 0; i < items.length; i++) {
              items[i]["voucher"] = items[i]["revoucher"];
          };
          grid.refresh();
          kendo.alert(result.message);
      }, function(result) {
          kendo.alert(result.message);
      });
    };

    var initKendoGridColorActive = function(){
      var grid = $kGrid.data("kendoGrid");
      grid.bind("dataBound", function(e){
        var rows = e.sender.tbody.children();
           for (var j = 0; j < rows.length; j++) {
              var row = $(rows[j]);
              var dataItem = e.sender.dataItem(row);
              var active = dataItem.get("active");
              if (!active) {
                row.addClass("font-green");
              }
           }
      });
    }


    var initStatus = function (flag) {
        shortcut.remove(key + "A");
        shortcut.remove(key + "V");
        shortcut.remove(key + "D");
        shortcut.remove(key + "R");
        jQuery('.view_item,.delete_item,.print,.unwrite_item,.write_item').not('.back').off('click');
        if (flag === 1) {//DEFAULT
            jQuery('.new_item').on('click', initNew);
            jQuery('#get_data').on('click', initSearchData);
            jQuery('#re_voucher').on('click', initVoucherForm);
            jQuery('#search_voucher').on('click',initSearchGridVoucher);
            jQuery('.start_voucher').on('click',initStartVoucher);
            jQuery('.change_voucher').on('click',initChangeVoucher);
            jQuery('.import_item').on('click', initImport);
            //jQuery('.view_item').on('click', initView);
            //jQuery('.delete_item').on('click', initDelete);
            //jQuery('.write_item').on('click', initWrite);
            //jQuery('.unwrite_item').on('click', initUnWrite);
            jQuery('.refesh_item').on('click', initRefesh);
            jQuery('.view_item,.delete_item,.print,.unwrite_item,.write_item').addClass('disabled');
            jQuery('.view_item,.delete_item,.print-item,.unwrite_item,.write_item').off('click');
            jQuery('.cancel-window').on('click', initClose);
            shortcut.add(key + "A", function (e) { initNew(e); });
            shortcut.add(key + "I", function (e) { initImport(e); });
            //shortcut.add(key + "V", function (e) { initView(e); });
            //shortcut.add(key + "W", function (e) { initWrite(e); });
            //shortcut.add(key + "D", function (e) { initDelete(e); });
            //shortcut.add(key + "E", function (e) { initUnWrite(e); });
            shortcut.add(key + "R", function (e) { initRefesh(e); });
        } else if (flag === 2) {
            jQuery('.view_item,.delete_item,.print').removeClass('disabled');
            jQuery('.view_item').on('click', initView);
            jQuery('.delete_item').on('click', initDelete);
            //jQuery('.write_item').on('click', initWrite);
            //jQuery('.unwrite_item').on('click', initUnWrite);
            jQuery('.print-item').on('click', initPrint);
            shortcut.add(key + "V", function (e) { initView(e); });
            //shortcut.add(key + "W", function (e) { initWrite(e); });
            shortcut.add(key + "D", function (e) { initDelete(e); });
            //shortcut.add(key + "E", function (e) { initUnWrite(e); });
        }
    };

    var initActive = function (active) {
        shortcut.remove(key + "W");
        shortcut.remove(key + "U");
        if (active == "1"|| active == 1) {
            initStatus(1);
            jQuery(".unwrite_item").show();
            jQuery('.unwrite_item,.print,.view_item').removeClass('disabled');
            jQuery('.unwrite_item').on('click', initUnWrite);
            jQuery('.print-item').on('click', initPrint);
            jQuery('.view_item').on('click', initView);
            shortcut.add(key + "U", function (e) { initUnWrite(e); });
            shortcut.add(key + "V", function (e) { initView(e); });
            jQuery('.write_item').addClass('disabled');
            jQuery('.write_item').off('click');
            jQuery(".write_item").hide();
        } else {
            initStatus(2);
            shortcut.add(key + "W", function (e) { initWrite(e); });
            jQuery('.write_item').on('click', initWrite);
            jQuery('.write_item').removeClass('disabled');
            jQuery(".write_item").show();
            jQuery(".unwrite_item").hide();
            jQuery('.unwrite_item').addClass('disabled');
            jQuery('.unwrite_item').off('click');
        }
    }

    var initChangeLink =function(){
      jQuery("select[name='type']").on("change",function(){
          link = jQuery(this).val();
          jQuery.each(Ermis.group, function( i, v ){
              if(v.code == link ){
                type = v.id;
              }
          });
         
      })        
    }

    var initNew = function (e) {
      ErmisTemplateEvent0(e, Ermis.per.a,
          function () {
              sessionStorage.status = 1;
              sessionStorage.removeItem("dataId");
              window.location = link;
          },function(){
              kendo.alert(Lang.get('messages.you_not_permission_add'));
          });
    };

    var initView = function (e) {
      ErmisTemplateEvent0(e, Ermis.per.v,
          function () {
            if ($kGrid.find('.k-state-selected').length > 0) {
                var arrId = [];
                var grid = $kGrid.data("kendoGrid");
                var selectedItem = grid.dataItem(grid.select());
                var source = $kGrid.data("kendoGrid").dataSource.data();
                jQuery.each(source, function (e, v) {
                    arrId[e] = v.id;
                    if (v.id === selectedItem.id) {
                        sessionStorage.current = e;
                    }
                });
                sessionStorage.link =  link;
                sessionStorage[type] =  JSON.stringify(arrId);
                sessionStorage.status = 5;
                sessionStorage.dataId = selectedItem.id;
                window.location = link;
            } else {
              kendo.alert(Lang.get('messages.please_select_line_view'));
            }
          },function(){
              kendo.alert(Lang.get('messages.you_not_permission_view'));
          });
    };

    var initDelete = function (e) {
      ErmisTemplateEvent0(e, Ermis.per.d,
              function () {
                if ($kGrid.find('.k-state-selected').length > 0) {
                      $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('global.message'))).then(function (confirmed) {
                        if (confirmed) {
                          var grid = $kGrid.data("kendoGrid");
                          var selectedItem = grid.dataItem(grid.select());
                          var dataId = selectedItem.id;
                          var postdata = { data: JSON.stringify(dataId) };
                            ErmisTemplateAjaxPost0(e,postdata,Ermis.link+'-delete',
                                function (result) {
                                  var grid = $kGrid.data("kendoGrid");
                                  var grid_detail = $kDetailGrid.data("kendoGrid");
                                  grid.removeRow($kGrid.find('tr.k-state-selected'));
                                  dataSource = new kendo.data.DataSource({ data: [], aggregate: Ermis.aggregate });
                                  grid_detail.setDataSource(dataSource);
                                  initStatus(1);
                                },
                                function (result) {
                                    kendo.alert(result.message);
                                });
                        }
                    });
                } else {
                    kendo.alert(Lang.get('messages.please_select_line_delete'));
                }
            },
            function () {
                kendo.alert(Lang.get('messages.you_not_permission_delete'));
            });
    };

    var initPrint = function (e) {
      ErmisTemplateEvent1(e,function(){
          if ($kGrid.find('.k-state-selected').length > 0) {
              var obj = {};
              var grid = $kGrid.data("kendoGrid");
              var selectedItem = grid.dataItem(grid.select());
              var dataId = selectedItem.id;
              obj.id = dataId;
              obj.voucher = jQuery(this).attr('data-id');
              var postdata = { data: JSON.stringify(obj) };
              ErmisTemplateAjaxPost0(e,postdata,Ermis.link+'-print',function (result) {
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
              });
          }else{
            kendo.alert(Lang.get('messages.please_select_line_view'));
          }
      })
    };
    var initWrite = function (e) {
      ErmisTemplateEvent0(e, Ermis.per.e,
              function () {
              if ($kGrid.find('.k-state-selected').length > 0) {
                var grid = $kGrid.data("kendoGrid");
                var selectedItem = grid.dataItem(grid.select());
                var dataId = selectedItem.id;
                var postdata = { data: JSON.stringify(dataId) };
                ErmisTemplateAjaxPost0(e,postdata,Ermis.link+'-write',
                    function () {
                      initActive("1");
                      selectedItem.set("active", "1");
                      initStatus(1);
                    },
                    function (result) {
                        kendo.alert(result.message);
                    });
              } else {
                kendo.alert(Lang.get('messages.please_select_line_write'));
              }
            },
            function () {
                kendo.alert(Lang.get('messages.you_not_permission_write'));
            });
    };


    var initUnWrite = function (e) {
      ErmisTemplateEvent0(e, Ermis.per.e,
              function () {
              if ($kGrid.find('.k-state-selected').length > 0) {
                var grid = $kGrid.data("kendoGrid");
                var selectedItem = grid.dataItem(grid.select());
                var dataId = selectedItem.id;
                var postdata = { data: JSON.stringify(dataId) };
                ErmisTemplateAjaxPost0(e,postdata,Ermis.link+'-unwrite',
                    function () {
                      initActive("0");
                      selectedItem.set("active", "0");
                      initStatus(1);
                    },
                    function (result) {
                        kendo.alert(result.message);
                    });
              } else {
                kendo.alert(Lang.get('messages.please_select_line_unwrite'));
              }
            },
            function () {
                kendo.alert(Lang.get('messages.you_not_permission_unwrite'));
            });
    };

    var initRefesh = function (e) {
      ErmisTemplateEvent0(e, Ermis.per.v,
         function () {
            location.reload();
         },
         function () {
             kendo.alert(Lang.get('messages.you_not_permission_view'));
         });
    };

    var initClose = function(e) {
      ErmisTemplateEvent1(e, function() {
          if ($kWindow.element.is(":hidden") === false) {
              $kWindow.close();
          }
      });
  };

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
       kendo.alert(results.message);
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

    var initSearchData = function (e) {
        var d = GetDataAjax(data.columns, data.elem);
        d.obj.type = type;
        var postdata = { data: JSON.stringify(d.obj) };
        ErmisTemplateAjaxPost0(e,postdata,Ermis.link+'-get',
            function (result) {
              dataSourceGeneral = new kendo.data.DataSource({ data: result.data, pageSize: Ermis.page_size, schema: { model: { fields: Ermis.field } } });
              var grid = $kGrid.data("kendoGrid");
              var grid_detail = $kDetailGrid.data("kendoGrid");
              grid.setDataSource(dataSourceGeneral);
              dataSource = new kendo.data.DataSource({ data: [],  aggregate: Ermis.aggregate });
              grid_detail.setDataSource(dataSource);
              calculatePriceBind(result.data);
            },
            function (result) {
                kendo.alert(result.message);
            });
    };

    var onChange = function (e) {
        var $this = this;
        var dataItem = $this.dataItem($this.select());
        initActive(dataItem.active);
        var grid_detail = $kDetailGrid.data("kendoGrid");
        dataId = dataItem.id;
        var postdata = { data: JSON.stringify(dataId) };
        ErmisTemplateAjaxPost0(e,postdata,Ermis.link+'-detail',
            function (result) {
              dataSource = new kendo.data.DataSource({ data: result.data, aggregate: Ermis.aggregate });
              grid_detail.setDataSource(dataSource);
            },
            function (result) {
                kendo.alert(result.message);
            });
    };

    var initKendoDetailGrid = function () {
        dataSource = new kendo.data.DataSource({
            aggregate: Ermis.aggregate
        });
        ErmisKendoGridTemplate5($kDetailGrid,jQuery(window).height() * 0.4,dataSource,false,true,false,Ermis.columns);
    };

    var initBack = function (e) {
      jQuery(".back").on("click", function () {
          window.history.go(-1);
      })
  };

      var initVoucherForm = function() {
        $kWindow.open();
    };

    return {
        init: function () {
            initGetActionNew();
            initGetType();
            initGetShortKey();
            initKendoGrid();
            initKendoDetailGrid();
            initKendoGridVoucher();
            initGlobalRegister();
            initStatus(Ermis.flag);
            initGetColunm();
            initBack();
            initChangeLink();
        }

    };

}();

jQuery(document).ready(function () {
    Ermis.init();
});
