var ChatForm = function () {
    var myWindow = jQuery('#event_content');
    var $kWindow = '';
    var initKendoButtonAddEvent = function(){
        ErmisKendoButtonTemplate("#add-event","share");
    };
    var initKendoEditor = function(){
      ErmisKendoEditorFullTemplate("#editor");
    };

    var initWindowEvent = function(){
      $kWindow = ErmisKendoWindowTemplate(myWindow,"650px","Thêm sự kiện");
    };
    var initOpenWindowEvent = function(){
       jQuery('#add-event').on("click",function(e){
        var jQuerylink = jQuery(e.target);
        e.preventDefault();
        if (!jQuerylink.data('lockedAt') || +new Date() - jQuerylink.data('lockedAt') > 300) {
           myWindow.data("kendoWindow").open();
        }
        jQuerylink.data('lockedAt', +new Date());
       });
    };
     var initCancelEvent = function(){
       jQuery('.cancel-event').on("click",function(e){
        var jQuerylink = jQuery(e.target);
        e.preventDefault();
        if (!jQuerylink.data('lockedAt') || +new Date() - jQuerylink.data('lockedAt') > 300) {
           myWindow.data("kendoWindow").close();
        }
        jQuerylink.data('lockedAt', +new Date());
       });
    };
    var initComboboxTypeAction = function(){
        var data = [
            { text: "Kiểu 1", value: "1" },
            { text: "Kiểu 2", value: "2" },
            { text: "Kiểu 3", value: "3" },
            { text: "Kiểu 4", value: "4" },
            { text: "Kiểu 5", value: "5" }
    ];
        ErmisKendoComboboxTemplate("#action-event","text","value","contains",data)
    };
    return {

        init: function () {
            initWindowEvent();
            initOpenWindowEvent();
            initKendoButtonAddEvent();
            initKendoEditor();
            initCancelEvent();
            initComboboxTypeAction();
        }

    };

}();

jQuery(document).ready(function () {
    ChatForm.init();
});
