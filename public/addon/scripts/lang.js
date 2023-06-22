var Language = function () {

    var initLanguage = function () {
        var lang = jQuery('meta[name="locale"]').attr('content');
        Lang.setLocale(lang);
        var $select = jQuery('#lang_switcher').selectize();
        var selectize = $select[0].selectize;
        var arr = [];
        for(let key in Lang.lang_url){
          arr[Lang.lang_url[key]] = key
        };
        selectize.setValue(arr[Lang.current]);
        jQuery('#lang_switcher').on('change', function () {
            var val = jQuery(this).val();
            var lang = Lang.lang_url[val];
            var url = window.location.pathname.replace('/'+Lang.current,lang);
            window.location.href = UrlString(url);
        });
    };

    var initAddClassMenu = function(){
      var subid = jQuery(".current_section").attr('pid');
      if(subid){
        jQuery(".menu_section li[mid='"+subid+"'] a").click();
      }
    };

    var initHideLoading = function(){
      jQuery("#loading").fadeOut('slow');
    };

    return {

        init: function () {
            initLanguage();
            initAddClassMenu();
            initHideLoading();
        }

    };

}();

jQuery(document).ready(function () {
    Language.init();
});
