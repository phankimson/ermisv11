var Profile = function () {
    var initChangeCard = function () {
        jQuery('#change_user_edit').on('click', function () {
            jQuery('#user_edit_form').show(1000);
            jQuery('#user_edit_form').find('.uk-sticky-placeholder').css('height', jQuery(window).height() * 0.15);
            jQuery("html, body").animate({ scrollTop: 0 }, "slow");
            jQuery('#user_profile').hide(1000);
        })
    }
    var initBack = function () {
        jQuery('#user_edit_back').on('click', function () {
            jQuery('#user_edit_form').hide(1000);
            jQuery("html, body").animate({ scrollTop: 0 }, "slow");
            jQuery('#user_profile').show(1000);
        })
    }
    var initChangeAvatar = function () {
        jQuery("#user_edit_avatar_control").change(function(e){
          ErmisTemplateAjaxPost9(e,this,'avatar','avatar-profile',function(result){
            jQuery(".fileinput").fileinput('reset');
            jQuery(".user_action_image").find(".md-user-image").attr("src",UrlString(result.data.avatar));
            jQuery(".thumbnail img").attr("src",UrlString(result.data.avatar));
            jQuery('#notification').EPosMessage('success', result.message);
          },function(result){
            jQuery('#notification').EPosMessage('error', result.message);
          },function(){

          })
      });
        Echo.private('chat-room-'+Chat.com)
         .listen('UserStatus', (rs) => {
           jQuery(".chat_users").find("li[data-user="+rs.data.id+"] img").attr("src",UrlString(rs.data.avatar));
            })
    }
    var initSave = function () {
        jQuery('#user_edit_save').on('click', function (e) {
            e.preventDefault();
            var tabs = jQuery('#user_edit_tabs_content').find('.uk-active').attr('id');
            if (tabs == 'user_edit_info_content') {
                if (jQuery('.profile-update').valid()) {
                    var data = GetAllValueForm('.profile-update');
                    var postdata = { data: JSON.stringify(data) };
                    ErmisTemplateAjaxPost0(e,postdata,'profile',
                    function(result){
                      jQuery('#notification').EPosMessage('success', result.message);
                    },function(result){
                      jQuery('#notification').EPosMessage('error', result.message);
                    });
                }
            } else {
                if (jQuery('.change-password').valid()) {
                    var data = GetAllValueForm('.change-password');
                    var postdata = { data: JSON.stringify(data) };
                    ErmisTemplateAjaxPost0(e,postdata,'change-password',
                    function(result){
                      jQuery('#notification').EPosMessage('success', result.message);
                      setTimeout(function () { window.location.href = 'index' }, 1500);
                    },function(result){
                      jQuery('#notification').EPosMessage('error', result.message);
                    });
                }
            }

        })
    }
    var initValidate = function () {
        jQuery('.profile-update').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                fullname: {
                    required: true
                },
                firstname: {
                    required: true
                },
                lastname: {
                    required: true
                },
                identity_card: {
                    required: true,
                    number: true
                },
                jobs: {
                    required: true
                },
                about: {
                    required: true
                },
                city: {
                    required: true
                },
                address: {
                    required: true
                },
                birthday: {
                    required: true
                },
                phone: {
                    required: true,
                    number: true
                }
            },

            invalidHandler: function (event, validator) { //display error alert on form submit
                $('.alert-danger', $('.profile-update')).show();
            },

            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.uk-grid').addClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                label.closest('.uk-grid').removeClass('has-error');
                label.remove();
            },

            errorPlacement: function (error, element) {
                if (element.closest('.control-label').size() === 1) {
                    error.insertAfter(element.closest('.control-label'));
                } else {
                    error.insertAfter(element);
                }
            },

            submitHandler: function (form) {
                form.submit(); // form validation success, call ajax form submit
            }
        });

        $('.profile-update').keypress(function (e) {
            if (e.which === 13) {
                if ($('.profile-update').validate().form()) {
                    $('.profile-update').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });

        jQuery('.change-password').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                password: {
                    required: true,
                    minlength: 6
                },
                npassword: {
                    required: true,
                    minlength: 6
                },
                rpassword: {
                    equalTo: "#new_password"
                }

            },

            invalidHandler: function (event, validator) { //display error alert on form submit
                $('.alert-danger', $('.change-password')).show();
            },

            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.uk-grid').addClass('has-error'); // set error class to the control group
            },

            success: function (label) {
                label.closest('.uk-grid').removeClass('has-error');
                label.remove();
            },

            errorPlacement: function (error, element) {
                if (element.closest('.control-label').size() === 1) {
                    error.insertAfter(element.closest('.control-label'));
                } else {
                    error.insertAfter(element);
                }
            },

            submitHandler: function (form) {
                form.submit(); // form validation success, call ajax form submit
            }
        });

        $('.change-password').keypress(function (e) {
            if (e.which === 13) {
                if ($('.change-password').validate().form()) {
                    $('.change-password').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    }

    var initPaginationHistoryAction = function(){
      jQuery('#pagination_history_action').pagination({
          total: Profile.total,
          current: 1,
          length: 10,
          size: 2,
          /**
           * ajax

           * refresh
           * @param  {[object]} options = {
           *      current: options.current,
           *      length: options.length,
           *      total: options.total
           *  }
           * @param  {[function]} refresh
           * @param  {[object]} $target [description]
           * @return {[type]}         [description]
           */
          ajax: function(options, refresh, $target){
          	jQuery.ajax({
          		url: 'load-history-action',
              type : 'POST',
          		data: { data: JSON.stringify({
                      current: options.current,
                      length: options.length
                  }) },
                  dataType: 'json'
          	}).done(function(res){
          		//console.log(res.data);
          		// do something
              jQuery("#pagination_history_action_content .item").remove();
              if(res.status == true){
                jQuery.each(res.data, function (k, v) {
                  var item = jQuery("#pagination_history_action_content li").last().clone(true);
                  item.addClass("item");
                  item.removeAttr("style");
                  if(v.type == 0){
                   item.find("#history_action_title").text(Lang.get('global.signout') +" - "+ Lang.get('global.signout'));
                  }else if(v.type == 1){
                   item.find("#history_action_title").text(Lang.get('global.signin') +" - "+ Lang.get('global.signin'));
                 }else if(v.type == 2){
                   item.find("#history_action_title").text(Lang.getLocale() == 'vi' ? Lang.get('action.add') + " - "+ v.menus.name : Lang.get('action.add') +" - " +v.menus.name_en);
                 }else if(v.type == 3){
                    item.find("#history_action_title").text(Lang.getLocale() == 'vi' ?  Lang.get('action.edit') + " - " +v.menus.name : Lang.get('action.edit') +" - "+ v.menus.name_en);
                  }else if(v.type == 4){
                    item.find("#history_action_title").text(Lang.getLocale() == 'vi' ? Lang.get('action.delete') + " - "+ v.menus.name : Lang.get('action.delete') +" - "+ v.menus.name_en);
                  }else if(v.type == 5){
                    item.find("#history_action_title").text(Lang.getLocale() == 'vi' ? Lang.get('action.import') + " - "+ v.menus.name : Lang.get('action.import') +" - "+ v.menus.name_en);
                  }
                  item.find("#history_action_timer").text(moment(v.created_at).calendar());
                  jQuery("#pagination_history_action_content").append(item);
                });
              }


          		refresh({
          			total: res.total,
          			length: res.length
          		});
          	}).fail(function(error){

          	});
          }
      });
    }

    return {

        init: function () {
            initChangeCard();
            initBack();
            initValidate();
            initSave();
            initChangeAvatar();
            initPaginationHistoryAction();
        }

    };

}();

jQuery(document).ready(function () {
    Profile.init();
});
