// Scroll Upload Form
var Ermis = function () {
    var $kGrid = jQuery('#grid');
    var $kGridExtra = jQuery('#grid_extra');
    var key = '';
    var data = [];
    var dataId = '';
    var extra_data = '';
    var $export = ''; var $import = '';
    var $export_page_value = 0;var $total_export_page = 0;
    var $extra_page = jQuery("#list_extra_page");
    var myWindow = jQuery("#form-window-extra");
    var $kWindow = '';

    var initGetShortKey = function(){
        return key = Ermis.short_key;
    }

    var initGetColunm = function () {
        data = GetAllDataForm(Ermis.elem);
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
      // ChangeInputArr
      ErmisChangeInputArr();
      //Window Extra
      $kWindow = ErmisKendoWindowTemplate(myWindow, "600px", "");
      $kWindow.title("Extra");    
      // KendoGridTemplateToolTip0
        if(Ermis.paging == 1){
        ErmisKendoGridTemplateToolTipPageApi0($kGrid,  Ermis.page_size, Ermis.link+'-data', onChange, "row", jQuery(window).height() * 0.75, {numeric: false, previousNext: false}, data.fields, data.columns,".k-grid-content",".tooltipImg",toolTip);
        }else{
        ErmisKendoGridTemplateToolTipApi0($kGrid,  Ermis.page_size, Ermis.link+'-data', onChange, "row", jQuery(window).height() * 0.75, {numeric: false, previousNext: false}, data.fields, data.columns,".k-grid-content",".tooltipImg",toolTip);
        }
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
        shortcut.remove(key + "W");
        jQuery('.add,.copy,.cancel,.edit,.save,.delete,.import,.export,.load,.export_extra').off('click');
        if (flag === 1) {//DEFAULT
            jQuery('.save,.cancel').addClass('disabled');
            jQuery('.save,.cancel').off('click');
            jQuery('input,textarea').not('.header_main_search_input').not('#content_message').not('#content_message_ai').not('#files').not('.k-filter-menu input').addClass('disabled');
            jQuery(".droplist").not("#action-event").not(".not_disabled").addClass('disabled');
            jQuery('input:checkbox').parent().addClass('disabled');
            jQuery('.k-select,.k-datepicker').addClass('disabled');
            jQuery('.multiselect').addClass('disabled');
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
            shortcut.add(key + "W", function (e) { initExportExtra(e); });
            shortcut.add(key + "L", function (e) { altair_main_header.search_show();});
        } else if (flag === 2) {//ADD
            $kGrid.addClass('disabled');
            $kGrid.find('tr.k-state-selected').removeClass('k-state-selected');
            jQuery(Ermis.image_upload+'_preview').attr('src',UrlString('addon/img/placehold/100.png'));
            jQuery('.save,.cancel,.load').removeClass('disabled');
            jQuery('.k-select,.k-datepicker').removeClass('disabled');
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
            jQuery('.multiselect').removeClass('disabled');
            jQuery(".droplist").removeClass('disabled');
            jQuery('input:checkbox').parent().removeClass('disabled');
            jQuery('input').not('[type=radio]').val("");
            jQuery('textarea').val("");
            SetDataDefault(data.columns);
            jQuery('.load').click();
            ErmisDisableKendoDroplist(null,data.columns);
        } else if (flag === 3) {//Edit , COpy
            $kGrid.addClass('disabled');
            jQuery('.save,.cancel,.load').removeClass('disabled');
            jQuery('.k-select,.k-datepicker').removeClass('disabled');
            jQuery('.multiselect').removeClass('disabled');
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
            jQuery(".droplist").removeClass('disabled');
            jQuery('input:checkbox').parent().removeClass('disabled');
            ErmisDisableKendoDroplist(dataId,data.columns);
        } else if (flag === 4) {//Cancel, Save
            $kGrid.find('tr.k-state-selected').removeClass('k-state-selected');
            $kGrid.removeClass('disabled');
            jQuery('input').not('[type=radio]').val("");
            jQuery('textarea').val("");
            SetDataDefault(data.columns);
            jQuery(Ermis.image_upload+'_preview').attr('src',UrlString('addon/img/placehold/100.png'));
            jQuery('.k-select,.k-datepicker').addClass('disabled');
            jQuery('.multiselect').addClass('disabled');
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
            shortcut.add(key + "W", function (e) { initExportExtra(e); });
            shortcut.add(key + "L", function (e) { altair_main_header.search_show();});
        }else{
          
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

    var initKendoUiDialog = function (type) {
        if (type === 1) {
            var grid = $kGrid.data("kendoGrid");
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
              var postdata = { data: pd };
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
              if(value != "0"){
                if(value.search(",")>0){
                  value = value.split(",");
                  pd = value[0];
                }else{
                  pd = value;
                }
                var postdata = { data: pd };
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
          initAddGrid(rd,grid);   
          jQuery.each(data.columns, function (k, v) {
              if (v.addoption === "true") {
                  initAddSelect(rd,v.field);    
              }
          });
      } else {
          initEditDefault(rd,grid,data.columns);
          jQuery.each(data.columns, function (k, v) {
              if (v.addoption === "true" ) {
                initEditSelect(rd,v.field)
              }
          });
      }
    }

    var initSave = function (e) {
      var arr = {};
      arr.action = 'save';
      arr.com = Chat.com;
      arr.key = Ermis.link;
      ErmisTemplateAjaxPostAddImage4(e, Ermis.image_upload ,null , data, Ermis.link+'-save', arr ,dataId,
        function(result){
          jQuery('#notification').EPosMessage('success', result.message);
          initStatus(4);
        },
        function(result){
          if(result.error){
            ValidationErrorMessages(result.error);
          };
          jQuery('#notification').EPosMessage('error', result.message);
        },
        function(){
        },
        function(){
          jQuery('#notification').EPosMessage('error', Lang.get('messages.please_fill_field'));
        });
    };

    var initAdd = function (e) {
      ErmisTemplateEvent0(e, Ermis.per.a,
         function () {
             dataId = null;
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
                                    jQuery('#notification').EPosMessage('success', result.message);
                                    initStatus(4);
                                    jQuery.each(data.columns, function (k, v) {
                                      if (v.addoption === "true" ) {
                                        initRemoveSelect(arr.id,v.field);  
                                      }
                                  }); 
                                },
                                function (result) {
                                    jQuery('#notification').EPosMessage('error', result.message);
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
      var totalRecords = $kGrid.data("kendoGrid").dataSource.total();
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
          $kWindow.open();
    };

    var initKendoGridExtra = function () {
          function onChange(arg) {
              extra_data = this.selectedKeyNames().join(", ");
              //console.log(this.selectedKeyNames())
          }
          ErmisKendoGridCheckboxTemplate1($kGridExtra ,Ermis.data_expend ,jQuery(window).height() * 0.5 , 10 ,Ermis.columns_expend , onChange ,"", 'field');
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
            $kWindow.close();
         },
         function (result) {
             jQuery('#notification').EPosMessage('error', result.message);
         });
   }

   var initClose = function (e) {
       var jQuerylink = jQuery(e.target);
       e.preventDefault();
       if (!jQuerylink.data('lockedAt') || +new Date() - jQuerylink.data('lockedAt') > 300) {
           if ($kWindow.element.is(":hidden") === false) {
               $kWindow.close();
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
    };


    var initImagePreview = function(){
      jQuery(Ermis.image_upload).on("change",function(){
        ImageReadURL(this,Ermis.image_upload);
      })
    };

    var toolTip = function(){
        var target = $(e.target);
        var img = target.attr('data-img');
        return kendo.template(jQuery("#template_img").html())({
          value: UrlString(img)
        });
    }    

    var initClientReceive = function(){
      Echo.private('data-delete-'+Ermis.link+'-'+Chat.com)
         .listen('DataSend', (rs) => {
           initDeleteGrid(rs.data.id,$kGrid);
         });
       Echo.private('data-save-'+Ermis.link+'-'+Chat.com)
          .listen('DataSend', (rs) => {
              initSaveComplete(rs.data);
          });
        Echo.private('data-import-'+Ermis.link+'-'+Chat.com)
           .listen('DataSendCollection', (rs) => {
            var grid = $kGrid.data("kendoGrid");
            jQuery.each(rs.data[0], function (k, v) {
              initAddGrid(v,grid);
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
            initGetColunm();
            initKendoUiSearchbox();
            initStatus(Ermis.flag);
            initClientReceive();
            initKendoGridExtra();
            initImagePreview();
            initGlobalRegister();
            initLoadInputCrit();
        }

    };

}();

jQuery(document).ready(function () {
    Ermis.init();
});
