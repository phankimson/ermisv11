// Ermis Page Default
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
    var myWindow_extra = jQuery("#form-window-extra");
    var $kWindow_extra = '';

    var initGetShortKey = function(){
        return key = Ermis.short_key;
    }

    var initGetColunm = function () {
        data = GetAllDataForm(Ermis.elem,1);
        return data;
    };

    var initKendoStartEndDatePicker = function () {
      ErmisKendoStartPickerTemplate("#start","dd/MM/yyyy");
      ErmisKendoEndPickerTemplate("#end","dd/MM/yyyy");
    };


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
            jQuery('.k-select').addClass('disabled');
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
            jQuery('.k-select').removeClass('disabled');
            jQuery(".droplist").removeClass('disabled');
            jQuery('.number-price,.number').removeClass('disabled');
            jQuery('.cancel').on('click', initCancel);
            jQuery('.save').on('click', initSave);
            jQuery('.load').on('click', initLoadInput);
            jQuery('.k-datepicker').removeClass('disabled');
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
            jQuery.each(data.columns, function (k, v) {
              if (v.addoption === "true") {              
                  jQuery('#'+v.field+'_listbox .k-item').removeClass('disabled k-state-disabled');
              }
          });
        } else if (flag === 3) {//Edit , COpy
            $kWindow.center().open();
            $kGrid.addClass('disabled');
            jQuery('.save,.cancel,.load').removeClass('disabled');
            jQuery('.k-select').removeClass('disabled');
            jQuery('.number-price,.number').removeClass('disabled');
            jQuery(".droplist").removeClass('disabled');
            jQuery('.cancel').on('click', initCancel);
            jQuery('.save').on('click', initSave);
            jQuery('.load').on('click', initLoadInput);
            jQuery('.k-datepicker').removeClass('disabled');
            shortcut.add(key + "S", function (e) { initSave(e); });
            shortcut.add(key + "C", function (e) { initCancel(e); });
            jQuery('.add,.copy,.edit,.delete,.import,.export,.export_extra,.connect_database').addClass('disabled');
            jQuery('.add,.copy,.edit,.delete,.import,.export').off('click');
            jQuery('input,textarea').removeClass('disabled');
            jQuery('.k-button').removeClass('disabled');
            jQuery('input:checkbox').parent().removeClass('disabled');
            jQuery.each(data.columns, function (k, v) {
              if (v.addoption === "true") {
                  var index = parseInt(jQuery('select[name="' + v.field + '"]').find('option[value=' + dataId + ']').index());
                  jQuery('#'+v.field+'_listbox .k-item').removeClass('disabled k-state-disabled');
                  if(dataId != null){                  
                  jQuery('#'+v.field+'_listbox .k-item').eq(index).addClass('disabled k-state-disabled');
                }
              }
          });
        } else if (flag === 4) {//Cancel, Save
            $kGrid.removeClass('disabled');
            $kGrid.find('tr.k-state-selected').removeClass('k-state-selected');
            jQuery('input').not('[type=radio]').val("");
            jQuery('textarea').val("");
            SetDataDefault(data.columns);
            jQuery('.k-select').addClass('disabled');
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

    var initKendoUiDatePicker = function () {
         ErmisKendoDatePickerTemplate(".date", "dd/MM/yyyy");
     };

    var initKendoUiEditor = function () {
      ErmisKendoEditorFullTemplate(".editor");
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
                    jQuery('#'+id).data('kendoDropDownList').destroy();
                    parent.empty();
                    parent.append('<select id="'+ id+'" class="droplist load_droplist large" data-width="200px" name="'+id+'">');
                  jQuery('#'+ id).kendoDropDownList({
                       dataTextField: "text",
                       dataValueField: "value",
                       dataSource: result.datatb,
                       filter: "contains",
                       optionLabel: "Select ..."
                   });
                  }
              });

            };
            // Change Title
            var title = jQuery('.md-card-toolbar-heading-text').text();
            jQuery('title').html(result.com_name+' - '+title);
            // Change Grid
            var grid = $kGrid.data("kendoGrid");
            var ds = new kendo.data.DataSource({ data: result.data , pageSize : Ermis.page_size});
            grid.setDataSource(ds);
            initStatus(4);
          },
          function(result){
            kendo.alert(result.message);
          }
      )
    };


    var initKendoUiContextMenu = function () {
        ErmisKendoContextMenuTemplate("#context-menu", ".md-card-content");
        ErmisKendoContextMenuTemplate("#context-menu", "#form-action");
    };

    var initKendoUiUpload = function () {
        ErmisKendoUploadTemplate("#files", false);
    };

    var initKendoUiDialog = function (type) {
        if (type === 1) {
            var grid = $kGrid.data("kendoGrid");
            $export = ErmisKendoDialogTemplate("#export","400px","Export",null,"Export Excel","Export PDF","Close",onExcel,onPDF);
          } else {
            $import = ErmisKendoDialogTemplate("#import","400px","Import",'<form id="import-form" enctype="multipart/form-data" role="form" method="post"><input name="files" id="files" type="file" /></form>','Import File','Download File',"Close",onImportFile,onDownloadFile);
              initKendoUiUpload();
          }
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
        function onExcel(e) {
          grid.setOptions({
              excel: {
                  allPages: true
              }
            })
            grid.saveAsExcel();
        }
        function onPDF(e) {
          grid.setOptions({
            pdf: {
                 allPages: true,
                 avoidLinks: true,
                 paperSize: "A4",
                 margin: { top: "2cm", left: "1cm", right: "1cm", bottom: "1cm" },
                 landscape: true,
                 repeatHeaders: true,
                 template: $("#page-template").html(),
                 scale: 0.8
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
                if(Array.isArray(value) && value.length > 0){
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
      $kGrid.data("kendoGrid").dataSource.query({ filter: filter });
    };

    var initSaveComplete = function(rd){
      var grid = $kGrid.data("kendoGrid");
      if (rd.t === 2) {
          //jQuery.each(data.columns, function (k, col) {
          //    if (col.field) {
          //        var field = col.field;
          //        grid.dataSource.add({ field: result.data[col.field] });
          //    }
          //});
          //grid.dataSource.add(result.data);
          //var dataItem = grid.dataItem("tbody tr:eq(0)");
          //if(dataItem['row_number'] != "undefined"){
          //  rd['row_number'] = dataItem['row_number']+1;
        //  }else{
        //    rd['row_number'] = 1;
        //  }
          grid.dataSource.insert(0, rd);
          jQuery.each(data.columns, function (k, v) {
              if (v.addoption === "true") {
                  jQuery('select[name="' + v.field + '"]').data("kendoDropDownList").dataSource.add({ "text": rd.code + ' - ' + rd.name, "value": rd.id });
              }
          });
      } else {
          //if(rd.a == 1){
            var dataItem  = grid.dataSource.get(rd.id);
            //    var row = grid.tbody.find("tr[data-uid='" + dataItem.uid + "']");
            //    selectedItem = grid.dataItem(row);
            //}else{
            //    selectedItem = grid.dataItem(grid.select());
            //}
            jQuery.each(data.columns, function (k, col) {
              // CL
                if(col.key == 'select' && col.type != 'arr'){
                  jQuery('select[name="' + col.field + '"]').data("kendoDropDownList").value(0);
                }else if(col.type == 'arr'){
                  jQuery('select[name="' + col.field + '"]').data("kendoMultiSelect").value([]);
                };
                if (col.field != 'row_number' && col.field && dataItem[col.field] !== rd[col.field]) {
                  dataItem.set(col.field, rd[col.field]);
                };
          });
          jQuery.each(data.columns, function (k, v) {
              if (v.addoption === "true" ) {
                  var index = parseInt(jQuery('select[name="' + v.field + '"]').find('option[value=' + rd.id + ']').index());
                  jQuery('select[name="' + v.field + '"]').data("kendoDropDownList").dataItem(index).set("text", rd.code + ' - ' + rd.name);
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
                                        var ddl = jQuery('select[name="' + v.field + '"]').data("kendoDropDownList");
                                        var index = parseInt(jQuery('select[name="' + v.field + '"]').find('option[value=' + arr.id + ']').index());
                                        var oldData = ddl.dataSource.data();
                                        ddl.dataSource.remove(oldData[index]);
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


    // Window Extra Export

    var initExportExtra = function (e) {
          $kWindow_extra.open();
    };

    var initKendoUiDialogExtra = function(){
        $kWindow_extra = ErmisKendoWindowTemplate(myWindow_extra, "600px", "");
        $kWindow_extra.title("Extra");
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
     var postdata = { data: extra_data };
     ErmisTemplateAjaxGet0(e,postdata,Ermis.link+'-export',
         function (result) {
           var a = document.createElement("a");
            a.href = result.file;
            a.download = result.name;
            document.body.appendChild(a);
            a.click();
            a.remove();
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

   var initKendoUiDropList = function () {
       ErmisKendoDroplistTemplate(".droplist", "contains");
       ErmisKendoDroplistTemplate1(".database", "contains",initKendoUiChangeDB);
   };

   var initKendoUiTimepicker = function () {
     ErmisKendoTimePickerTemplate("#start_time","#end_time");
   };

   var initKendoUiMultiSelect = function () {        
         ErmisKendoMultiSelectTemplate(".multiselect", false, '<span>#: FormatMultiSelectValueRow(data.text,'+Ermis.row_multiselect+') #</span>');
   };

   var initKendoUiNumber = function () {
     ErmisKendoNumbericTemplate(".number", "n"+Ermis.decimal, null, null, null, 1);
     ErmisKendoNumbericTemplate(".number-price", "n"+Ermis.decimal, null, null, null, 1000);
   };

   var initKendoColor = function(){
     ErmisKendoColorPickerTemplate(".color",false,"#FFFFFF");
   };

    var initKendoUiWindow = function () {
        function onClose() {
            initStatus(4);
        }
        $kWindow = ErmisKendoWindowTemplate1(myWindow, jQuery(window).width() * 0.75, "",onClose);
    };

    var initKendoUiGridView = function () {
      ErmisKendoGridTemplateDefault($kGrid, Ermis.page_size, Ermis.data, onChange, "row", jQuery(window).height() * 0.75, {
          refresh: true,
          pageSizes: true,
          buttonCount: 5
      }, data.fields, data.columns);
    };

    var initTooltipMaxLenght = function(){
      ErmisTooltipMaxlenght('[maxlength]');
    };

    var initClientReceive = function(){
      Echo.private('data-delete-'+Ermis.link+'-'+Chat.com)
         .listen('DataSend', (rs) => {
             var grid = $kGrid.data("kendoGrid");
             var dataItem  = grid.dataSource.get(rs.data.id);
             var row = grid.tbody.find("tr[data-uid='" + dataItem.uid + "']");
             grid.removeRow(row);
         });
       Echo.private('data-save-'+Ermis.link+'-'+Chat.com)
          .listen('DataSend', (rs) => {
              initSaveComplete(rs.data);
          });
        Echo.private('data-import-'+Ermis.link+'-'+Chat.com)
           .listen('DataSendCollection', (rs) => {
            var dataSource = new kendo.data.DataSource({ data: rs.data[0] , pageSize: Ermis.page_size });
            var grid = $kGrid.data("kendoGrid");
            dataSource.read();
            grid.setDataSource(dataSource);
            jQuery.each(data.columns, function (k, v) {
              if (v.addoption === "true") {
                jQuery.each(rs.data[0], function (l,m) {
                  jQuery('select[name="' + v.field + '"]').data("kendoDropDownList").dataSource.add({ "text": m.code + ' - ' + m.name, "value": m.id });
                });
              }
          });
           });
    }

    return {

        init: function () {
            initGetShortKey();
            initStatus(Ermis.flag);
            initKendoUiDatePicker();
            initGetColunm();
            initKendoUiDropList();
            initKendoUiContextMenu();
            initKendoUiGridView();
            initKendoUiSearchbox();
            initKendoUiWindow();
            initKendoUiNumber();
            initKendoColor();
            initKendoUiMultiSelect();
            initKendoUiEditor();
            initClientReceive();
            initKendoGridExtra();
            initKendoUiDialogExtra();
            initTooltipMaxLenght();
            initKendoStartEndDatePicker();
            initFilterMultiSelect();
            initLoadInputCrit();
        }

    };

}();

jQuery(document).ready(function () {
    Ermis.init();
});
