// Tree view
var Ermis = function () {
    var $kGrid = jQuery('#grid');
    var $kGridExtra = jQuery('#grid_extra');
    var myWindow = jQuery("#form-action");
    var $kWindow = '';
    var key = '';
    var data = [];
    var dataId = '';
    var extra_data = '';
    var $export = ''; var $import = '';
    var $export_page_value = 0;var $total_export_page = 0;
    var $extra_page = jQuery("#list_extra_page");
    var myWindow_extra = jQuery("#form-window-extra");
    var $kWindow_extra = '';

    var initGetShortKey = function(){
        return key = Ermis.short_key;
    }

    var initGetColunm = function () {
        data = GetAllDataForm(Ermis.elem,2);
        return data;
    };

    
    var initGlobalRegister = function(){
      // KendoStartPickerTemplate
      ErmisKendoStartPickerTemplate("#start","dd/MM/yyyy");
      ErmisKendoEndPickerTemplate("#end","dd/MM/yyyy");
      // KendoDatePickerTemplate
      ErmisKendoDatePickerTemplate(".date", "dd/MM/yyyy");
      // KendoContextMenuTemplate
      ErmisKendoContextMenuTemplate("#context-menu", ".md-card-content");
      ErmisKendoContextMenuTemplate("#context-menu", "#form-action");
      // KendoUploadTemplate
      ErmisKendoUploadTemplate("#files", false);
        // KendoDroplistTemplate
        jQuery('.droplist.read').each(function() {
          ErmisKendoDroplistReadTemplate(this, "contains");
        }); 
        ErmisKendoDroplistTemplate(".droplist:not(.read)", "contains");
      ErmisKendoDroplistTemplate1(".database", "contains",initKendoUiChangeDB);
      // KendoTimePickerTemplate
      ErmisKendoTimePickerTemplate("#start_time","#end_time");
      // KendoMultiSelectTemplate
      jQuery('.multiselect.read').each(function() {
        ErmisKendoMultiSelectReadTemplate(this, "contains",false, '<span>#: FormatMultiSelectValueRow(data.text,'+Ermis.row_multiselect+') #</span>');
      }); 
      ErmisKendoMultiSelectTemplate(".multiselect:not(.read)", false, '<span>#: FormatMultiSelectValueRow(data.text,'+Ermis.row_multiselect+') #</span>');
      // KendoNumbericTemplate
      ErmisKendoNumbericTemplate(".number", "n"+Ermis.decimal, null, null, null, 1);
      ErmisKendoNumbericTemplate(".number-price", "n"+Ermis.decimal, null, null, null, 1000);
      // KendoColorPickerTemplate
      ErmisKendoColorPickerTemplate(".color",false,"#FFFFFF");
      // TooltipMaxlenght
      ErmisTooltipMaxlenght('[maxlength]');
      ErmisKendoEditorFullTemplate(".editor");
      // ChangeInputArr
      ErmisChangeInputArr();
      //Window Extra
      $kWindow_extra = ErmisKendoWindowTemplate(myWindow_extra, "600px", "");
      $kWindow_extra.title("Extra");    
      // KendoGridTemplateDefault
      ErmisKendoTreeViewApiTemplate($kGrid, Ermis.link+'-data', "parent_id", true, onChange, "row", jQuery(window).height() * 0.75, true, data.fields, data.columns);
    }  

    var initStatus = function (flag) {
        shortcut.remove(key + "A");
        shortcut.remove(key + "X");
        shortcut.remove(key + "E");
        shortcut.remove(key + "S");
        shortcut.remove(key + "C");
        shortcut.remove(key + "D");
        shortcut.remove(key + "I");
        shortcut.remove(key + "Q");
        shortcut.remove(key + "L");
        jQuery('.add,.copy,.cancel,.edit,.save,.delete,.import,.export,.load,.export_extra').off('click');
        if (flag === 1) {//DEFAULT
            jQuery('.save,.cancel').addClass('disabled');
            jQuery('.save,.cancel').off('click');
            jQuery('input,textarea').not('.header_main_search_input').not('#content_message').not('#files').not('.k-filter-menu input').addClass('disabled');
            jQuery(".droplist").not("#action-event").not(".not_disabled").addClass('disabled');
            jQuery('input:checkbox').parent().addClass('disabled');
            jQuery('.k-select,.k-datepicker').addClass('disabled');
            jQuery('.add,.copy,.edit,.delete,.import,.export,.load,.export_extra,.connect_database').removeClass('disabled');
            jQuery('.add').on('click', initAdd);
            jQuery('.copy').on('click', initCopy);
            jQuery('.edit').on('click', initEdit);
            jQuery('.delete').on('click', initDelete);
            jQuery('.import').on('click', initImport);
            jQuery('.export').on('click', initExport);
            jQuery('.export_extra').on('click', initExportExtra);
            jQuery('.cancel-window').on('click', initClose);
            jQuery('.choose-extra').on('click', initChooseExtraExport);
            shortcut.add(key + "A", function (e) { initAdd(e); });
            shortcut.add(key + "X", function (e) { initCopy(e); });
            shortcut.add(key + "E", function (e) { initEdit(e); });
            shortcut.add(key + "D", function (e) { initDelete(e); });
            shortcut.add(key + "I", function (e) { initImport(e); });
            shortcut.add(key + "Q", function (e) { initExport(e); });
            shortcut.add(key + "L", function (e) { altair_main_header.search_show();});
        } else if (flag === 2) {//ADD
          $kWindow.center().open();
          $kGrid.addClass('disabled');
          $kGrid.find('tr.k-state-selected').removeClass('k-state-selected');
          jQuery('.save,.cancel,.load').removeClass('disabled');
          jQuery('.k-select,.k-datepicker').removeClass('disabled');
          jQuery(".droplist").removeClass('disabled');
          jQuery('.number-price,.number').removeClass('disabled');
          jQuery('.cancel').on('click', initCancel);
          jQuery('.save').on('click', initSave);
          jQuery('.load').on('click', initLoadInput);
          shortcut.add(key + "S", function (e) { initSave(e); });
          shortcut.add(key + "C", function (e) { initCancel(e); });
          jQuery('.add,.copy,.edit,.delete,.import,.export,.export_extra,.connect_database').addClass('disabled');
          jQuery('.add,.copy,.edit,.delete,.import,.export,.export_extra').off('click');
          jQuery('input,textarea').removeClass('disabled');
          jQuery('.k-button').removeClass('disabled');
          jQuery('input:checkbox').parent().removeClass('disabled');
          jQuery('input').not('[type=radio]').val("");
          jQuery('textarea').val("");
          SetDataDefault(data.columns);
          jQuery('.load').click();
          ErmisDisableKendoDroplist(null,data.columns);
        } else if (flag === 3) {//Edit , COpy
          $kWindow.center().open();
          $kGrid.addClass('disabled');
          jQuery('.save,.cancel,.load').removeClass('disabled');
          jQuery('.k-select,.k-datepicker').removeClass('disabled');
          jQuery('.number-price,.number').removeClass('disabled');
          jQuery(".droplist").removeClass('disabled');
          jQuery('.cancel').on('click', initCancel);
          jQuery('.save').on('click', initSave);
          jQuery('.load').on('click', initLoadInput);
          shortcut.add(key + "S", function (e) { initSave(e); });
          shortcut.add(key + "C", function (e) { initCancel(e); });
          jQuery('.add,.copy,.edit,.delete,.import,.export,.export_extra,.connect_database').addClass('disabled');
          jQuery('.add,.copy,.edit,.delete,.import,.export').off('click');
          jQuery('input,textarea').removeClass('disabled');
          jQuery('.k-button').removeClass('disabled');
          jQuery('input:checkbox').parent().removeClass('disabled');                 
          ErmisDisableKendoDroplist(dataId,data.columns);
        } else if (flag === 4) {//Cancel, Save
          $kGrid.removeClass('disabled');
          $kGrid.find('tr.k-state-selected').removeClass('k-state-selected');
          jQuery('input').not('[type=radio]').val("");
          jQuery('textarea').val("");
          SetDataDefault(data.columns);
          jQuery('.k-select,.k-datepicker').addClass('disabled');
          jQuery('.number-price,.number').addClass('disabled');
          jQuery('.save,.cancel').addClass('disabled');
          jQuery('.save,.cancel').off('click');
          jQuery('input,textarea').not('.header_main_search_input').not('#files').not('.k-filter-menu input').addClass('disabled');
          jQuery(".droplist").not("#action-event").not(".not_disabled").addClass('disabled');
          jQuery('input:checkbox').parent().addClass('disabled');
          $kGridExtra.find('input:checkbox').parent().removeClass('disabled');
          jQuery('.add,.copy,.edit,.delete,.import,.export,.export_extra,.connect_database').removeClass('disabled');
          jQuery('.add').on('click', initAdd);
          jQuery('.copy').on('click', initCopy);
          jQuery('.edit').on('click', initEdit);
          jQuery('.delete').on('click', initDelete);
          jQuery('.import').on('click', initImport);
          jQuery('.export').on('click', initExport);
          jQuery('.export_extra').on('click', initExportExtra);
          shortcut.add(key + "A", function (e) { initAdd(e); });
          shortcut.add(key + "X", function (e) { initCopy(e); });
          shortcut.add(key + "E", function (e) { initEdit(e); });
          shortcut.add(key + "D", function (e) { initDelete(e); });
          shortcut.add(key + "I", function (e) { initImport(e); });
          shortcut.add(key + "Q", function (e) { initExport(e); });
          shortcut.add(key + "L", function (e) { altair_main_header.search_show();});
        }
    };

    var initKendoUiChangeDB = function (e) {
      ErmisTemplateAjaxPost1(e,"#form_select",Ermis.link+'-change-database',
          function(result){
            // Load data droplist ".load_droplist"
            if(jQuery(".droplist").hasClass("load_droplist") == true ){
              jQuery.each(data.columns, function (k, v) {
                  if (v.addoption === "true") {
                    var parent = jQuery('select[name="' + v.field + '"]').parents('td');
                    var id = jQuery('select[name="' + v.field + '"]').attr('id');
                    var $class = jQuery('select[name="' + v.field + '"]').prop('class');
                    var $width = jQuery('select[name="' + v.field + '"]').data('width');
                    jQuery('#'+id).data('kendoDropDownList').destroy();
                    parent.empty();
                    parent.append('<select id="'+ id+'" class="'+$class+'" data-width="'+$width+'" name="'+id+'">');
                  jQuery('#'+ id).kendoDropDownList({
                       dataTextField: "text",
                       dataValueField: "value",
                       dataSource: result.datatb,
                       filter: "contains",
                       optionLabel: "--Select--"
                   });
                  }
              });

            };
            // Change Title
            var title = jQuery('.md-card-toolbar-heading-text').text();
            jQuery('title').html(result.com_name+' - '+title);
            // Change Grid
            var grid = $kGrid.data("kendoTreeList");
            var ds = new kendo.data.TreeListDataSource ({ data: result.data , schema: {
                model: {
                    id: "id",
                    parentId:  "parent_id",
                    fields: data.fields,
                    expanded: true
                },
            }});
            ds.read(ds);
            grid.setDataSource(ds);
            initStatus(4);
          },
          function(result){
            kendo.alert(result.message);
          }
      )
    };

    var initKendoUiDialog = function (type) {
        if (type === 1) {
            var grid = $kGrid.data("kendoTreeList");
            $export = ErmisKendoDialogTemplate("#export","400px","Export",null,"Export Excel","Export PDF","Close",onExcel,onPDF);
          } else {
            $import = ErmisKendoDialogTemplate("#import","400px","Import",'<form id="import-form" enctype="multipart/form-data" role="form" method="post"><input name="files" id="files" type="file" /></form>','Import File','Download File',"Close",onImportFile,onDownloadFile);
            ErmisKendoUploadTemplate("#files", false);
          }
        function onImportFile(e) {
          var arr = {};
          arr.action = 'import';
          arr.com = Chat.com;
          arr.key = Ermis.link;
          ErmisTemplateAjaxPostAdd3(e,'#import-form',Ermis.link+'-import',arr,
         function(){},
         function(){},
         function(results){
           kendo.alert(results.message);
         });
        }
        function onDownloadFile(e) {
            var url = Ermis.link+'-DownloadExcel';
            window.open(url);
        }
        function onExcel(e) {
          grid.setOptions({
              excel: {
                  allPages: true,
                  fileName: "Export.xlsx"
              }
            })
            grid.saveAsExcel();
        }
        function onPDF(e) {
          grid.setOptions({
            pdf: {
                 allPages: true,
                 fileName: "Export.pdf",
                 landscape: true
             },
           });
           // hide loading indicator
            kendo.ui.progress($kGrid, false);
            grid.saveAsPDF();
        }

    };

    var initLoadInputCrit = function(){
      function multiselect_change(e) {
        if($kGrid.find('tr.k-state-selected').length == 0){
          var value = this.value();
          var pd = 0 ;
          var multiselect = jQuery("#"+Ermis.fieldload_crit);
          if(multiselect.hasClass('multiselect')){
            pd = value[0];
          }else{
            pd = value;
          }
          if(value.length>0){
            var postdata = { data: JSON.stringify(pd) };
            ErmisTemplateAjaxPost0(e,postdata, Ermis.link+'-load',
            function(result){
              if(!result.data.number){
                jQuery("input[name='"+Ermis.fieldload+"']").val(result.data);
              }else{
                jQuery("input[name='"+Ermis.fieldload+"']").val(initErmisBarcodeMasker(result.data));
              }
            },function(result){
              kendo.alert(result.message);
            })
          }
        }
      }
   if(Ermis.fieldload_crit){
      var multiselect = jQuery("#"+Ermis.fieldload_crit);
      if(multiselect.hasClass('multiselect')){
        multiselect = multiselect.data("kendoMultiSelect");
      }else{
        multiselect = multiselect.data("kendoDropDownList");
      }
      multiselect.bind("change", multiselect_change);
    }
  }

  var initLoadInput = function(){
        if(Ermis.fieldload_crit){
          var value = jQuery("#"+Ermis.fieldload_crit).val();
            if(value != "0" && value != null ){
              if(Array.isArray(value) && value.length == 0){
                pd = value[0];
              }else if(value.search(",")>0){
                value = value.split(",");
                pd = value[0];
              }else{
                pd = value;
              }
              var postdata = { data: JSON.stringify(pd) };
              ErmisTemplateAjaxPost0(null,postdata, Ermis.link+'-load',
              function(result){
                if(!result.data.number){
                  jQuery("input[name='"+Ermis.fieldload+"']").val(result.data);
                }else{
                  jQuery("input[name='"+Ermis.fieldload+"']").val(initErmisBarcodeMasker(result.data));
                }
              },function(result){
                kendo.alert(result.message);
              })
            }
          }else{
            jQuery.post( Ermis.link+'-load', function( result ) {
              if(result.status == true){
                if(!result.data.number){
                  jQuery("input[name='"+Ermis.fieldload+"']").val(result.data);
                }else{
                  jQuery("input[name='"+Ermis.fieldload+"']").val(initErmisBarcodeMasker(result.data));
                }
              }
             });
          }
    }


    var initKendoUiSearchbox = function () {
        jQuery(".header_main_search_input").on("keypress blur change", function (e) {
          $searchValue = jQuery(this).val();
          initSearchFilter($searchValue)
        });
        jQuery(".header_main_search_btn").on("click", function (e) {
          $searchValue = jQuery('.header_main_search_input').val();
          initSearchFilter($searchValue);
        });
    };

    var initSearchFilter = function(search){
      var filter = { logic: "or", filters: [] };
      $searchValue = search;
      if ($searchValue) {
          $.each(data.columns, function (key, column) {
              if (column.hidden === false) {
                  if (column.type === 'number') {
                      filter.filters.push({ field: column.field, operator: "eq", value: $searchValue });
                  } else if (column.type === 'boolean') {
                      filter.filters.push({ field: column.field, operator: "eq", value: $searchValue });
                  } else if (column.type === 'date') {
                      filter.filters.push({ field: column.field, operator: "gt", value: $searchValue });
                  } else {
                      filter.filters.push({ field: column.field, operator: "contains", value: $searchValue });
                  }

              }
          });
      }
      $kGrid.data("kendoTreeList").dataSource.query({ filter: filter });
    };


    var initSaveComplete = function(rd){
      var grid = $kGrid.data("kendoTreeList");
      if (rd.t === 2) {
          grid.dataSource.pushCreate(rd);
          jQuery.each(data.columns, function (k, v) {
              if (v.addoption === "true") {
                initAddSelect(rd,v.field);
              }
          });
      } else {
          grid.dataSource.pushUpdate(rd);
          jQuery.each(data.columns, function (k, v) {
              if (v.addoption === "true" ) {
                initEditSelect(rd,v.field);
              }
          });
      }
    }

    var initSave = function (e) {
      var arr = {};
      arr.action = 'save';
      arr.com = Chat.com;
      arr.key = Ermis.link;
      ErmisTemplateAjaxPostAdd4(e, null , data, Ermis.link+'-save', arr ,dataId,
        function(result){
          kendo.alert(result.message);
          myWindow.data("kendoWindow").close();
          initStatus(4);
        },
        function(result){
          if(result.error){
            ValidationErrorMessages(result.error);
          };
          kendo.alert(result.message);
        },
        function(){
        },
        function(){
          kendo.alert(Lang.get('messages.please_fill_field'));
        });

    };

    var initAdd = function (e) {
      ErmisTemplateEvent0(e, Ermis.per.a,
         function () {
             dataId = null;
             $kWindow.title(Lang.get('action.add'));
             initStatus(2);
         },
         function () {
             kendo.alert(Lang.get('messages.you_not_permission_add'));
         });
    };

    var initCopy = function (e) {
      ErmisTemplateEvent0(e, Ermis.per.a,
         function () {
             if ($kGrid.find('tr.k-state-selected').length > 0) {
                 dataId = null;
                 $kWindow.title(Lang.get('action.copy'));
                 initStatus(3);
             } else {
                 kendo.alert(Lang.get('messages.please_select_line_copy'));
             }
         },
         function () {
             kendo.alert(Lang.get('messages.you_not_permission_add'));
         });
    };

    var initEdit = function (e) {
      ErmisTemplateEvent0(e, Ermis.per.e,
     function () {
         if ($kGrid.find('tr.k-state-selected').length > 0) {
             $kWindow.title(Lang.get('action.edit'));
             initStatus(3);
         } else {
             kendo.alert(Lang.get('messages.please_select_line_edit'));
         }
     },
     function () {
         kendo.alert(Lang.get('messages.you_not_permission_edit'));
     });
    };

    var initCancel = function (e) {
        ErmisTemplateEvent1(e, function () {
            $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('global.message'))).then(function (confirmed) {
                if (confirmed) {
                    myWindow.data("kendoWindow").close();
                    initStatus(4);
                }
            });
        });
    };

    var initDelete = function (e) {
      ErmisTemplateEvent0(e, Ermis.per.d,
              function () {
                if ($kGrid.find('tr.k-state-selected').length > 0) {
                      $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('global.message'))).then(function (confirmed) {
                        if (confirmed) {
                            var arr = {};
                            arr.id = dataId;
                            arr.action = 'delete';
                            arr.com = Chat.com;
                            arr.key = Ermis.link;
                            var postdata = { data: JSON.stringify(arr) };
                            ErmisTemplateAjaxPost0(e,postdata,Ermis.link+'-delete',
                                function (result) {
                                  myWindow.data("kendoWindow").close();
                                  kendo.alert(result.message);
                                  initStatus(4);
                                    jQuery.each(data.columns, function (k, v) {
                                      if (v.addoption === "true" ) {
                                        initRemoveSelect(arr.id,v.field);  
                                      }
                                  }); 
                                },
                                function (result) {
                                  myWindow.data("kendoWindow").close();
                                  kendo.alert(result.message);
                                  initStatus(4);
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

    // Window Extra Export

    var initAddPageExportExtra = function(){
      var totalRecords = $kGrid.data("kendoTreeList").dataSource.total();
      var $total_page = Math.ceil(totalRecords/Ermis.export_limit);
      if($total_page>$total_export_page){
        $total_export_page = $total_page;
        for (let i = 1; i <= $total_export_page; i++) {          
          $extra_page.empty();
          $extra_page.data("kendoDropDownList")
              .dataSource.add({ "text": i, "value": i });
        }
      }     
    }

    var initExportExtra = function (e) {
          initAddPageExportExtra();
          $kWindow_extra.open();
    };

       var initKendoGridExtra = function () {
          function onChange(arg) {
              extra_data = this.selectedKeyNames().join(", ");
            //  console.log(this.selectedKeyNames().join(", "));
          };
             ErmisKendoGridCheckboxTemplate1($kGridExtra ,Ermis.data_expend ,jQuery(window).height() * 0.5 , 10 ,Ermis.columns_expend , onChange , "", 'field');

       //ErmisKendoGridCheckboxTemplate($kGridExtra, { data: data.columns }, jQuery(window).height() * 0.5, 6, "extra", Ermis.columns_expend , $kWindow, function (checked) {
      //     var array = $.map(checked, function (value, index) {
      //         return [value.voucher];
      //     });
      // }, true , 'field');
   };

   var initChooseExtraExport = function(e){
    $export_page_value = parseInt($extra_page.data("kendoDropDownList").value());
    var postdata = { data: extra_data , page: $export_page_value };
     ErmisTemplateAjaxGet0(e,postdata,Ermis.link+'-export',
         function (result) {
           var a = document.createElement("a");
            a.href = result.file;
            a.download = result.name;
            document.body.appendChild(a);
            a.click();
            a.remove();
            if($export_page_value >= $total_export_page){
              $export_page_value = 0;
            };
            $extra_page.data("kendoDropDownList").select($export_page_value);
            $kWindow_extra.close();
         },
         function (result) {
             jQuery('#notification').EPosMessage('error', result.message);
         });
   }

   var initClose = function (e) {
       var jQuerylink = jQuery(e.target);
       e.preventDefault();
       if (!jQuerylink.data('lockedAt') || +new Date() - jQuerylink.data('lockedAt') > 300) {
           if ($kWindow_extra.element.is(":hidden") === false) {
               $kWindow_extra.close();
           }
       }
       jQuerylink.data('lockedAt', +new Date());
   };


      // Filter
      var initFilterMultiSelect = function(){
        jQuery.each(data.columns, function (k, v) {
            if (v.filter === "true") {
              jQuery('select[name="' + v.field + '"]').on("change", function (e){
                var a = jQuery(this).val();
                initFilterMultiSelectContent(a,v.field,v.type);
              });
            }
        });
      }

      var initFilterMultiSelectLoad = function(arr){
        jQuery.each(data.columns, function (k, v) {
            if (v.filter === "true") {
                if(arr[v.field] != null){
                  if(v.type == "arr"){
                  var a = arr[v.field];
                  }else{
                  var a = arr[v.field].split(",");                
                  };
                  initFilterMultiSelectContent(a,v.field,v.type);         
                }
            }
        });
      }

   ////////

   // ---------------

    var initImport = function (e) {
        ErmisTemplateEvent0(e, $import,
     function () {
         $import.data("kendoDialog").open();
     },
     function () {
         initKendoUiDialog(2);
     });
    };

    var initExport = function (e) {
        ErmisTemplateEvent0(e, $export,
        function () {
            $export.data("kendoDialog").open();
        },
        function () {
            initKendoUiDialog(1);
        });
    };

    var onChange = function () {
        var grid = this;
        var dataItem = grid.dataItem(grid.select());
        dataId = dataItem.id;
        SetDataAjax(data.columns, dataItem);
        initFilterMultiSelectLoad(dataItem);
    };

    var initKendoUiWindow = function () {
        function onClose() {
            initStatus(4);
        }
        $kWindow = ErmisKendoWindowTemplate1(myWindow, "600px", "", onClose);
    };

    var initClientReceive = function(){
      Echo.private('data-delete-'+Ermis.link+'-'+Chat.com)
         .listen('DataSend', (rs) => {
          initDeleteTree(rs.data.id,$kGrid);
         });
       Echo.private('data-save-'+Ermis.link+'-'+Chat.com)
          .listen('DataSend', (rs) => {
              initSaveComplete(rs.data);
          });
        Echo.private('data-import-'+Ermis.link+'-'+Chat.com)
           .listen('DataSendCollection', (rs) => {
            var grid = $kGrid.data("kendoTreeList");         
            jQuery.each(rs.data[0], function (k, v) {
              grid.dataSource.pushCreate(v);
            });           
                jQuery.each(data.columns, function (k, v) {
                  if (v.addoption === "true") {
                    jQuery.each(rs.data[0], function (l,m) {
                      initAddSelect(m,v.field);                                  
                    });
                  }
              });
           });
    }

    return {

        init: function () {
            initGetShortKey();
            initStatus(Ermis.flag);
            initGetColunm();         
            initKendoUiSearchbox();
            initKendoUiWindow();
            initClientReceive();
            initKendoGridExtra();
            initFilterMultiSelect();
            initGlobalRegister();
            initLoadInputCrit();
        }

    };

}();

jQuery(document).ready(function () {
    Ermis.init();
});
