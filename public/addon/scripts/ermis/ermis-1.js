var Ermis = function () {
    var key = '';
    var data = [];

    var initGetShortKey = function(){
        return key = Ermis.short_key;
    }

    var initStatus = function (flag) {
      shortcut.remove(key + "S");
      shortcut.remove(key + "C");
      if (flag === 1) {//DEFAULT
        shortcut.add(key + "S", function (e) { initSave(e); });
        shortcut.add(key + "C", function (e) { initCancel(e); });
        jQuery('.cancel').on('click', initCancel);
        jQuery('.save').on('click', initSave);
       }
    }

    var initKendoUiContextMenu = function () {
        ErmisKendoContextMenuTemplate("#context-menu", ".md-card-content");
    };


    var initSave = function (e) {
      ErmisTemplateEvent0(e, Ermis.per.e,
     function () {
       var arr = {};
       arr.action = 'save';
       arr.com = Chat.com;
       arr.key = Ermis.link;
       ErmisTemplateAjaxPostAdd1(e,Ermis.elem,Ermis.link+'-change',arr,function(rs){
         kendo.alert(rs.message);
       },function(rs){
         kendo.alert(rs.message);
       })
     },
     function () {
         kendo.alert(Lang.get('messages.you_not_permission_edit'));
     });
    };

    var initCancel = function (e) {
      jQuery('.md-input').each(function(index){
          jQuery(this).val(jQuery(this).attr('value-default'));
      });
    };

    var initClientReceive = function(){
      Echo.private('data-save-'+Ermis.link+'-'+Chat.com)
         .listen('DataSend', (rs) => {
           jQuery.each(rs.data, function (k, v) {
             jQuery("input[name='"+k+"']").attr('value-default',v);
             jQuery("input[name='"+k+"']").val(v);
           })
         });
    };


    return {

        init: function () {
            initGetShortKey();
            initStatus(1);
            initKendoUiContextMenu();
            initClientReceive();
        }

    };

}();

jQuery(document).ready(function () {
    Ermis.init();
});
