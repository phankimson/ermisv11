var Ermis = function() {  
    var initStatus = function (flag) {
    if (flag === 1) {//DEFAULT
        jQuery('.start').on('click ', btnStart);   
        jQuery('.load').on('click ', btnLoad);        
    }
  }
  var btnLoad = function(e){
        e.preventDefault();
          if(jQuery('input[name="host"]').val() != '' && jQuery('input[name="database"]').val() != ''){
          var name = "Tables_in_"+jQuery('input[name="database"]').val();
          var data = GetAllValueForm('.info-database');
          var postdata = {data : JSON.stringify(data)};
              RequestURLWaiting('load-database','json',postdata,function(result){
                  if(result.status == true){
                      jQuery('#notification').EPosMessage('success', result.message);
                        jQuery.each(result.tables, function (k, v) {
                            var clone = jQuery(".item_table").clone(true);
                            clone.removeClass("hidden");
                            clone.removeClass("item_table");
                            clone.find("label").html(v[name]);
                            clone.find("input:checkbox").attr("name",v[name]);
                            clone.find("select").attr("name",v[name]);
                            clone.appendTo(".item_table_add");
                        });
                  }else{
                      jQuery('#notification').EPosMessage('error', result.message);
                  }
              },true);
        }else{
              jQuery('#notification').EPosMessage('error', Lang.get('messages.please_fill_field'));
        }
    };
      var btnStart = function(e){
        e.preventDefault();
          if(jQuery('textarea[name="query"]').val() != ''){
          var data = GetAllValueForm('.query-form');
          var postdata = {data : JSON.stringify(data)};
              RequestURLWaiting('update-database','json',postdata,function(result){
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

    return {
        //main function to initiate the module
        init: function() {
         initStatus(1);
        }

    };

}();

jQuery(document).ready(function() {
    Ermis.init();
});
