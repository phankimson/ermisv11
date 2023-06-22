var Ermis = function () {
    var $kGrid = jQuery('#grid');
    var key = '';
    var data = [];
    var dataId = '';

    var initGetShortKey = function(){
        return key = Ermis.short_key;
    }

    var initGetColunm = function () {
        data = GetAllDataForm(Ermis.elem);
        return data;
    };
    var initMonthDate = function () {
      ErmisKendoMonthPickerTemplate(".month-picker","year","year","MM/yyyy");
    }

    var initStatus = function(){
      jQuery('.save').on('click', initSave);
      jQuery('.delete').on('click', initDelete);
      shortcut.add(key + "S", function (e) { initSave(e); });
      shortcut.add(key + "D", function (e) { initDelete(e); });
    }


    var onChange = function () {
        var grid = this;
        var dataItem = grid.dataItem(grid.select());
        dataId = dataItem.id;
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
          var dataItem = grid.dataItem("tbody tr:eq(0)");
          if(dataItem != undefined){
            rd['row_number'] = dataItem['row_number']+1;
          }else{
            rd['row_number'] = 1;
          }
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

            if(col.key == 'select'){
              jQuery(',dropdown').data("kendoDropDownList").value(0);
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
          jQuery('#notification').EPosMessage('success', result.message);
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

    var initDelete = function (e) {
      ErmisTemplateEvent0(e, Ermis.per.d,
              function () {
                if ($kGrid.find('.k-state-selected').length > 0) {
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
                                },
                                function (result) {
                                    jQuery('#notification').EPosMessage('error', result.message);
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


    var initKendoUiGridView = function () {
      ErmisKendoGridTemplate0($kGrid, Ermis.page_size , Ermis.data, onChange, "row", jQuery(window).height() * 0.75, {numeric: false, previousNext: false},[], Ermis.columns);
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
    }

    return {

        init: function () {
            initStatus();
            initGetColunm();
            initMonthDate();
            initKendoUiGridView();
            initClientReceive();
        }

    };

}();
jQuery(document).ready(function () {
    Ermis.init();
});
