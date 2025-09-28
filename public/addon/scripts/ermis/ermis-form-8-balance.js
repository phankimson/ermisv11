// Balance
var Ermis = function () {
    var $kGridTab = '';   
    var $kGridExtra = jQuery('#grid_extra');
    var tab_key = '';  
    var key = '';
    var parent_id_tree = "parent_id";
    var $kWindow = '';
    var $import = '';
    var $export = '';
    var $export_page_value = 0;var $total_export_page = 0;
    var $extra_page = jQuery("#list_extra_page");
    var myWindow = jQuery("#form-window-extra");
    var $kWindow = '';

    var initGetShortKey = function(){
        return key = Ermis.short_key;
    }

    var initGlobalRegister = function(){
        // KendoDroplistTemplate 
      jQuery('.droplist.read').each(function() {
       ErmisKendoDroplistReadTemplate(this, "contains");
      }); 
      ErmisKendoDroplistTemplate(".droplist:not(.read)", "contains");
       //Window Extra
      $kWindow = ErmisKendoWindowTemplate(myWindow, "600px", "");
      $kWindow.title("Extra");       
    }

    var initStatus = function(flag) {
      if(flag == 1){//Default
        jQuery(".save").on("click", initSave);  
        jQuery('.cancel').on('click', initCancel);
        jQuery('.export').on('click', initExport);
        jQuery('.import').on('click', initImport);
        jQuery('.export_extra').on('click', initExportExtra);
        jQuery('.choose-extra').on('click', initChooseExtraExport);
        jQuery('.cancel-window').on('click', initClose);
        jQuery("#stock").on("change", initChangeStock);
        //Shortcut
        shortcut.add(key + "I", function (e) { initImport(e); });
        shortcut.add(key + "S", function (e) { initSave(e); });
        shortcut.add(key + "C", function (e) { initCancel(e); }); 
        shortcut.add(key + "Q", function (e) { initExport(e); });
        shortcut.add(key + "W", function (e) { initExportExtra(e); });
      }else if(flag == 2){
        
      }else if(flag == 3){
       
      }else{
                
      } 
    }

    var initClickTab = function(){
       jQuery('#tabs_li').on('change.uk.tab', function(e, active, previous) {
                var tab = active.index();              
                if(tab_key != ""){
                 initClientLeave(tab_key);   
                 $export = "";                      
                };
                $kGridTab = jQuery("#grid_tab"+(tab+1));  
                tab_key = active.attr("data-key");    
                 if(tab_key == "materials" || tab_key == "tools" || tab_key == "goods" || tab_key == "upfront_costs" || tab_key == "assets" || tab_key == "finished_product"){
                    jQuery("#stock_area").detach().insertBefore($kGridTab);
                 };             
                if($kGridTab.data("kendoTreeList") === undefined && tab_key == "account"){                    
                    ErmisKendoTreeViewApiTemplate1($kGridTab, Ermis.link+'-data?type='+tab_key, parent_id_tree, true, "incell",onChangeTreeList,onDataBoundTreeList, jQuery(window).height() * 0.75, true, Ermis.fields_account, Ermis.columns_account,Ermis.aggregates);
                    initKendoGridExtra(Ermis["data_expend_"+tab_key]); 
                }else if($kGridTab.data("kendoGrid") === undefined && tab_key == "bank"){
                    ErmisKendoGridTemplateApi1($kGridTab, Ermis.page_size , Ermis.link+'-data?type='+tab_key, onChangeGrid, false , jQuery(window).height() * 0.75, {
                        numeric: false,
                        previousNext: false
                    }, true ,  Ermis.fields_bank, Ermis.columns_bank,Ermis.aggregates);
                    initKendoGridExtra(Ermis["data_expend_"+tab_key]); 
                }else if($kGridTab.data("kendoGrid") === undefined && (tab_key == "materials" || tab_key == "tools" || tab_key == "goods" || tab_key == "upfront_costs" || tab_key == "assets" || tab_key == "finished_product")){
                    var a = jQuery("#stock").val();
                    ErmisKendoGridTemplateApi1($kGridTab, Ermis.page_size , Ermis.link+'-data?type='+tab_key+'&stock='+a, onChangeGrid, false , jQuery(window).height() * 0.75, {
                        numeric: false,
                        previousNext: false
                    }, true ,  Ermis.fields_supplies_goods, Ermis.columns_supplies_goods,Ermis.aggregates_supplies_goods);
                   initKendoGridExtra(Ermis["data_expend_stock"]);  
                }else if($kGridTab.data("kendoGrid") === undefined && (tab_key == "suppliers" || tab_key == "customers" || tab_key == "employees" || tab_key == "others")){
                     ErmisKendoGridTemplateApi1($kGridTab, Ermis.page_size , Ermis.link+'-data?type='+tab_key, onChangeGrid, false , jQuery(window).height() * 0.75, {
                        numeric: false,
                        previousNext: false
                    }, true ,  Ermis.fields_object, Ermis.columns_object,Ermis.aggregates);
                    initKendoGridExtra(Ermis["data_expend_object"]);  
                }else{

                }
                initClientReceive(tab_key);
            });
    }   
        var onChangeGrid = function(e){
            setTimeout(function() {
                $kGridTab.data("kendoGrid").refresh();
            }, 0);
        }

      var onDataBoundTreeList = function(){
        if(!$kGridTab.find("tr.k-footer-template:first").hasClass("hidden")){
          $kGridTab.find("tr.k-footer-template:not(:last)").addClass("hidden");  
          var treeList = $kGridTab.data("kendoTreeList");
          var aggregates = treeList.dataSource.aggregates();
                let data = treeList.dataSource.data();
                var aggregate_total = aggregates[null];  
                jQuery.each(aggregate_total, function(field, value) {  
                        let total = 0;
                         for (let i = 0; i < data.length; i++) {
                            const item = data[i];
                            if(!item["parent_id"] && item[field]>0){
                                total += item[field]; // Add the current item's value
                            }                           
                         }
                   if(typeof aggregate_total[field] != 'undefined'){
                    aggregate_total[field]["sum"] = total;
                   };
                 })
                 treeList.refresh();
        };       
      }  

      var initChangeStock = function(e){
        var a = jQuery(e.target).val();
        var grid = $kGridTab.data("kendoGrid");
        var ds = new kendo.data.DataSource({
            transport: {
                read: {
                    url: Ermis.link+'-data?type='+tab_key+'&stock='+a,
                    dataType: "json",
                },       
            },
            pageSize: parseInt(Ermis.page_size),      
            schema: {
                model: {
                    id: "id",
                    fields: Ermis["fields_"+tab_key]
                }
            },
            aggregate: Ermis.aggregates_supplies_goods,
        });
        grid.setDataSource(ds);
      }
    
      var onChangeTreeList = function (e) {
            if(e.items.length == 1){
              var treeList = $kGridTab.data("kendoTreeList");
              var item = e.items[0];
              var parentId =  item.parentId;   
              var aggregates = treeList.dataSource.aggregates();
              var key = Object.keys(item.dirtyFields);
              if(parentId){     
              var parentDataItem = treeList.dataSource.get(parentId);                
              var aggregate = aggregates[parentId];              
                jQuery.each(key, function(i, field) {
                    if(typeof aggregate[field] != 'undefined'){
                         parentDataItem.set(field,aggregate[field]["sum"]);
                    }                   
                })              
              }else{ 
                let data = treeList.dataSource.data();
                var aggregate_total = aggregates[null];  
                jQuery.each(key, function(i, field) {  
                     let total = 0;
                         for (let i = 0; i < data.length; i++) {
                            const item = data[i];
                            if(!item["parent_id"] && item[field]>0){
                                total += item[field]; // Add the current item's value
                            }                           
                         }
                    if(typeof aggregate_total[field] != 'undefined'){
                        aggregate_total[field]["sum"] = total;
                    }
                 })
              }        
            }            
        };    

        var initSave = function(e){
            var obj = {};
            obj.action = 'save';
            obj.com = Chat.com;
            obj.key = Ermis.link;
            obj.type = tab_key;
            if (obj.type === "account") {
                obj.dataSource = $kGridTab.data("kendoTreeList").dataSource.data();
            }else{
                if(tab_key === "materials" || tab_key === "tools" || tab_key === "goods" || tab_key == "upfront_costs" || tab_key == "assets" || tab_key == "finished_product"){
                    obj.stock = jQuery("#stock").val();
                }
                obj.dataSource = $kGridTab.data("kendoGrid").dataSource.data();
            }
            var postdata = { data: JSON.stringify(obj) };
            ErmisTemplateAjaxPost0(e,postdata, Ermis.link+'-save', 
                function(result){
                    kendo.alert(result.message);
                },
                function(result){
                    kendo.alert(result.message);
                })
        }

        // Window Extra Export

    var initAddPageExportExtra = function(){
      var totalRecords = $kGridTab.data("kendoGrid").dataSource.total();
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
    
    var initAddPageExportExtraTree = function(){
      var totalRecords = $kGridTab.data("kendoTreeList").dataSource.total();
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
        if (tab_key === "account") {
             initAddPageExportExtraTree();
        }else{
             initAddPageExportExtra();
        }
         
          $kWindow.open();
    };    


    var initKendoGridExtra = function (data_expend) {
          function onChange(arg) {
              extra_data = this.selectedKeyNames().join(", ");
              //console.log(this.selectedKeyNames())
          }
          ErmisKendoGridCheckboxTemplate1($kGridExtra ,data_expend ,jQuery(window).height() * 0.5 , 10 ,Ermis.columns_expend , onChange ,"", 'field');
       //ErmisKendoGridCheckboxTemplate($kGridExtra, { data: data.columns }, jQuery(window).height() * 0.5, 6, "extra", Ermis.columns_expend , $kWindow, function (checked) {
      //     var array = $.map(checked, function (value, index) {
      //         return [value.voucher];
      //     });
      // }, true , 'field');
   };

   var initChooseExtraExport = function(e){
     $export_page_value = parseInt($extra_page.data("kendoDropDownList").value());
     var postdata = { data: extra_data , page: $export_page_value , type : tab_key};
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


    var initExport = function (e) {
          ErmisTemplateEvent0(e, $export,
         function () {
             $export.data("kendoDialog").open();
         },
         function () {
             initKendoUiDialog(1);
         });
    };


      var initImport = function (e) {
          ErmisTemplateEvent0(e, $import,
          function () {
              $import.data("kendoDialog").open();
          },
          function () {
              initKendoUiDialog(2);
          });
    };

     var initKendoUiDialog = function (type) {
        if (type === 1) {
             if (tab_key === "account") {
                var grid = $kGridTab.data("kendoTreeList");
                $export = ErmisKendoDialogTemplate("#export","400px","Export",null,"Export Excel","Export PDF","Close",onExcelTree,onPDFTree);
             }else{
                var grid = $kGridTab.data("kendoGrid");
                 $export = ErmisKendoDialogTemplate("#export","400px","Export",null,"Export Excel","Export PDF","Close",onExcel,onPDF);
             }
           
          } else {
            $import = ErmisKendoDialogTemplate("#import","400px","Import",'<form id="import-form" enctype="multipart/form-data" role="form" method="post"><input name="files" id="files" type="file" /></form>','Import File','Download File',"Close",onImportFile,onDownloadFile);
            ErmisKendoUploadTemplate("#files", false);
          }
        function onImportFile(e) {
          var arr = {};
          arr.action = 'import';
          arr.com = Chat.com;
          arr.key = Ermis.link;
          if(tab_key === "materials" || tab_key === "tools" || tab_key === "goods" || tab_key == "upfront_costs" || tab_key == "assets" || tab_key == "finished_product"){
                arr.stock = jQuery("#stock").val();
            }
          arr.type = tab_key;
          ErmisTemplateAjaxPostAdd3(e,'#import-form',Ermis.link+'-import',arr,
         function(){},
         function(){},
         function(results){
           kendo.alert(results.message);
         });
        }
        function onDownloadFile(e) {
            var url = Ermis.link+'-DownloadExcel?type='+tab_key;
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
            kendo.ui.progress($kGridTab, false);
            grid.saveAsPDF();
        }

        function onExcelTree(e) {
          grid.setOptions({
              excel: {
                  allPages: true,
                  fileName: "Export.xlsx"
              }
            })
            grid.saveAsExcel();
        }
        function onPDFTree(e) {
          grid.setOptions({
            pdf: {
                 allPages: true,
                 fileName: "Export.pdf",
                 landscape: true
             },
           });
           // hide loading indicator
            kendo.ui.progress($kGridTab, false);
            grid.saveAsPDF();
        }

    };

        var initSaveComplete = function(rd){
            if(rd.type === "account"){
                var grid = $kGridTab.data("kendoTreeList");
                initEdit(rd.arr,grid,Ermis.columns_account);            
            }else{
                var columns_edit = '';
                if(rd.type === "bank"){
                    columns_edit = Ermis.columns_bank;
                }else if(rd.type === "materials" || rd.type === "tools" || rd.type === "goods" || rd.type === "upfront_costs" || rd.type === "assets" || rd.type === "finished_product"){
                    columns_edit = Ermis.columns_supplies_goods;
                }else if(rd.type === "suppliers" || rd.type === "customers" || rd.type === "employees" || rd.type === "others" ){
                    columns_edit = Ermis.columns_object;
                }
                var grid = $kGridTab.data("kendoGrid");
                initEdit(rd.arr,grid,columns_edit);
            }           
        }

        var initCancel = function () {
             if (tab_key === "account") {
                 var grid = $kGridTab.data("kendoTreeList");
                 var ds = new kendo.data.TreeListDataSource({
                        transport: {
                            read: {
                                url:  Ermis.link+'-data?type='+tab_key,
                                dataType: "json",
                            },       
                        },
                        schema: {
                            model: {
                                id: "id",
                                parentId: parent_id_tree,
                                fields: Ermis.fields_account,
                                expanded: true
                            },
                        },
                        aggregate: Ermis.aggregates,
                        change: onChangeTreeList
                    });
                    grid.setDataSource(ds);
                 }else{
                    var url_default = Ermis.link+'-data?type='+tab_key;
                    if(tab_key == "materials" || tab_key == "tools" || tab_key == "goods" || tab_key == "upfront_costs" || tab_key == "assets" || tab_key == "finished_product"){
                         var a = jQuery("#stock").val();
                         url_default = url_default+'&stock='+a
                    }
                    var grid = $kGridTab.data("kendoGrid");
                    var ds = new kendo.data.DataSource({
                        transport: {
                            read: {
                                url: url_default,
                                dataType: "json",
                            },       
                        },
                        pageSize: parseInt(Ermis.page_size),      
                        schema: {
                            model: {
                                id: "id",
                                fields: Ermis["fields_"+tab_key]
                            }
                        },
                        aggregate: Ermis.aggregates,
                    });
                    grid.setDataSource(ds);
             }
        }

        var initClientReceive = function(tab_key){      
        Echo.private('data-save-'+Ermis.link+'-'+tab_key+'-'+Chat.com)
          .listen('DataSendCollectionTabs', (rs) => {
              initSaveComplete(rs.data);
          });
        Echo.private('data-import-'+Ermis.link+'-'+tab_key+'-'+Chat.com)
           .listen('DataSendArrayTabs', (rs) => {
               initSaveComplete(rs.data);
           });
        }
        
        var initClientLeave = function(tab_key){
            Echo.leave('data-save-'+Ermis.link+'-'+tab_key+'-'+Chat.com); 
            Echo.leave('data-import-'+Ermis.link+'-'+tab_key+'-'+Chat.com);
        }
   
    

    return {

        init: function () {
            initGetShortKey();      
            initGlobalRegister();
            initStatus(1);
            initClickTab();
        }

    };

}();

jQuery(document).ready(function () {
    Ermis.init();
});
