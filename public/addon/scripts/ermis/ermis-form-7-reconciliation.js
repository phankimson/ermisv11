// Reconciliation
var Ermis = function () {
    var $kGridTab1 = jQuery('#grid_tab1');
    var $kGridTab2 = jQuery('#grid_tab2');
    var key = '';  
    var grid_header_key_1 = "tab1";
    var grid_header_key_2 = "tab2";
    var myWindow = jQuery("#form-window-get-data");
    var $kWindow = '';
    var $import = '';

    var initGetShortKey = function(){
        return key = Ermis.short_key;
    }

    var initGetColunm = function () {
        data = GetAllDataForm(Ermis.elem);
        return data;
    };

    var initGlobalRegister = function(){
       // KendoWindowTemplate
        $kWindow = ErmisKendoWindowTemplate(myWindow, "1000px", ""); 
        $kWindow.title(Lang.get('action.load'));
       //DroplistTemplate
        jQuery('.droplist.read').each(function() {
            ErmisKendoDroplistReadTemplate(this, "contains");
          }); 
         ErmisKendoDroplistTemplate(".droplist:not(.read)", "contains");  
        //StartEndDroplistTemplate
        ErmisKendoStartEndDroplistTemplate("#start_b","#end_b","dd/MM/yyyy","#fast_date_b","contains");
       //ContextMenu
        ErmisKendoContextMenuTemplate("#context-menu", ".md-card-content");
       // Grid
       ErmisKendoGridCheckboxTemplate4($kGridTab1,Ermis.data,Ermis.aggregate, Ermis.field, jQuery(window).height() * 0.5 ,Ermis.page_size , Ermis.columns_tab1,onChange,onDataBound,"id")
       ErmisKendoGridCheckboxTemplate4($kGridTab2,Ermis.data,Ermis.aggregate, Ermis.field, jQuery(window).height() * 0.5 ,Ermis.page_size , Ermis.columns_tab2,onChange,onDataBound,"ids")
    }

    var onChange = function(data){  
      
    }
    var onDataBound = function(data){  
      
    }

    var initStatus = function(flag) {
        shortcut.remove(key + "L");
        shortcut.remove(key + "I");
        shortcut.remove(key + "C");
      if(flag == 1){
          jQuery('.load').on('click', initLoadForm);
          jQuery('.import').on('click', initImport);
          jQuery('.get_data').on('click', initChoose);
          jQuery('.cancel-window').on('click', initClose);
          btnSearchGrid("#btn_search_tab1",'#search_tab1',Ermis.columns_tab1,$kGridTab1);
          btnSearchGrid("#btn_search_tab2",'#search_tab2',Ermis.columns_tab2,$kGridTab2);
           shortcut.add(key + "L", function(e) {
                initLoadForm(e);
            });
            shortcut.add(key + "I", function(e) {
                initImport(e);
            });
      }
    }

     var btnSearchGrid = function(btn_search_tab,serchValue,columns_tab,$kGrid){
        jQuery(btn_search_tab).on('click', function(){
            var searchValue_tab = jQuery(serchValue).val();
                var filters_tab = [];          
                jQuery.each(columns_tab, function(i, item) {
                    if(item.field){
                    filters_tab.push({
                        field   : item.field,
                        operator: "contains",
                        value   : searchValue_tab
                    });
                    }             
                });
                $kGrid.data("kendoGrid").dataSource.filter({
                    logic  : "or",
                    filters: filters_tab
                });
            })    
        };      

      var initLoadForm = function(e) {
        $kWindow.open();
    };

    
    var initChoose = function(e) {
          var filter = GetAllDataForm('#form-window-get-data', 2);
          var a = initShowValidationColumn(filter);
          if(a.length == 0){
            var c = GetDataAjax(filter.columns, filter.elem);
            var postdata = {
                data: JSON.stringify(c.obj)
            };
            ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-load', function(result) {
            initLoadGrid($kGridTab1,result.data1,Ermis.field,Ermis.aggregate);
            initLoadGrid($kGridTab2,result.data2,Ermis.field,Ermis.aggregate);
            $kWindow.close();
            }, function(result) {
                kendo.alert(result.message);
            });
          }else{
            var mes = Lang.get('messages.please_input_all_field_require');
            jQuery.each(a, function (l, n) { 
                var char = "";
                if(l>0){
                    char = ", ";
                };
               mes = mes+char+n.title;
            })
            kendo.alert(mes);
          }         
      };

      var initClose = function(e) {
        ErmisTemplateEvent1(e, function() {
            if ($kWindow.element.is(":hidden") === false) {
                $kWindow.close();
            }
        });
    };

      var initLoadGrid = function($kGrid,dataLoad,field,aggregate){
          var grid = $kGrid.data("kendoGrid");
          ds = new kendo.data.DataSource({
              data: dataLoad,
              schema: {
                  model: {
                      fields: field,
                      id: "id"
                  }
              },
              aggregate: aggregate
          });
          grid.setDataSource(ds);
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
        $import = ErmisKendoDialogTemplate2("#import","400px","Import",'#template-dialog','Import File',"Close",onImportFile,initOpen);
        ErmisKendoUploadTemplate("#files", false);   
        function onImportFile(e) {
          var arr = {};
          arr.action = 'import';
          arr.com = Chat.com;
          arr.key = Ermis.link;
          arr.crit = jQuery(Ermis.crit).data("kendoDropDownList").value();
          if(arr.crit !="0"){
             ErmisTemplateAjaxPostAdd3(e,'#import-form',Ermis.link+'-import',arr,
                function(results){
                kendo.alert(results.message);
                },
                function(){},
                function(results){
                kendo.alert(results.message);
            });  
          }else{
            kendo.alert(Lang.get('messages.please_input_all_field_require'));
          }              
         } 
        function initOpen(e) {
            jQuery('.droplist.read').each(function() {
            ErmisKendoDroplistReadTemplate(this, "contains");
        });  
        }       
    };

    

    return {

        init: function () {
            initGetShortKey();
            initGetColunm();        
            initGlobalRegister();
            initStatus(1);
        }

    };

}();

jQuery(document).ready(function () {
    Ermis.init();
});
