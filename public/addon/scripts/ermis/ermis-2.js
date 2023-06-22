var Ermis = function () {
    var key = '';
    var dataId = '';
    var k = 1;
    var $card_note = jQuery(".md-card-single");
    var data = [];

    var initGetShortKey = function(){
        return key = Ermis.short_key;
    }

    var initGetColunm = function () {
    data = GetAllDataForm('#form-action');
    return data;
};

    var t = function(){};

    var initStatus = function (flag) {
      shortcut.remove(key + "S");
      shortcut.remove(key + "A");
      shortcut.remove(key + "D");
      jQuery('.add,.save,.delete').off('click');
      if (flag === 1) {//DEFAULT
        shortcut.add(key + "A", function (e) { initAdd(e); });
        shortcut.add(key + "S", function (e) { initSave(e); });
        jQuery('.add').on('click', initAdd);
        jQuery('.save').on('click', initSave);
        jQuery('.delete').addClass('disabled');
        jQuery('.delete').off('click');
      }else if( flag === 2 ) { // ADD
        jQuery('.save').removeClass('disabled');
        shortcut.add(key + "S", function (e) { initSave(e); });
        shortcut.add(key + "A", function (e) { initAdd(e); });
        jQuery('.add').on('click', initAdd);
        jQuery('.save').on('click', initSave);
        jQuery('input').not('[type=radio]').val("");
        jQuery('textarea').val("");
        jQuery('.delete').addClass('disabled');
        jQuery('.delete').off('click');
        jQuery('.switchery').prop('checked', true).trigger("click");
      }else if ( flag === 3 ){// EDIT - COPY
        jQuery('.save,.add,.delete').removeClass('disabled');
        shortcut.add(key + "D", function (e) { initDelete(e); });
        shortcut.add(key + "S", function (e) { initSave(e); });
        shortcut.add(key + "A", function (e) { initAdd(e); });
        jQuery('.save').on('click', initSave);
        jQuery('.add').on('click', initAdd);
        jQuery('.delete').on('click', initDelete);
      }else if ( flag === 4 ){// CANCEL , SAVE

      }
    }

    var initAdd = function(e){
        ErmisTemplateEvent0(e, Ermis.per.a,
        function () {
            altair_md.card_show_hide($card_note, void 0, t, void 0);
            dataId = null;
            initStatus(2);
        },
        function () {
            kendo.alert(Lang.get('messages.you_not_permission_add'));
        });
    }

    var initSave = function(e){
      ErmisTemplateEvent0(e, Ermis.per.e,
       function () {
         if(jQuery('.not-null').val() !=""){
           var arr = GetAllValueForm(Ermis.elem);
           arr.id = dataId;
           arr.action = 'save';
           arr.com = Chat.com;
           arr.key = Ermis.link;
           var postdata = { data: JSON.stringify(arr) };
           ErmisTemplateAjaxPost0(e,postdata,Ermis.link+'-save',function(rs){
             kendo.alert(rs.message);
             altair_md.card_show_hide($card_note, void 0, t, void 0);
             dataId = null;
             initStatus(2);
           },function(rs){
             kendo.alert(rs.message);
           })
         }else{
             kendo.alert(Lang.get('messages.please_fill_field'));
         }
       },
       function () {
         if(dataId){
           kendo.alert(Lang.get('messages.you_not_permission_edit'));
         }else{
           kendo.alert(Lang.get('messages.you_not_permission_add'));
         }
       });
    }

    var initDelete = function(e){
      ErmisTemplateEvent0(e, Ermis.per.d,
        function (){
          $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('global.message'))).then(function (confirmed) {
                       if (confirmed) {
                         if(dataId){
                           var arr = {};
                           arr.id = dataId;
                           arr.action = 'delete';
                           arr.com = Chat.com;
                           arr.key = Ermis.link;
                           var postdata = { data: JSON.stringify(arr) };
                           ErmisTemplateAjaxPost0(e,postdata,Ermis.link+'-delete',function(rs){
                             kendo.alert(rs.message);
                             altair_md.card_show_hide($card_note, void 0, t, void 0);
                             dataId = null;
                             initStatus(2);
                           },function(rs){
                             kendo.alert(rs.message);
                           })
                         }else{
                            kendo.alert(Lang.get('messages.please_choose_item'));
                         }

                       }
                   });
        },
       function () {
           kendo.alert(Lang.get('messages.you_not_permission_delete'));
       });

    }

    var onChange = function(e){
      jQuery(".note_link").on("click",function(e){
          initStatus(3);
          dataId = jQuery(this).attr("data-note-id");
          jQuery(this).parents("#notes_list").find('li').removeClass("md-list-item-active");
          jQuery(this).parent("li").addClass("md-list-item-active");
          var postdata = { data: JSON.stringify(dataId) };
          ErmisTemplateAjaxPost0(e, postdata ,Ermis.link+'-load' ,function(rs){
                    jQuery.each(data.columns, function (k, col) {
                      if (col.key === 'textarea') {
                        jQuery(col.key+'[name="' + col.field + '"]').val(rs.data[col.field]);
                      }else if( col.key === 'text'){
                        jQuery('input[name="' + col.field + '"]').val(rs.data[col.field]);
                      }else if(col.key === 'checkbox'){
                        var mySwitch =  jQuery('.switchery[name="' + col.field + '"]');
                        if(rs.data[col.field] == 1){
                          mySwitch.prop('checked', true).trigger("click");
                        }else{
                          mySwitch.prop('checked', false).trigger("click");
                        }
                      }
                    });
              altair_md.card_show_hide($card_note, void 0, t, void 0);
          },function(rs){
            kendo.alert(rs.message);
          })
      })
    }

    var initLoadChatUserScroll = function(){
      $(".scrollbar-inner").on("scroll",function(e){
        e.preventDefault();
        if(jQuery(".chat_box_active").length > 0){
          var a = $(this).scrollTop();
          var c = $(this).height();
          var b = $(this)[0].scrollHeight;
          if( a + c == b && crit_load == false && a >0){
           ErmisTemplateAjaxPost0(e,k,Ermis.link+'-view-more',
           function(result){
             jQuery.each(result.data, function (k, v) {
               k++
             })
           },function(result){
             crit_load = true;
             kendo.alert(result.message);
           })

          }
        }

    });
  };

    var initClientReceive = function(){
    Echo.private('data-delete-'+Ermis.link+'-'+Chat.com)
       .listen('DataSend', (rs) => {
         jQuery('.note_link[data-note-id='+rs.data.id+']').parent('li').remove();
       });
     Echo.private('data-save-'+Ermis.link+'-'+Chat.com)
        .listen('DataSend', (rs) => {
          if(rs.data.type == 2){ // Add
            var c = jQuery('li.hidden').clone(true);
            c.removeClass('hidden');
            c.find('.note_link').attr('data-note-id',rs.data.id);
            c.find('span.md-list-heading').text(rs.data.title);
            c.find('span.uk-text-small').text(moment(rs.data.created_at).format('DD/MM/YYYY'));
            if(jQuery("li").hasClass('append-new') == false){
              var ap = '<li class="heading_list uk-text-danger append-new">'+Lang.get('global.new')+'</li>';
              jQuery('li.hidden').after(ap);
            };
            jQuery('.append-new').after(c);
          }else if (rs.data.type == 3){ // Remove
            var c = jQuery('.note_link[data-note-id='+rs.data.id+']');
            c.find('span.md-list-heading').text(rs.data.title);
          }

        });
  };



    return {

        init: function () {
          initGetShortKey();
          initGetColunm();
          initStatus(1);
          onChange();
          initClientReceive();
        }

    };

}();

jQuery(document).ready(function () {
    Ermis.init();
});
