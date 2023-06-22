var Login = function () {
    jQuery('input[name="username"],input[name="password"],.login_page,.g-recaptcha').keypress(function (e) {
        if (e.which === 13) {
            btnLogin(e);
            return false;    //<---- Add this line
        }
    });

    var btnLogin = function (e) {
        ErmisTemplateAjaxPost1(e,'.login-form','login',
        function(){
          if(Login.url_back.indexOf("login")>0 || Login.url_back.indexOf("block")>0 || Login.url_back.indexOf(Login.manage)<0)
          {
              window.location.href = 'index';
          }else{
              window.location.href = Login.url_back;
          }

        },
        function(result){
            jQuery('#notification').EPosMessage('error', result.message);
        }
      );
    };
    var btnEmail = function (e) {
        ErmisTemplateAjaxPost1(e,'.forget-form','email/reset',
        function(result){
            jQuery('#notification').EPosMessage('success', result.message);
        },
        function(result){
            jQuery('#notification').EPosMessage('error', result.message);
        }
      );
    };

    var initKendoUiDropList = function () {
       if(jQuery(".droplist").length > 0){
           ErmisKendoDroplistTemplate(".droplist", "contains");
       }
    };

    return {

        init: function () {
            jQuery('#button_login').on('click ', btnLogin);
            jQuery('#button_email').on('click ', btnEmail);
            initKendoUiDropList();
        }

    };

}();

jQuery(document).ready(function () {
    Login.init();
});
