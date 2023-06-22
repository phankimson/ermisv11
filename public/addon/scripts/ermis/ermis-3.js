var Ermis = function () {
    var keys = [];
    var group_user = 0 ;

    var permission_key = function(){
      keys = convertKeyJsontoArr(Ermis.per);
      return keys;
    }

    var initGetShortKey = function(){
        return key = Ermis.short_key;
    }

    var initStatus = function (flag) {
      shortcut.remove(key + "S");
      shortcut.remove(key + "C");
      if (flag === 1) {//DEFAULT
        shortcut.add(key + "S", function (e) { btnSave(e); });
        shortcut.add(key + "C", function (e) { btnCancel(e); });

        jQuery("select, input:checkbox, input:radio, input:file").uniform();
        jQuery('.group_click').on('click', group_click);
        jQuery('.checkbox-group').on('click', checkbox_group);
        jQuery('.all').on('click', checkbox_all);
        jQuery('.save').on('click', btnSave);
        jQuery('.cancel').on('click', btnCancel);
        jQuery('#user').on('change', change_user);
        jQuery('#group').on('change', change_group);
       }
    }

    var group_click = function (e) {
        var group = jQuery(this).attr('data-group');
        if (jQuery(this).hasClass('group-hide')) {
            jQuery(this).find('i').html("remove");
            jQuery(this).removeClass('group-hide');
            jQuery('.item[data-group="' + group + '"]').show();
        } else {
            jQuery(this).addClass('group-hide');
            jQuery(this).find('i').html("add");
            jQuery('.item[data-group="' + group + '"]').hide();
        }
    };
    var checkbox_group = function (e) {
        var group = jQuery(this).attr('name');
        if (jQuery(this).parent().hasClass('checked')) {
            jQuery('input[data-group="' + group + '"]').parent().addClass('checked');
        } else {
            jQuery('input[data-group="' + group + '"]').parent().removeClass('checked');
        }
    };
    var load_checkbox_group = function (e) {
        jQuery('.checkbox-group').each(function (e) {
            var name = jQuery(this).attr('name');
            var i = true;
            jQuery('input[data-group="' + name + '"]').each(function (e) {
                if (!jQuery(this).parent().hasClass('checked')) {
                    i = false;
                    return false;
                }
            });
            if (i === true) {
                jQuery(this).parent().addClass('checked');
            } else {
                jQuery(this).parent().removeClass('checked');
            }
        });
    }
    var checkbox_all = function (e) {
        var group = jQuery(this).attr('name');
        if (jQuery(this).parent().hasClass('checked')) {
            jQuery('input[data-group^="' + group + '"]').parent().addClass('checked');
            jQuery('input[name^="' + group + '"]').parent().addClass('checked');
        } else {
            jQuery('input[data-group^="' + group + '"]').parent().removeClass('checked');
            jQuery('input[name^="' + group + '"]').parent().removeClass('checked');
        }
    };
    var load_checkbox_all = function (e) {
        jQuery('.all').each(function (e) {
            var name = jQuery(this).attr('name');
            var i = true;
            jQuery('input[name^="' + name + '"]').not('#accordion_mode_main_menu').not('.filter').not('.all').each(function (e) {
                if (!jQuery(this).parent().hasClass('checked')) {
                    i = false;
                    return false;
                }
            });
            if (i === true) {
                jQuery(this).parent().addClass('checked');
            } else {
                jQuery(this).parent().removeClass('checked');
            }
        });
    };
    var change_user = function (e) {
        group_user = 0;
        jQuery('#uniform-group span').text(Lang.get('global.select')+"...");
        jQuery("#group option[value='']").prop("selected",true);
        var id = jQuery(this).val();
        var postdata = { data: JSON.stringify(id) };
        RequestURLWaiting(Ermis.link+'-load', 'json', postdata, function (data) {
            if (data.status === true) {
                //                    jQuery('#notification').EPosMessage('success',data.message);
                jQuery("input[type='checkbox']").parent().removeClass('checked');
                check_checkbox(data.data);
                load_checkbox_group();
                load_checkbox_all();
            } else {
               jQuery("input[type='checkbox']").parent().removeClass('checked');
                //                    jQuery('#notification').EPosMessage('error', data.message);
            }
        }, true);
    };

    var change_group = function (e) {
        group_user = 1;
        jQuery('#uniform-user span').text(Lang.get('global.select')+"...");
        jQuery("#user option[value='']").prop("selected",true);
        var id = jQuery(this).val();
        var postdata = { data: JSON.stringify(id) };
        RequestURLWaiting(Ermis.link+'-group', 'json', postdata, function (data) {
            if (data.status === true) {
                //                    jQuery('#notification').EPosMessage('success',data.message);
                jQuery("input[type='checkbox']").parent().removeClass('checked');
                check_checkbox(data.data);
                load_checkbox_group();
                load_checkbox_all();
            } else {
               jQuery("input[type='checkbox']").parent().removeClass('checked');
                //                    jQuery('#notification').EPosMessage('error', data.message);
            }
        }, true);
    };

    var check_checkbox = function (data) {
        jQuery('tr').removeAttr('id');
        if (data.length>0) {
            jQuery.each(data, function (key, value) {
                jQuery.each(keys, function (k, v) {
                    if (value[v] === true) {
                        jQuery('input[name="' + v +'-'+value.menu + '"]').parent().addClass('checked');
                    } else {
                        jQuery('input[name="' + v +'-'+ value.menu + '"]').parent().removeClass('checked');
                    }
                })
                jQuery('tr[data-id="' + value.menu + '"]').attr('id', value.id);
            });
        } else {
            jQuery('input:checkbox').parent().removeClass('checked');
        }
    };
    //var load_id = function (data) {
    //    if (data) {
    //        jQuery.each(data, function (key, value) {
    //            jQuery('tr[data-id="' + value + '"]').attr('id', key);
    //        });
    //    }
    //};
    var btnSave = function (e) {
        var jQuerylink = jQuery(e.target);
        e.preventDefault();
        if (!jQuerylink.data('lockedAt') || +new Date() - jQuerylink.data('lockedAt') > 300) {
            if (Ermis.per.e) {
            $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('global.message'))).then(function (confirmed) {

                if (confirmed) {
                    var user = 0;
                    if(group_user == 1){
                      user = jQuery('#group').val();
                    }else{
                      user = jQuery('#user').val();
                    }
                    if (user !=="") {
                        var aryData = [];
                        jQuery('.uk-table tr.item').each(function (i, obj) {
                            var id = jQuery(this).attr('id');
                            if (!id) {
                                id = null;
                            }
                            var menu = jQuery(this).attr('data-id');
                            var items = jQuery(this).find('.checkbox-item');
                            var permission = 0;
                            jQuery.each(items, function (y,o) {
                                var name = jQuery(this).attr('name').split('-', 1)[0];
                                if (jQuery(this).parent().hasClass('checked')) {
                                        permission += Math.pow(2, y);
                                }
                            });
                                aryData.push({ "permission": permission, "menu": menu, "user": user, "id": id, "group_user": group_user });
                        });
                        var postdata = { data: JSON.stringify(aryData) };
                        RequestURLWaiting(Ermis.link+'-save', 'json', postdata, function (data) {
                            if (data.status === true) {
                                jQuery('#notification').EPosMessage('success', data.message);
                            } else {
                                jQuery('#notification').EPosMessage('error', data.message);
                            }
                        }, true);
                    } else {
                        jQuery('#notification').EPosMessage('error', Lang.get('messages.please_choose_user'));
                    }
                }
            });
            } else {
                kendo.alert(Lang.get('messages.you_not_permission_edit'));
            }
        }
        jQuerylink.data('lockedAt', +new Date());
    }

    var btnCancel = function(e){
      var jQuerylink = jQuery(e.target);
      e.preventDefault();
      if (!jQuerylink.data('lockedAt') || +new Date() - jQuerylink.data('lockedAt') > 300) {
                var id = jQuery("#user").val();
                var postdata = { data: JSON.stringify(id) };
                var link = '-load'
                if(group_user == 1){
                  link = '-group'
                }
                RequestURLWaiting(Ermis.link+link, 'json', postdata, function (data) {
                    if (data.status === true) {
                        //                    jQuery('#notification').EPosMessage('success',data.message);
                        check_checkbox(data.data);
                        load_checkbox_group();
                        load_checkbox_all();
                    } else {
                       jQuery("input[type='checkbox']").parent().removeClass('checked');
                        //                    jQuery('#notification').EPosMessage('error', data.message);
                    }
                }, true);
      }
      jQuerylink.data('lockedAt', +new Date());
    }

    return {
        //main function to initiate the module
        init: function () {
            initGetShortKey();
            permission_key();
            initStatus(1);
        }
    };
}();

jQuery(document).ready(function () {
    Ermis.init();
});
