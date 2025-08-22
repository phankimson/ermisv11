// Balance
var Ermis = function () {
    var $kGridTab = '';   
    var tab_key = '';  
    var key = '';
    var parent_id_tree = "parent_id";
    var data = '';  
    var $kWindow = '';
    var $import = '';

    var initGetShortKey = function(){
        return key = Ermis.short_key;
    }

    var initGlobalRegister = function(){
      
       
    }

    var initStatus = function(flag) {
      if(flag == 1){//Default
        jQuery(".save").on("click", initSave);  
        jQuery('.cancel').on('click', initCancel);
        shortcut.add(key + "S", function (e) { initSave(e); });
        shortcut.add(key + "C", function (e) { initCancel(e); }); 
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
                };
                tab_key = active.attr("data-key");
                $kGridTab = jQuery("#grid_tab"+(tab+1));
                if($kGridTab.data("kendoTreeList") === undefined && tab_key == "account"){
                    ErmisKendoTreeViewApiTemplate1($kGridTab, Ermis.link+'-data-'+tab_key, parent_id_tree, true, "incell",onChangeTreeList,onDataBoundTreeList, jQuery(window).height() * 0.75, true, Ermis.fields_account, Ermis.columns_account,Ermis.aggregates_account); 
                }else if($kGridTab.data("kendoGrid") === undefined && tab_key == "bank"){
                    ErmisKendoGridTemplateApi1($kGridTab, Ermis.page_size , Ermis.link+'-data-'+tab_key, onChangeGrid, false , jQuery(window).height() * 0.75, {
                        numeric: false,
                        previousNext: false
                    }, true ,  Ermis.fields_bank, Ermis.columns_bank,Ermis.aggregates_account);
                }else{

                }
                initClientReceive(tab_key);
            });
    }   
        var onChangeGrid = function(){
            
        }

      var onDataBoundTreeList = function(){
        if(!$kGridTab.find("tr.k-footer-template:first").hasClass("hidden")){
          $kGridTab.find("tr.k-footer-template:not(:last)").addClass("hidden");  
        };       
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
                    parentDataItem.set(field,aggregate[field]["sum"]);
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
                    aggregate_total[field]["sum"] = total;
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

        var initSaveComplete = function(rd){
            if(rd.type === "account"){
                var grid = $kGridTab.data("kendoTreeList");
                initEdit(rd.arr,grid,Ermis.columns_account);
            }else{

            }           
        }

        var initCancel = function () {
             if (tab_key === "account") {
                 var grid = $kGridTab.data("kendoTreeList");
                 var ds = new kendo.data.TreeListDataSource({
                        transport: {
                            read: {
                                url: Ermis.link+'-data-'+tab_key,
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
                        aggregate: Ermis.aggregates_account,
                        change: onChangeTreeList
                    });
                    grid.setDataSource(ds);
                 }else if(tab_key === "bank"){
                    var grid = $kGridTab.data("kendoGrid");
                    var ds = new kendo.data.DataSource({
                        transport: {
                            read: {
                                url: Ermis.link+'-data-'+tab_key,
                                dataType: "json",
                            },       
                        },
                        pageSize: parseInt(Ermis.page_size),      
                        schema: {
                            model: {
                                id: "id",
                                fields: Ermis.fields_bank
                            }
                        },
                        aggregate: Ermis.aggregates_account,
                    });
                    grid.setDataSource(ds);
             }else{

             }
        }

        var initClientReceive = function(tab_key){      
        Echo.private('data-save-'+Ermis.link+'-'+tab_key+'-'+Chat.com)
          .listen('DataSendCollectionTabs', (rs) => {
              initSaveComplete(rs.data);
          });
        Echo.private('data-import-'+Ermis.link+'-'+tab_key+'-'+Chat.com)
           .listen('DataSendCollectionTabs', (rs) => {
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
