var Chat = function () {
    var i = 1,
    k = 1,
    user_receipt = "",
    crit_load = false;

    let channel = Echo.private('chat-user');

    var initStatusChat = function(user,status) {
        var data = jQuery(".chat_users").find("li[data-user="+user+"]");
      if(status == 0){ // OFFLINE
        data.find(".element-status").removeClass().addClass("element-status");
        data.find("span.uk-text-small.uk-text-muted.uk-text-truncate").html(Lang.get('messages.offline'));
      }else if(status == 1 ){ // ONLINE
        data.find(".element-status").removeClass().addClass("element-status").addClass("element-status-success");
        data.find("span.uk-text-small.uk-text-muted.uk-text-truncate").html(Lang.get('messages.online'));
      }else if(status == 2 ){ // BUSY
        data.find(".element-status").removeClass().addClass("element-status").addClass("element-status-danger");
        data.find("span.uk-text-small.uk-text-muted.uk-text-truncate").html(Lang.get('messages.busy'));
      }else if(status == 3 ){ // GOING OUT
        data.find(".element-status").removeClass().addClass("element-status").addClass("element-status-warning");
        data.find("span.uk-text-small.uk-text-muted.uk-text-truncate").html(Lang.get('messages.going_out'));
      }
    };


    var initCheckStatus = function(){
      Echo.join('chat-room-'+Chat.com)
        .here((users) => {
          jQuery.each(users, function (k, v) {
            initStatusChat(v.id,1);
          });
        })
        .joining((user) => {
              UIkitshowNotify ("<a href='javascript:;' class='notify-action'>x</a>"+ user.name + " " + Lang.get('messages.online') , null , 0 , null,"top-right");
              initStatusChat(user.id,1);
        })
        .leaving((user) => {
              UIkitshowNotify ("<a href='javascript:;' class='notify-action'>x</a>"+ user.name + " " + Lang.get('messages.offline') , null , 0 , null,"top-right");
              initStatusChat(user.id,0);
        });
    };

    var initLoadChatUserScroll = function(){
      $(".scrollbar-inner").on("scroll",function(e){
        e.preventDefault();
        if(jQuery(".chat_box_active").length > 0){
          var a = $(this).scrollTop();
          var c = $(this).height();
          var b = $(this)[0].scrollHeight;
          if( a + c == b && crit_load == false && a >0){
           k++;
           arr = {};
           arr['user_receipt'] = user_receipt;
           arr['page'] = k;
           arr['com'] = Chat.com;
           var postdata = { data: JSON.stringify(arr) };
           ErmisTemplateAjaxPost0(e,postdata,'load-chat-user',
           function(result){
             jQuery.each(result.data, function (k, v) {
               if(v.user_send == user_receipt && v.user_receipt == user_receipt){
                   bindDataUser(v,'append',1);
                   bindDataUser(v,'append',2);
               }else if(v.user_send == user_receipt){
                   bindDataUser(v,'append',1);
               }else if(v.user_receipt == user_receipt){
                   bindDataUser(v,'append',2);
               }
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

    var initLoadChatUser = function(){
      jQuery(".chat_users li").on("click",function(e){
        e.preventDefault();
        k = 1;
        crit_load = false;
        arr = {};
        jQuery(".chat_message_wrapper").not(".chat_message_load").remove()
        user_receipt = jQuery(this).attr("data-user");
        arr['user_receipt'] = user_receipt;
        arr['page'] = k;
        arr['com'] = Chat.com;
        var postdata = { data: JSON.stringify(arr) };
        ErmisTemplateAjaxPost0(e,postdata,'load-chat-user',
        function(result){
          jQuery.each(result.data, function (k, v) {
            if(v.user_send == user_receipt && v.user_receipt == user_receipt){
                bindDataUser(v,'append',1);
                bindDataUser(v,'append',2);
            }else if(v.user_send == user_receipt){
                bindDataUser(v,'append',1);
            }else if(v.user_receipt == user_receipt){
                bindDataUser(v,'append',2);
            }
            k++;
          })
        },function(result){
          kendo.alert(result.message);
        })
      })
    };


    var initSendChatUser = function(){
        jQuery("#submit_message").on("click",function(e){
           e.preventDefault();
           var arr = {}
           arr['user_receipt'] = user_receipt;
           arr['message'] = jQuery("#content_message").val();
           arr['user_send'] = jQuery("#session_user").val();
           arr['com'] = Chat.com;
           var postdata = { data: JSON.stringify(arr) };
           ErmisTemplateAjaxPost0(e,postdata,'chat',
           function(result){
             jQuery("#content_message").val("");
             bindDataUser(arr,'prepend',2);
           },function(result){
             kendo.alert(result.message);
           })
        })

        //channel.listenForWhisper('chatting', (rs) => {
        //  bindDataUser(rs.data,'prepend',1);
        //  var user = jQuery(".chat_users li[data-user='"+rs.data.user_receipt+"']").find(".md-list-heading").html();
        //  UIkitshowNotify ("<a href='javascript:;' class='notify-action'>x</a>"+ rs.data.user_send + " " + Lang.get('messages.chatting') , null , 0 , null,"top-right");
        //})
        Echo.private('user-'+Chat.u)
           .listen('UserChat', (rs) => {
             bindDataUser(rs.data,'prepend',1);
             var user = jQuery(".chat_users li[data-user='"+rs.data.user_receipt+"']").find(".md-list-heading").html();
              UIkitshowNotify ("<a href='javascript:;' class='notify-action'>x</a>"+ rs.data.user_send + " " + Lang.get('messages.chatting') , "success" , 0 , null,"top-center");
           });
    };

    var initTypeChat = function(){
      jQuery('#content_message').on('keydown', function(){
       setTimeout( () => {
         channel.whisper('typing', {
           user: Chat.u,
           typing: true
         })
       }, 300)
      });

      channel.listenForWhisper('typing', (e) => {
        if(e.user == user_receipt){
            e.typing ? jQuery('.typing').show() : jQuery('.typing').hide();
        }
      });

        jQuery("#content_message").blur(function(){
          setTimeout( () => {
            channel.whisper('blur', {
              user: Chat.u,
              typing: true
            })
          }, 300)
       });

       channel.listenForWhisper('blur', (e) => {
         if(e.user == user_receipt){
           setTimeout( () => {
             jQuery('.typing').hide();
           }, 1000)
         }
       });
    }

    var initSendEvent = function(){
        jQuery(".send-event").on("click",function(e){
           e.preventDefault();
           var arr = {}
           arr['type'] = jQuery("#action-event").data("kendoComboBox").value();
           arr['message'] = jQuery("#editor").data("kendoEditor").value();
           arr['com'] = Chat.com;
           var postdata = { data: JSON.stringify(arr) };
           ErmisTemplateAjaxPost0(e,postdata,'timeline',
           function(result){

           },function(result){
             kendo.alert(result.message);
           })
        })

        Echo.private('chat-room-'+ Chat.com)
        .listen('ChatTimeline', (rs) => {
             bindData(rs.data,"prepend");
           })

    };

      var bindDataUser = function(data,position,type){
        var item = jQuery(".chat_message_wrapper").last().clone(true);
        item.removeAttr("style")
        item.removeClass("chat_message_load")
        var src_user_receipt = jQuery(".chat_users li[data-user='"+data.user_receipt+"']").find(".md-user-image").attr("src")
        var src_user_send = jQuery(".chat_users li[data-user='"+data.user_send+"']").find(".md-user-image").attr("src")
        if(type == 1){
          item.removeClass("chat_message_right")
          item.find(".md-user-image").attr("src",src_user_receipt)
        }else{
          item.addClass("chat_message_right")
          item.find(".md-user-image").attr("src",src_user_send)
        }
        if(data.created_at){
          item.find(".chat_message p").html(data.message+"<span class='chat_message_time'>"+moment(data.created_at).calendar()+"</span>")
        }else{
          item.find(".chat_message p").html(data.message+"<span class='chat_message_time'>"+moment().calendar()+"</span>")
        }
        if(position == 'prepend'){
            jQuery(".chat_box.chat_box_colors_a").prepend(item);
        }else{
            jQuery(".chat_box.chat_box_colors_a").append(item);
        }
      }
      var bindData = function(data,position){
        var item = jQuery(".timeline_item").first().clone(true);
        item.find(".timeline_icon").removeClass("timeline_icon_success");
        item.removeAttr("style");
        item.find('.timeline_content').text(data.username);
        if(data.type == "1"){
        item.find(".timeline_icon").addClass("timeline_icon_success");
        item.find(".material-icons").html("&#xE85D;");
        }else if(data.type == "2"){
        item.find(".timeline_icon").addClass("timeline_icon_danger");
        item.find(".material-icons").html("&#xE5CD;");
      }else if(data.type == "3" ){
        item.find(".material-icons").html("&#xE410;");
      }else if(data.type == "4" ){
        item.find(".timeline_icon").addClass("timeline_icon_primary");
        item.find(".material-icons").html("&#xE0B9;");
      }else if(data.type == "5"){
        item.find(".timeline_icon").addClass("timeline_icon_warning");
        item.find(".material-icons").html("&#xE7FE;");
        }
        if(data.created_at != null){
        item.find(".timeline_date").html(moment(data.created_at, "YYYY-MM-DD").format('DD/MM/YYYY'))
        }else{
        var d = new Date();
        item.find(".timeline_date").html(d.getDate()+"/<span>"+ (d.getMonth() + 1) +"</span>/"+"<span>"+d.getFullYear()+"</span>");
        }
        item.find(".timeline_content").html(data.user);
        item.find(".timeline_content_addon blockquote").html(data.message);
        if(position == 'prepend'){
            jQuery(".timeline").prepend(item);
        }else{
            jQuery(".timeline").append(item);
        }

        jQuery('#event_content').data("kendoWindow").close();
        jQuery('body').addClass('sidebar_secondary_active');
      }

      var initLoadTimeline = function(){
        jQuery("#view_more").on("click",function(e){
          e.preventDefault();
          var postdata = { data: JSON.stringify(i) };

          ErmisTemplateAjaxPost0(e,postdata,'view-more-timeline',
          function(result){
            jQuery.each(result.data, function (k, v) {
              bindData(v,"append");
            })
              i++;
          },function(result){
            kendo.alert(result.message);
          })

        })
      };

    return {

        init: function () {
            initCheckStatus();
            initSendEvent();
            initLoadTimeline();
            initLoadChatUser();
            initLoadChatUserScroll();
            initSendChatUser();
            initTypeChat();
        }

    };

}();

jQuery(document).ready(function () {
    Chat.init();
});
