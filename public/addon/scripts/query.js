var Ermis = function() {
  var myWindow = jQuery("#form-action");
  var $kWindow = '';
  var key = 'Alt+';
  var $kGrid = jQuery('#grid');
  var initStatus = function (flag) {
    if (flag === 1) {//DEFAULT
        $kWindow.title(Lang.get('action.query'));
        $kWindow.center().open();
        shortcut.add(key + "Q", function (e) { btnStart(e); });
        shortcut.add(key + "C", function (e) { initCancel(e); });
        shortcut.add(key + "S", function (e) { initShow(e); });
        jQuery('.cancel').on('click', initCancel);
        jQuery('.query').on('click ', btnStart);
        jQuery('.cancel').on('click', initCancel);
        jQuery('.clear').on('click ', btnClear);
        jQuery('.select').on('click ', btnSelect);
        jQuery('.insert').on('click ', btnInsert);
        jQuery('.update').on('click ', btnUpdate);
        jQuery('.delete').on('click ', btnDelete);
        jQuery('.truncate').on('click ', btnTruncate);
        jQuery('.show').on('click' , initShow);
    }
  }

  var initShow = function(e) {
    $kWindow.center().open();
  }

  var initCancel = function (e) {
    ErmisTemplateEvent1(e, function () {
          $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('global.message'))).then(function (confirmed) {
            if (confirmed) {
              myWindow.data("kendoWindow").close();
            }
        });
    });
  };

  var initKendoUiWindow = function () {
      function onClose() {
          initStatus(4);
      }
      $kWindow = ErmisKendoWindowTemplate1(myWindow, jQuery(window).width() * 0.75, "",onClose);
  };

      var btnStart = function(e){
        e.preventDefault();
          if(jQuery('textarea[name="query"]').val() != ''){
          var data = GetAllValueForm('.query-form');
          var postdata = {data : JSON.stringify(data)};
              RequestURLWaiting('query','json',postdata,function(result){
                  if(result.status == true){
                      jQuery('#notification').EPosMessage('success', result.message);
                         $kGrid.kendoGrid({
                           dataSource: { data: result.data,pageSize: 10},
                           pageable: true,
                         });
                         myWindow.data("kendoWindow").close();
                  }else{
                      jQuery('#notification').EPosMessage('error', result.message);
                  }
              },true);
        }else{
              jQuery('#notification').EPosMessage('error', Lang.get('messages.please_fill_field'));
        }
    };

    var initKendoUiDropList = function () {
        ErmisKendoDroplistTemplate1(".database", "contains",initKendoUiChangeDB);
    };

    var initKendoUiChangeDB = function (e) {
      ErmisTemplateAjaxPost1(e,"#form_select",Ermis.link+'-change-database',
          function(result){
            kendo.alert(result.message);  
          },
          function(result){
            kendo.alert(result.message);
          }
      )
    };

       var btnClear = function(e){
           jQuery('.table-scrollable').addClass('hidden');
           jQuery('textarea[name="query"]').val('');
       };

       var btnSelect = function(e){
           jQuery('textarea[name="query"]').val('select * from [table]');
       };

       var btnInsert = function(e){
           jQuery('textarea[name="query"]').val('insert into [table] (column1, column2, column3,...) values (value1, value2, value3,...)');
       };

       var btnUpdate = function(e){
           jQuery('textarea[name="query"]').val('UPDATE [table] SET column1=value, column2=value2,... WHERE some_column=some_value ');
       };

       var btnDelete = function(e){
           jQuery('textarea[name="query"]').val('DELETE FROM [table] WHERE some_column = some_value');
       };

       var btnTruncate = function(e){
           jQuery('textarea[name="query"]').val('truncate [table]');
       }

    return {
        //main function to initiate the module
        init: function() {
            initKendoUiWindow();
            initKendoUiDropList();
            initStatus(1);
        }

    };

}();

jQuery(document).ready(function() {
    Ermis.init();
});
