var Index = function () {
    var initKendoTabStrip = function () {
        jQuery("#tabstrip").kendoTabStrip();
    }
    var initKendoDropDownList = function () {
        jQuery("#language").kendoDropDownList({
            dataValueField: "value",
            change: onChange
        });
    }

    //$("#language").data("kendoDropDownList").trigger("change");
        var onChange = function() {
            var value = $("#language").val();
            document.location.href = value;
        };
        var initHideLoading = function(){
          jQuery("#loading").fadeOut('slow');
        }

    return {

        init: function () {
            initKendoTabStrip();
            initKendoDropDownList();
            initHideLoading();
        }

    };

}();

jQuery(document).ready(function () {
    Index.init();
});
