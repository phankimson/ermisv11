// Reconciliation
var Ermis = function () {
    var $kGridTab1 = jQuery('#grid_tab1');
    var $kGridTab2 = jQuery('#grid_tab2');
    var key = '';  
    var grid_header_key_1 = "tab1";
    var grid_header_key_2 = "tab2";
    var myWindow = jQuery("#form-window-get-data");
    var $kWindow = '';

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
        ErmisKendoGridCheckboxTemplate3($kGridTab1, Ermis.data, Ermis.aggregate, Ermis.field, Ermis.page_size , {
            confirmation: false
        }, jQuery(window).height() * 0.5, Ermis.columns_tab1,onSave,grid_header_key_1,onChecked,"id");       

        ErmisKendoGridCheckboxTemplate3($kGridTab2, Ermis.data, Ermis.aggregate, Ermis.field, Ermis.page_size , {
            confirmation: false
        }, jQuery(window).height() * 0.5, Ermis.columns_tab2,onSave,grid_header_key_2,onChecked,"id");  
    }

     var onChecked = function(t,dataItem){        
        if(t == 0){
            dataItem.set("checkbox", "");       
        }else{
            dataItem.set("checkbox", "checked");              
        };   
    }

     var onSave = function(data){  
        var grid = this;
            setTimeout(function() {
                grid.refresh();
            });        
    }

    var initStatus = function(flag) {
        shortcut.remove(key + "L");
        shortcut.remove(key + "I");
        shortcut.remove(key + "C");
      if(flag == 1){
         jQuery('.load').on('click', initLoadForm);
          jQuery('.get_data').on('click', initChoose);
          jQuery('.cancel-window').on('click', initClose);
          jQuery("#btn_search_tab1").on('click', btnSearchGrid('#search_tab1',Ermis.columns_tab1,$kGridTab1));
          jQuery("#btn_search_tab2").on('click', btnSearchGrid('#search_tab2',Ermis.columns_tab2,$kGridTab2));
      }
    }

     var btnSearchGrid = function(serchValue,columns_tab,$kGrid){
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
        };      

      var initLoadForm = function() {
        $kWindow.open();
    };

    
    var initChoose = function(e) {
          var filter = GetAllDataForm('#form-window-get-data', 2);
          var c = GetDataAjax(filter.columns, filter.elem);
          var postdata = {
              data: JSON.stringify(c.obj)
          };
          ErmisTemplateAjaxPost0(e, postdata, Ermis.link + '-load', function(result) {
           initLoadGrid($kGridTab1,result.data1,Ermis.field,Ermis.aggregate);
           $kWindow.close();
          }, function(result) {
              kendo.alert(result.message);
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


    var initClose = function(e) {
        ErmisTemplateEvent1(e, function() {
            if ($kWindow.element.is(":hidden") === false) {
                $kWindow.close();
            }
        });
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
