// Balance
var Ermis = function () {
    var $kGridTab = '';   
    var key = '';    
    var data = '';  
    var $kWindow = '';
    var $import = '';

    var initGetShortKey = function(){
        return key = Ermis.short_key;
    }

    var initGlobalRegister = function(){
      
       
    }

    var initStatus = function(flag) {
        shortcut.remove(key + "S");
        shortcut.remove(key + "C");
        shortcut.remove(key + "L");
        shortcut.remove(key + "I"); 
        jQuery('.import,.save,.cancel,.export,.export_extra').addClass('disabled');
      if(flag == 1){//Default
        jQuery('.import,.save,.cancel,.export,.export_extra').removeClass('disabled');
           
      }else if(flag == 2){
        
      }else if(flag == 3){
       
      }
    }

    var initClickTab = function(){
       jQuery('#tabs_li').on('change.uk.tab', function(e, active, previous) {
                var tab = active.index();
                $kGridTab = jQuery("#grid_tab"+(tab+1));
                if($kGridTab.data("kendoTreeList") === undefined && tab ==0){
                    ErmisKendoTreeViewApiTemplate1($kGridTab, Ermis.link+'-data', "parent_id", true, "incell",onChangeTreeList, jQuery(window).height() * 0.75, true, Ermis.fields_account, Ermis.columns_account,Ermis.aggregates_account);    
                    jQuery("tr.k-footer-template").addClass("hidden");       
                }
            });
    }    
      var onChangeTreeList = function (e) {
            if(e.items.length == 1){
              var treeList = $kGridTab.data("kendoTreeList");
              var item = e.items[0];
              var parentId =  item.parentId;
              var parentDataItem = treeList.dataSource.get(parentId);  
              var aggregates = treeList.dataSource.aggregates();
              var aggregate = aggregates[parentId];
               parentDataItem.set(item.dirtyFields,aggregate[item.dirtyFields]);
            }            
        };    
   
    

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
