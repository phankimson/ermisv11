function KendoUiConfirm(message, title) {
    var dfd = new jQuery.Deferred();
    var result = false;
    var kendoContent = '<p class="confirm-message">' + message + '</p><br/><button tabindex="1" class="confirm-kendoui k-button">' + Lang.get('action.yes') + '</button><button tabindex="2" class="cancel-kendoui k-button uk-margin-small-left">' + Lang.get('action.no') + '</button>';
    var kendoWindow = $("<div />").kendoWindow({
        width: "400px",
        title: title,
        resizable: false,
        modal: true,
        visible: false,
        activate: function(){
          kendoWindow.find(".confirm-kendoui").focus();
        },
        close: function (e) {
        this.destroy();
        dfd.resolve(result);
    }
    });
    kendoWindow.data("kendoWindow")
     .content(jQuery(kendoContent))
     .center().open();
    kendoWindow.find(".confirm-kendoui,.cancel-kendoui")
       .click(function () {
           if ($(this).hasClass("confirm-kendoui")) {
               result = true;
           }

           kendoWindow.data("kendoWindow").close();
       });
    return dfd.promise();
}

function TabsTripUikitV2 ($elem,$tab){
  jQuery($elem+' .uk-tab-content').fadeOut("fast","linear");
  jQuery($tab+'.uk-tab').on('change.uk.tab', function(e, active, previous) {
      jQuery($elem+' .uk-tab-content').fadeOut("fast","linear");
      var a = jQuery(active.context).attr('data-id');
      if(!a){
        a = jQuery($tab+'.uk-tab .uk-active').attr('data-id');
      };
      var b = jQuery($elem+' .uk-tab-content:eq('+parseInt(a)+')');
      b.fadeIn("fast","linear");
  });
}

function TabsTripUiKitV2Default ($elem,$tab){
  jQuery($tab+' li').removeClass("uk-active");
  jQuery($tab+' li').first().addClass("uk-active");
  jQuery($elem+' .uk-tab-content').fadeOut("fast","linear");
  var b = jQuery($elem+' .uk-tab-content:eq(0)');
  b.fadeIn("fast","linear");
}

function UrlString (url){
  var hostname = window.location.hostname;
  var port = window.location.port;
  var protocal = window.location.protocol;
  var string = '';
  if(window.location.port){
    string = window.location.protocol + '//' + window.location.hostname + ':' + window.location.port + '/' + url;
  }else{
    string = window.location.protocol + '//' + window.location.hostname + '/' + url;
  }
  return string;
}

function UrlStringPath (url,path){
  var hostname = window.location.hostname;
  var port = window.location.port;
  var protocal = window.location.protocol;
  var string = '';
  if(window.location.port){
    string = window.location.protocol + '//' + window.location.hostname + ':' + window.location.port + '/' + path + '/' + url;
  }else{
    string = window.location.protocol + '//' + window.location.hostname + '/' + path + '/' + url;
  }

  return string;
}

function ValidationErrorMessages (data){
    if(jQuery('.validate-alert').length > 0){
      jQuery('.validate-alert').remove();
    }
    var mes = '';
    jQuery.each(data, function (k, v) {
      mes += v+"</br>";
      jQuery('[name="' + k + '"]').after("<li class='validate-alert'>"+v+"</li>");
    });
    UIkitshowNotify(mes);
    setTimeout(function(){ jQuery('.validate-alert').fadeOut() }, 5000);
}

function ConvertNumber ($string , $decimal = ","){
  if($string){
    var check = $string.toString().indexOf($decimal);
    var ex = /\,/g;
    if ($string !== 0 && check !== -1) {
      if($decimal == "."){
        ex = /\./g;
      }
      return  $string = $string.replace(ex, "");
    }
  }else{
    $string = 0;
  }
    return $string;
}

function findObjectByKey(array, key, value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i][key] === value) {
            return array[i];
        }
    }
    return null;
}

function arrayColumn(array, columnName) {
    return array.map(function(value,index) {
        return value[columnName];
    })
}

function UIkitshowNotify (message, status ,timeout,group , pos) {
  thisNotify = UIkit.notify({
      message: message ? message : '',
      status: status ? status : '',
      timeout: timeout ? timeout : 5000,
      group: group ? group : null,
      pos: pos ? pos : 'top-center'
  });
  if(
      (
          ($window.width() < 768)
          && (
              (thisNotify.options.pos == 'bottom-right')
              || (thisNotify.options.pos == 'bottom-left')
              || (thisNotify.options.pos == 'bottom-center')
          )
      )
      || (thisNotify.options.pos == 'bottom-right')
  ) {
      var thisNotify_height = $(thisNotify.element).outerHeight();
      var spacer = $window.width() < 768 ? -6 : 8;
      $body.find('.md-fab-wrapper').css('margin-bottom',thisNotify_height + spacer);
  }
}

var initErmisBarcodeMasker = function (data) {
    var char = '0'; var voucher = "";
    var number = data.length_number;
    if (data.number) {
        voucher = data.prefix+char.repeat(number - (data.number+"").length) + data.number+data.suffixes;
    } else {
        voucher = char.repeat(number);
    }
    return voucher
};

var initErmisBarcodeMaskerHide = function(d){
  var data = d;
  var char = 'x';
  var number = parseInt(data.length_number);
  if (data.suffixed) {
      voucher = data.prefix + char.repeat(number) + data.suffixed;
  } else {
      voucher = data.prefix + char.repeat(number);
  }
  return voucher
}

var initErmisCheckSession = function(){
  if (!sessionStorage.status) {
      status = 1;
  } else {
      status = parseInt(sessionStorage.status);
  }
  return status;
}


var initFixScrollGrid = function(){
    var spe = "fast";
    var el = $kGridTab.find("div.k-grid-content");
    var a = el.scrollLeft();
    var b = el.scrollTop();
    var _left_p = a+0.00001;
    var _top_p = b;
    el.animate({ scrollLeft: _left_p, scrollTop: _top_p} , spe);
}

var initGetDefaultKeyArray = function(array){
  var obj = {};
  var data = Object.keys(array);
  jQuery.each(data, function (k, col) {
    var a = array[col];
    if(array[col].hasOwnProperty("defaultValue")){
      obj[col] = array[col].defaultValue
    }else{
      if(array[col].type == "number"){
        obj[col] = 0;
      }else{
        obj[col] = "";
      }
    }
  })
  return obj;
}

var initGetValidateKeyArray = function(array){
  var obj = {};
  var data = Object.keys(array);
  jQuery.each(data, function (k, col) {
    var a = array[col];
    if(array[col].hasOwnProperty("validation")){
      obj[col] = array[col].validation
    }
  })
  return obj;
}

var initValidationGrid = function(data,arr_field){
  var ret = [];
  var val = initGetValidateKeyArray(arr_field);
  var mes = '';
  jQuery.each(val, function (k, col) {
    var a = true;
    var b = arrayColumn(data, k);
    var c = [];
    if(col.required == true){
      if(arr_field[k].hasOwnProperty('defaultValue')&& arr_field[k].defaultValue.hasOwnProperty('id')){
        if (col.min >= 0){
          a = b.filter((n,i) => { if(n.id <= col.min) {c.push(i)} ; return n.id <= col.min });
          mes = Lang.get('validation.in', { attribute: Lang.get('acc_voucher.'+k).toLowerCase() });
        }else if (col.max >= 0){
          a = b.filter((n,i)  => { if(n.id >= col.max) {c.push(i)} ; return n.id >= col.max });
          mes = Lang.get('validation.in', { attribute: Lang.get('acc_voucher.'+k).toLowerCase() });
        }else{
          a = b.filter((n,i)  => { if(/[0-9]/.test(n.id) != true) {c.push(i)} ; return (/[1-9]/.test(n.id)) != true });
          mes = Lang.get('validation.required', { attribute: Lang.get('acc_voucher.'+k).toLowerCase() });
        }
      }else{
        if (col.min >= 0){
          a = b.filter((n,i)  => {if(n <= col.min) {c.push(i)} ; return n <= col.min });
          mes = Lang.get('validation.min.numeric', { attribute: Lang.get('acc_voucher.'+k).toLowerCase() , min : col.min});
        }else if (col.max >= 0){
          a = b.filter((n,i)  => { if(n >= col.max) {c.push(i)}  ; return n >= col.max });
          mes = Lang.get('validation.max.numeric', { attribute: Lang.get('acc_voucher.'+k).toLowerCase() , max : col.max});
        }else{
          a = b.filter((n,i)  => { if(/[0-9]/.test(n) != true) {c.push(i)}  ;return (/[a-z]/.test(n)) != true });
          mes = Lang.get('validation.required', { attribute: Lang.get('acc_voucher.'+k).toLowerCase() });
        }
      }
    }
    if(a.length > 0){
      ret.push({"key" : k , "mes" : mes, "arr" : c});
    }
  })
  return ret;
}

var initValidationGridColumnKey = function(data,arr_column){
  var ret = [];
    jQuery.each(arr_column, function (k, col) {
      if(col.key == true){
        var mes = '';
        var tex;
        var r  = jQuery('#'+col.field+"_dropdown_list").data("json");
        jQuery.each(data, function (l, p) {
            var d = findObjectByKey(r,'id',p[col.field].id);
            if(d != null){
              jQuery.each(d, function (m, n) {
                if(m != "id" && m != "code" && m != "name"){
                  if(m == 'object'){
                    m = jQuery("input[data-get='object.code']").attr("data-find");
                  }
                  var v = findObjectByKey(arr_column,'field', m);
                  if(v != null ){
                    if(v.hasOwnProperty('select')){
                      tex = /[1-9]/;
                    }else{
                      tex = /[a-z]/;
                    };
                    if(n == 1 && (tex.test(p[m].id)) != true){
                      var h = ret.findIndex(i => i.key === m);
                      if(h < 0){
                        var c = [];
                        c.push(l);
                        mes = Lang.get('validation.required', { attribute: Lang.get('acc_voucher.'+m).toLowerCase() });
                        ret.push({"key" : m , "mes" : mes, "arr" : c});
                      }else{
                        ret[h].arr.push(l);
                      }
                    }
                  }
                }
              });
            }
        });
      }
    });
   return ret;
}

var initShowValidationGrid = function(data,crit_arr,$kGrid){
  var grid = $kGrid.data("kendoGrid");
  var columns = grid.options.columns;
  var mes = arrayColumn(crit_arr, "mes");
  jQuery.each(data, function (l, n) {
      jQuery.each(crit_arr, function (k, col) {
        if(col.arr.includes(l) == true){
          var cell = -1;
          for (var i = 0; i < grid.columns.length; i++) {
            if (columns[i].field == col.key) {
                       cell = i;
            }
          }
          var row = grid.tbody.find("tr[data-uid='" + data[l].uid + "']");
          row.children().eq(cell).addClass('alert-danger');
        }
      });
    });
      kendo.alert(mes.join("</br>"));
}

var sort_by = function (field, reverse, primer) {

    var key = primer ?
        function (x) { return primer(x[field]); } :
        function (x) { return x[field]; };

    reverse = !reverse ? 1 : -1;

    return function (a, b) {
        return a = key(a), b = key(b), reverse * ((a > b) - (b > a));
    };
    //USE

    // Sort by price high to low
    //homes.sort(sort_by('price', true, parseInt));

    // Sort by city, case-insensitive, A-Z
    //homes.sort(sort_by('city', false, function (a) { return a.toUpperCase() }));
};



function PrintForm(elem,data) {
        elem.print({
        //Use Global styles
        globalStyles: false,
        append : data,
        //Add link with attrbute media=print
        mediaPrint : false,
        //Print in a hidden iframe
        iframe : true,
        //Don't print this
        //noPrintSelector : ".avoid-this",
        deferred: $.Deferred().done(function() { console.log('Printing done', arguments); })
});
}

function SetDataAjax(data, dataItem){
  jQuery.each(data, function (k, col) {
      var v = '';
      if(col.get){
      v = eval('dataItem.'+col.get);
      }else{
      v = dataItem[col.field];
      }
      if (col.key === 'text' || col.key === 'password' || col.key === 'number') {
          if (col.type === 'date') {
              var d = kendo.toString(kendo.parseDate(v, 'yyyy-MM-dd'), 'dd/MM/yyyy');
              jQuery('input[name="' + col.field + '"]').val(d);
          } else if (jQuery('input[name = ' + col.field + ']').hasClass("number-price") || jQuery('input[name = ' + col.field + ']').hasClass("number")) {
              jQuery('input[name="' + col.field + '"]').data("kendoNumericTextBox").value(v);
          }else if (jQuery('input[name = ' + col.field + ']').hasClass("color")) {
              var color = kendo.parseColor(color);
              jQuery('input[name="' + col.field + '"]').data("kendoColorPicker").value(v);
          }else if (jQuery('input[name = ' + col.field + ']').hasClass("prefix")) {
              var string_replace = jQuery('#prefix_'+col.field).text();
              var string = v.replace(string_replace,"");
              jQuery('input[name="' + col.field + '"]').val(string);
          } else {
              jQuery('input[name="' + col.field + '"]').val(v);
          }
      } else if (col.key === 'select') {
            if (v === null && jQuery('select[name="' + col.field + '"]').hasClass("droplist")) {
                jQuery('select[name="' + col.field + '"]').data('kendoDropDownList').value("0");
            } else {
             if(jQuery('select[name="' + col.field + '"]').hasClass("multiselect")){
               if(v != null || (Array.isArray(v) && v.length == 0)){
                 if(col.type == "arr"){
                  jQuery('select[name="' + col.field + '"]').data('kendoMultiSelect').value(v);
                 }else{
                  jQuery('select[name="' + col.field + '"]').data('kendoMultiSelect').value(v.split(","));
                 }                  
               }else{
                  jQuery('select[name="' + col.field + '"]').data('kendoMultiSelect').value(null);
               }
              }else{
                jQuery('select[name="' + col.field + '"]').data('kendoDropDownList').value(v);
            }
          }
      } else if (col.key === 'checkbox') {
          if (v === "1" || v === true || v === 1) {
              jQuery('input[name="' + col.field + '"]').parent().addClass('checked');
          } else {
              jQuery('input[name="' + col.field + '"]').parent().removeClass('checked');
          }
      } else if (col.key === 'textarea') {
        if(jQuery('textarea[name="' + col.field + '"]').hasClass('editor')){
          var decoded = $("<div/>").html(v).text();
           jQuery('textarea[name="' + col.field + '"]').data("kendoEditor").value(decoded);
        }else{
          jQuery('textarea[name="' + col.field + '"]').val(v);
        }
      } else if (col.key === 'radio') {
          jQuery('input[name="' + col.field + '"][value="' + v + '"]').attr('checked', true);
      } else if (col.key == 'file'){
        if(v == null || v == ''){
          jQuery("#"+col.field+"_preview").attr("src",'');
        }else{
          jQuery("#"+col.field+"_preview").attr("src",UrlString(v));
        }
      }
  });
}

function SetDataDefault(columns){
  jQuery.each(columns, function (k, col) {
      if (col.key === 'select' && jQuery('select[name = ' + col.field + ']').hasClass("droplist")) {
          jQuery('.droplist[name="' + col.field + '"]').data('kendoDropDownList').value("0");
      } else if (col.key === 'select' && jQuery('select[name = ' + col.field + ']').hasClass("multiselect")) {
          jQuery('.multiselect[name="' + col.field + '"]').data('kendoMultiSelect').value("");
      } else if (col.key === 'checkbox') {
          var a = jQuery('input[name="' + col.field + '"]');
          var value = jQuery('input[name="' + col.field + '"]').attr("data-value");
          if(value == 1){
            a.parent().addClass('checked');
          }
      } else if (col.type === 'date') {
          if (col.value === 'now') {
              jQuery('input[name="' + col.field + '"]').data("kendoDatePicker").value(kendo.toString(new Date(), 'dd/MM/yyyy'));
          } else {
              jQuery('input[name="' + col.field + '"]').data("kendoDatePicker").value("");
          }
      }  else if (col.key === 'number') {
              var df = jQuery('input[name="' + col.field + '"]').attr('value');
              jQuery('input[name="' + col.field + '"]').val(df);
      } else if (col.type === 'radio') {
          jQuery('input[name="' + col.field + '"]').attr("checked", "checked");
      } else if (col.addoption === 'true') {
          jQuery('#droplist-validation_listbox .k-item').removeClass('disabled k-state-disabled');
      }else if (col.key === 'textarea' && jQuery('textarea[name = ' + col.field + ']').hasClass("editor")) {
              jQuery('textarea[name="' + col.field + '"]').data('kendoEditor').value("");
      }
  });
}

function GetDataAjax(data) {
  var obj = {}; var crit = false;
  jQuery.each(data, function (k, col) {
      if (col.null === true && !jQuery('input[name="' + col.field + '"]').val()) {
          crit = false;
          return false;
      } else {
          crit = true;
      }

      if (col.key === 'text' || col.key === 'password' || col.key === 'number') {
          if (jQuery('input[name="' + col.field + '"]').hasClass('number-price') || jQuery('input[name="' + col.field + '"]').hasClass('number')) {
              obj[col.field] = jQuery('input[name="' + col.field + '"]').data("kendoNumericTextBox").value();
          } else {
              obj[col.field] = jQuery('input[name="' + col.field + '"]').val().trim();
              if (col.type === 'date') {
                  obj[col.field] = formatDateDefault(obj[col.field]);
              }
          }

      } else if (col.key === 'select' && jQuery('select[name = ' + col.field + ']').hasClass("droplist")) {
          obj[col.field] = jQuery('.droplist[name="' + col.field + '"]').data('kendoDropDownList').value();
      } else if (col.key === 'select' && jQuery('select[name = ' + col.field + ']').hasClass("multiselect")) {
        if(col.type === 'arr'){
          obj[col.field] = jQuery('.multiselect[name="' + col.field + '"]').data('kendoMultiSelect').value();
        }else{
          var arr = jQuery('.multiselect[name="' + col.field + '"]').data('kendoMultiSelect').value();
          obj[col.field] = arr.join();
        }         
      } else if (col.key === 'textarea') {
          obj[col.field] = jQuery('textarea[name="' + col.field + '"]').val().trim();
      } else if (col.key === 'checkbox') {
          if (jQuery('input[name="' + col.field + '"]').parent().hasClass('checked')) {
              if (col.type === 'boolean') {
                  obj[col.field] = true;
              } else if (col.type === 'number'){
                  obj[col.field] = 1;
              }else {
                  obj[col.field] = '1';
              }
          } else {
              if (col.type === 'boolean') {
                  obj[col.field] = false;
              }else if (col.type === 'number'){
                  obj[col.field] =  0;
              } else {
                  obj[col.field] = '0';
              }
          }
      } else if (col.key === 'radio') {
          obj[col.field] = jQuery('input[name="' + col.field + '"]:checked').val();
      }
  });
  return { obj: obj, crit: crit };
}

function GetDataAjaxTabstrip(elem) {
    var data = {};
    jQuery(elem).find("li").each(function () {
        var key = jQuery(this).attr("id");
        var bh = GetAllDataForm("#" + key, 2);
        if (jQuery(".uk-tab").find("li[data-tabs='" + key + "']").css("display") !== "none") {
            data[key + '_active'] = 1;
            data = GetDataAjax(bh.columns);
        } else {
            data[key + '_active'] = 0;
        }

    })
    return { obj : data };
}

function GetAllValueForm(elem) {
    var map = {};
    jQuery(elem + ' input').each(function () {
        if (jQuery(this).attr("type") === 'radio') {
            map[jQuery(this).attr("name")] = jQuery('input[name=radio]:checked', '#radio').val();
        } else {
            map[jQuery(this).attr("name")] = jQuery(this).val();
        }
    });
    jQuery(elem + ' textarea').each(function () {
        map[jQuery(this).attr("name")] = jQuery(this).val();
    });
    jQuery(elem + ' select').each(function () {
        map[jQuery(this).attr("name")] = jQuery(this).find('option:selected').val();
    });
    return map;
}

function GetAllDataForm(elem,style) {
    var data = [];
    var column = [];
    var fields = {};
    if (!style) {
        column = [{ "title": "STT", "field": "row_number", "width": 80, "position": 0 }];
        fields['row_number'] = { "type" : "number" };
    }else if(style == 1){
        column = [{ "title": "STT", "template": "<span class='row-number'></span>", "width": 100, "position": 0 }];       
    };
    fields['id'] = { "type" : "string" , nullable: false  };
    jQuery(elem + ' select').each(function () {
        var i = jQuery(this).attr('data-show');
        if (i === true || !i) {
            var k = parseInt(jQuery(this).attr('data-position'));
            var a = jQuery(this).attr('name');
            var c = jQuery(this).attr('data-template');
            var e = jQuery(this).attr('data-title');
            var t = jQuery(this).attr('data-type');
            var w = jQuery(this).attr('data-width');
            var h = jQuery(this).attr('data-hidden') ? true : false;
            var u = jQuery(this).attr('data-nullable') ? true : false;
            var v = jQuery(this).attr('data-value');
            var d = jQuery(this).attr('add-option');
            var r = jQuery(this).attr('data-remove') ? true : false;
            var f = jQuery(this).attr('data-format');
            var g = jQuery(this).attr('data-filter');
            if (c || c === "") {
                adata = { "field": a, "title": e, "width": w, "template": c,"format":f, "type": t, "key": "select", "position": k, "hidden": h, "value": v, "addoption": d ,"filter" : g };
            } else {
                adata = { "field": a, "title": e, "width": w, "type": t, "key": "select", "position": k, "hidden": h, "value": v, "addoption": d ,"filter" : g };
            }
            if (r === false && a != undefined) {
                column.push(adata);
            }
            if(t || t === ""){
              fields[a] = { "field": a, "nullable": u, "defaultValue": v };
            }else{
              fields[a] = { "type": t, "nullable": u, "defaultValue": v };
            }
            
        }
    });
    jQuery(elem + ' textarea').each(function () {
        var i = jQuery(this).attr('data-show');
        if (i === true || !i) {
        var k = parseInt(jQuery(this).attr('data-position'));
        var a = jQuery(this).attr('name');
        var c = jQuery(this).attr('data-template');
        var e = jQuery(this).attr('data-title');
        var t = jQuery(this).attr('data-type');
        var w = jQuery(this).attr('data-width');
        var u = jQuery(this).attr('data-nullable') ? true : false;
        var h = jQuery(this).attr('data-hidden') ? true : false;
        var r = jQuery(this).attr('data-remove') ? true : false;
        var v = jQuery(this).attr('data-value');
        var f = jQuery(this).attr('data-format');
        if (c||c === "") {
            adata = { "field": a, "title": e, "width": w, "template": c,"format":f, "type": t, "key": "textarea", "position": k, "hidden": h, "value": v };
        } else {
            adata = { "field": a, "title": e, "width": w, "type": t, "key": "textarea", "position": k, "hidden": h, "value": v };
        }
        if (r === false && a != undefined) {
            column.push(adata);
        }
        fields[a] = { "type": t, "nullable": u };
        }
    });
    jQuery(elem + ' input').each(function () {
        var i = jQuery(this).attr('data-show');
        if (i === true || !i) {
        var k = parseInt(jQuery(this).attr('data-position'));
        var a = jQuery(this).attr('name');
        var c = jQuery(this).attr('data-template');
        var e = jQuery(this).attr('data-title');
        var t = jQuery(this).attr('data-type');
        var w = jQuery(this).attr('data-width');
        var n = jQuery(this).attr('data-null') ? true : false;
        var h = jQuery(this).attr('data-hidden') ? true : false;
        var u = jQuery(this).attr('data-nullable') ? true : false;
        var r = jQuery(this).attr('data-remove') ? true : false;
        var v = jQuery(this).attr('data-value');
        var f = jQuery(this).attr('data-format');
        var l = jQuery(this).attr('data-get');
        if (c || c === "") {
            adata = { "field": a, "title": e, "width": w, "template": c, "format": f, "type": t, "key": jQuery(this).attr('type'), "position": k, "null": n, "hidden": h, "value": v,'get' : l };
        } else {
            adata = { "field": a, "title": e, "width": w, "type": t, "key": jQuery(this).attr('type'), "position": k, "null": n, "hidden": h, "value": v ,'get' : l};
        }
        if (r === false && a != undefined) {
            column.push(adata);
        }
        fields[a] = { "type": t ,"nullable" : u };
        }
    });
    column.sort(sort_by('position', false, parseInt));
    data['columns'] = column;
    data['fields'] = fields;
    return data;
}

function getDataHot(data){
  var arr = [];
  var arrayHeaders =[];
  var dataSchema = [];
  var arrayColumn = [];
  jQuery.each(data, function (k, col) {
    if(col['default']){
      dataSchema.push(col['default']);
      delete col['default'];
    };
    if(col['title']){
      arrayHeaders.push(col['title']);
      delete col['title'];
    };
    arrayColumn[k] = col;
  });
  arr['ArrayHeaders'] = arrayHeaders;
  arr['dataSchema'] = dataSchema;
  arr['ArrayColumn'] = arrayColumn;
  return arr;
}


jQuery.fn.EPosMessage = function (type, message) {
    type = type === '' ? 'success' : type;
    jQuery(this).html('');
    var messageElem = '';
    if (type === "success") {
        messageElem = jQuery("<div class=\"alert alert-success\"><a href='javascript:;' class='close'></a>" + message + "</div>");
    } else {
        messageElem = jQuery("<div class=\"alert alert-danger\"><a href='javascript:;'  class='close'></a>" + message + "</div>");
    }
    jQuery(this).append(messageElem);
    messageElem.fadeIn(500).delay(5000).fadeOut(500);
    jQuery('.alert .close').on('click',function(){
      messageElem.hide();
    })
};

function RequestURL(url){
    var data = [];
      $.ajax({
      url: url,
      async: false,
      success:function(rs) {
        data = rs
      }
    });
    return data;
}


function RequestURLWaitingGet(url, returnType , postData, callback, displayLoading) {
    var windowWidget = jQuery('body');
    if (displayLoading) {
        kendo.ui.progress(windowWidget, true);
    }
    jQuery.ajax({
        url: url,
        type : 'GET',
        data: postData,
        dataType: returnType,
        success: function (result) {
            callback(result);
            if (displayLoading) {
                kendo.ui.progress(windowWidget, false);
            }
        },
        error: function (xhr) {
            kendo.alert(xhr.statusText);
            kendo.ui.progress(windowWidget, false);
        //    location.reload();
        }
    });
}

function ImageReadURL(input,elem) {
     if (input.files && input.files[0]) {
         var reader = new FileReader();

         reader.onload = function (e) {
             $(elem+'_preview')
                 .attr('src', e.target.result);
         };

         reader.readAsDataURL(input.files[0]);
     }
 }

function RequestURLWaiting(url, returnType, postData, callback, displayLoading) {
    var windowWidget = jQuery('body');
    if (displayLoading) {
        kendo.ui.progress(windowWidget, true);
    }
    jQuery.ajax({
        url: url,
        type : 'POST',
        data: postData,
        dataType: returnType,
        success: function (result) {
            callback(result);
            if (displayLoading) {
                kendo.ui.progress(windowWidget, false);
            }
        },
        error: function (xhr) {
            kendo.alert(xhr.statusText);
            kendo.ui.progress(windowWidget, false);
        //    location.reload();
        }
    });
}

function RequestURLImage(url, returnType, postData, callback, displayLoading) {
  var windowWidget = jQuery('body');
  if (displayLoading) {
      kendo.ui.progress(windowWidget, true);
  }
  jQuery.ajax({
      url: url,
      type : 'POST',
      data: postData,
      processData: false,
      contentType: false,
      success: function (result) {
          callback(result);
          if (displayLoading) {
              kendo.ui.progress(windowWidget, false);
          }
      },
      error: function (xhr) {
          kendo.alert(xhr.responseJSON.message);
          kendo.ui.progress(windowWidget, false);
      //    location.reload();
      }
  });
}

function TryCatch(callback){
  try{
    callback();
  }catch(e){
    kendo.alert(Lang.get('messages.error')+' -JS- '+e.message);
  }
}

function RequestFileURLWaiting(url, postData, callback, displayLoading) {
    var windowWidget = jQuery('body');
    if (displayLoading) {
        kendo.ui.progress(windowWidget, true);
    }
    jQuery.ajax({
        url: url,
        xhr: function () { // custom xhr (is the best)

            var xhr = new XMLHttpRequest();
            var total = 0;

            // Get the total size of files
            jQuery.each(document.getElementById('files').files, function (i, file) {
                total += file.size;
            });

            // Called when upload progress changes. xhr2
            xhr.upload.addEventListener("progress", function (evt) {
                // show progress like example
                var loaded = (evt.loaded / total).toFixed(2) * 100; // percent
            }, false);

            return xhr;
        },
        type: 'POST',
        cache: false,
        processData: false,
        contentType: false,
        data: postData,
        success: function (result) {
                callback(result);
                if (displayLoading) {
                    kendo.ui.progress(windowWidget, false);
                }
        },
        error: function (xhr) {
            kendo.alert(xhr.statusText);
            kendo.ui.progress(windowWidget, false);
        //    location.reload();
        }
    });
}


function formatDate(container) {
    if (container) {
        var from = container.substr(0, 10).split("-");
        var date_string = from[2] + "/" + from[1] + "/" + from[0];
        return date_string;
    }else{
      return container;
    }
}
function formatDateDefault(container) {
    if (container) {
        var from = container.substr(0, 10).split("/");
        var date_string = from[2] + "-" + from[1] + "-" + from[0];
        return date_string;
    }else{
      return container;
    }
}

function formatDateTimeDefault(container) {
    if (container) {
        var from = container.substr(0, 10).split("/");
        var date_string = from[2] + "-" + from[1] + "-" + from[0]+ ' 00:00:00.000';
        return date_string;
    }else{
      return container;
    }
}
function formatDateTime(container) {
    var from = container.substr(0, 10).split("-");
    var time = container.substr(11);
    var date_string = from[2] + "-" + from[1] + "-" + from[0] + " " + time;
    return date_string;
}


function customRenderer(instance, td) {
  Handsontable.renderers.TextRenderer.apply(this, arguments);
    td.style.backgroundColor = '#eaeaea';
  return td;
}

function PercentCustomRenderer(instance, td, row, col, prop, value) {
  Handsontable.renderers.NumericRenderer.apply(this, arguments);
  if(value){
    td.innerHTML = value + ' %'
  }
  return td;
}

function customDropdownRenderer(instance, td, row, col, prop, value, cellProperties) {
    var selectedId;
    var optionsList = cellProperties.chosenOptions.data;

    if(typeof optionsList === "undefined" || typeof optionsList.length === "undefined" || !optionsList.length) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        return td;
    }

    var values = (value + "").split(",");
    value = [];
    for (var index = 0; index < optionsList.length; index++) {

        if (values.indexOf(optionsList[index].id + "") > -1) {
            selectedId = optionsList[index].id;
            value.push(optionsList[index].code+' - '+optionsList[index].name);
        }
    }
    value = value.join(", ");

    Handsontable.renderers.TextRenderer.apply(this, arguments);
    return td;
}

function getMonthDateRange(year, month) {
    //var moment = require('moment');

    // month in moment is 0 based, so 9 is actually october, subtract 1 to compensate
    // array is 'year', 'month', 'day', etc
    var startDate = moment([year, month - 1]);

    // Clone the value before .endOf()
    var endDate = moment(startDate).endOf('month');

    // make sure to call toDate() for plain JavaScript date type
    return { start: startDate, end: endDate };
}
function getQuarterDateRange(year, month) {
    //var moment = require('moment');

    // month in moment is 0 based, so 9 is actually october, subtract 1 to compensate
    // array is 'year', 'month', 'day', etc
    var startDate = moment([year, month - 1]);

    // Clone the value before .endOf()
    var endDate = moment(startDate).endOf('quarter');

    // make sure to call toDate() for plain JavaScript date type
    return { start: startDate, end: endDate };
}
// DEFAULT COLUMN KENDO UI
function FormatStatus(container) {
    var result = '';
    if (container === "0" || container === 0) {
        result = '<span class="uk-badge uk-badge-warning">'+Lang.get('action.not_accepted')+'</span>'
    } else if(container === "1" || container === 1){
        result = '<span class="uk-badge uk-badge-success">'+Lang.get('action.accepted')+'</span>'
    } else {
        result = '<span class="uk-badge uk-badge-info">'+Lang.get('action.completed')+'</span>';
    }
    return result;
}
function FormatImageTooltip(container) {
    return result = '<a data-img="'+container+'" class="tooltipImg" href="javascript:;"><span class="k-icon k-i-image k-i-photo k-icon-22"></span></a>';
}
function FormatCheckBox(container) {
    var result = '';
    if (container === "1" || container === 1) {
        result = '<input type="checkbox" disabled checked=""/>';
    } else {
        result = '<input type="checkbox" disabled/>';
    }
    return result;
}
function FormatCheckBoxBoolean(container) {
    var result = '';
    if (container === true) {
        result = '<input type="checkbox" disabled checked=""/>';
    } else {
        result = '<input type="checkbox" disabled/>';
    }
    return result;
}
function FormatDropList(container,column) {
  var result = "";
  if(container != null && container != ""){
    result = jQuery("select[name='" + column + "']").find('option[value=' + container+ ']').text();
  }
  return result;
}

function FormatRadio(container, column) {
    var id = jQuery("input[name='" + column + "'][value=" + container + "]").attr("id");
    var result = jQuery("label[for='" + id + "'").text();
    return result;
}

function FormatMultiSelectValue(container) {
    var result = container.split('-')[0];
    return result;
}

function FormatMultiSelectValueRow(container,row) {
  var result = container.split('-')[row];
  return result;
}

function FormatNumber(container) {
    var result = 0;
    if (container === null || container === "") {
        result = 0;
    } else {
        result = kendo.toString(parseInt(container), "n0");
    }
    return result;
}

function FormatNumberDecimal(container,decimal) {
    var result = 0;
    if (container === null || container === "") {
        result = 0;
    } else {
        result = kendo.toString(parseInt(container), "n"+decimal);
    }
    return result;
}

function FormatDecimal(container) {
    var result = 0;
    if (container != null || container != "") {
        result = kendo.toString(parseInt(container), "n2");
    }
    return result;
}

function FormatDate(container) {
    var result = "";
    if (container != null) {
          result = kendo.toString(kendo.parseDate(container, 'yyyy-MM-dd'), 'dd/MM/yyyy');
    }
    return result;
}

function FormatMonth(container) {
    var result = "";
    if (container != null) {
        result = kendo.toString(kendo.parseDate(container, 'yyyy-MM'), 'MM/yyyy');
    }
    return result;
}

function DateFormatter(container) {
    var result = "";
    if (container != null || container != "-") {
      var a = new Date(container);
      result = kendo.toString(kendo.parseDate(a, "yyyy-MM-dd"), "dd/MM/yyyy");
    }
    return result;
}

function runningFormatter(value, row, index) {
    return index + 1;
}

function totalTextFormatter(data) {
    return 'Total';
}

function totalFormatter(data) {
    return data.length;
}

function sumFormatter(data) {
    var field = this.field;
    var total = data.reduce(function (sum, row) {
        return sum + (+row[field]);
    }, 0);
    return kendo.toString(parseInt(total), "n0");
}

function avgFormatter(data) {
    return kendo.toString(parseInt(sumFormatter.call(this, data).split('.').join('') / data.length), "n0");
}
function priceFomatter(value) {
    if (value !== null && value !=="-") {
        return kendo.toString(parseInt(value), "n0");
    } else {
        return "";
    }
}
function GetValueFomatter(container, column) {
    var result = jQuery("select[name='" + this.field + "']").find('option[value=' + container + ']').text();
    return result;
}

function setSwitchery(switchElement, checkedBool) {
       if (checkedBool && !switchElement.checked) { // switch on if not on
           $(switchElement).trigger('click').attr("checked", "checked");
       } else if (!checkedBool && switchElement.checked) { // switch off if not off
           $(switchElement).trigger('click').removeAttr("checked");
       }
   }

  function convertKeyJsontoArr(arr) {
    var a = [];
    $.map(arr, function(el,i) {
       a.push(i)
    });
    return a;
  }

  // Arguments: number to round, number of decimal places
function roundNumber(rnum, rlength) {
    var newnumber = Math.round(rnum * Math.pow(10, rlength)) / Math.pow(10, rlength);
    return newnumber;
}

// Detail General Voucher

function getAtIndex(i,storedarrId) {
 if (i === 0) {
   return storedarrId[currentIndex];
 } else if (i < 0) {
   return storedarrId[-(currentIndex + storedarrId.length + i) % storedarrId.length];
 } else if (i > 0) {
   return storedarrId[(currentIndex + i) % storedarrId.length];
 }
}

function checkboxClicked(element,$kGrid) {
    var isChecked = element.checked;
    var field = jQuery(element).attr('name');
    cell = $(element).parent(); /* you have to find cell containing check box*/
    grid = $kGrid.data("kendoGrid");
    grid.editCell(1);
};


function calculateAmount(quantity, price, decimal) {
    var amount = quantity * price;
    return kendo.toString(amount, 'n'+decimal);
};

function calculateAmountRate(amount, rate, decimal ) {
      var amount_rate = 0;
      if(rate > 0){
        amount_rate = amount / rate;
      };
    return kendo.toString(amount_rate, 'n'+decimal);
};

function calculateAmountTax(amount, tax, decimal ) {
    var amount_tax = (amount * tax)/100;
    return kendo.toString(amount_tax, 'n'+decimal);
};

function calculatePriceBind(data,elem, decimal) {
    var total = 0;
    for (var i = 0; i < data.length; i++) {
        if (data[i].quantity > 0 && data[i].price > 0) {
            var a = data[i].price;
            total += data[i].quantity * a;
        }else if(data[i].quantity > 0 && data[i].purchase_price > 0){
            var a = data[i].purchase_price;
            total += data[i].quantity * a;
        }
    }
    if (total > 0) {
        jQuery(elem).text(kendo.toString(total, 'n'+decimal));
    }
};

function calculateTotalVatBind(data,elem, decimal) {
    var total = 0;
    for (var i = 0; i < data.length; i++) {
            var a = data[i].tax;
            total += (data[i].amount * a)/100;
    }
    if (total > 0) {
        jQuery(elem).text(kendo.toString(total, 'n'+decimal));
    }
};

function calculatePriceBindDiscount(data,elem,elem_discount_percent,elem_discount,decimal) {
    var total = 0;
    var discount_percent = jQuery(elem_discount_percent).val();
    var discount = jQuery(elem_discount).val();
    if(discount_percent == null){
      discount_percent = 0;
    }
    if(discount == null){
      discount = 0;
    }
    for (var i = 0; i < data.length; i++) {
      if(data[i].discount_percent == null){
        data[i].discount_percent = 0;
      }
      if(data[i].discount == null){
        data[i].discount = 0;
      }
        if (data[i].quantity > 0 && data[i].price > 0) {
            var a = data[i].price;
            total += data[i].quantity * a*(1-(data[i].discount_percent/100))-data[i].discount;
        }else if(data[i].quantity > 0 && data[i].purchase_price > 0){
            var a = data[i].purchase_price;
            total += data[i].quantity * a*(1-(data[i].discount_percent/100))-data[i].discount;
        }
    }
    if (total > 0) {
        total = total * (1-(discount_percent/100)) - discount
        jQuery(elem).text(kendo.toString(total, 'n'+decimal));
    }
};


function calculatePriceAggregate(decimal) {
    var grid = $kGrid.data("kendoGrid");
    var data = grid.dataSource.data();
    var total = 0;
    var a = grid.columns
    var searchResultArray = findObjectByKey(a ,'field','price');
    for (var i = 0; i < data.length; i++) {
      if (data[i].quantity > 0 && !searchResultArray.hidden) {
            total += data[i].quantity * data[i].price;
        }else if(data[i].quantity > 0 && searchResultArray.hidden){
            total += data[i].quantity * data[i].purchase_price;
        }
    }
        return kendo.toString(total, 'n'+decimal);
};

function calculateTotalVatAggregate(decimal) {
    var grid = $kGridVat.data("kendoGrid");
    var data = grid.dataSource.data();
    var total = 0;
    for (var i = 0; i < data.length; i++) {
      total += data[i].amount * data[i].tax.code / 100;
    }
      return kendo.toString(total, 'n'+decimal);
};

function calculateTotalRateAggregate(decimal) {
    var grid = $kGrid.data("kendoGrid");
    var data = grid.dataSource.data();
    var total = 0;
    for (var i = 0; i < data.length; i++) {
      total += data[i].amount * data[i].rate ;
    }
      return kendo.toString(total, 'n'+decimal);
};


function calculatePriceAggregateDiscount(decimal) {
    var total = 0;
    if($kGrid.find('tr.k-state-selected').length > 0){
      var grid_select = $kGrid.data("kendoGrid");
      var selectedItem = grid_select.dataItem(grid_select.select());
      var discount_percent = selectedItem.discount_percent;
      var discount = selectedItem.discount;
      if(discount_percent == null){
        discount_percent = 0;
      }
      if(discount == null){
        discount = 0;
      }
      var grid = $kDetailGrid.data("kendoGrid");
      var data = grid.dataSource.data();
      for (var i = 0; i < data.length; i++) {
        if(data[i].discount_percent == null){
          data[i].discount_percent = 0;
        }
        if(data[i].discount == null){
          data[i].discount = 0;
        }
          if (data[i].quantity > 0 && data[i].price > 0) {
              var a = data[i].price;
              total += data[i].quantity * a*(1-(data[i].discount_percent/100))-data[i].discount;
          }else if(data[i].quantity > 0 && data[i].purchase_price > 0){
              var a = data[i].purchase_price;
              total += data[i].quantity * a*(1-(data[i].discount_percent/100))-data[i].discount;
          }
      }
      if (total > 0) {
          total = total * (1-(discount_percent/100)) - discount;
      }
    }
      return kendo.toString(total, 'n'+decimal);
};


   ItemsDropDownEditor = function (container, options) {
       var c  = findObjectByKey($kGridTab_column,"field",options.field);
       jQuery('<input required id="' + options.field + '" class="dropdown-list" name="' + options.field + '"/>')
            .appendTo(container)
            .kendoDropDownList({
                select: eval(c.select), // PriItems :Onchange , SleItems : OnchangeCancel , Items : OnchangeItem , Group : OnchangeGroup
                filter: "contains",
                dataTextField: "code",
                dataValueField: "id",
                optionLabel: "---SELECT---",
                headerTemplate: '<div class="dropdown-header k-widget k-header">' +
                              '<span>Code</span>' +
                              '<span>Name</span>' +
                          '</div>',
                template: '<span class="k-state-default"> #:data.code #</span> - ' +
                      '<span class="k-state-default">#: data.name #</span>',
                      virtual: {
                        itemHeight: 26,
                        valueMapper: function(options) {
                           options.success([options.value || 0]); //return the value <-> item index mapping;
                         }
                      },
                autoBind: false,
                dataSource: {
                    pageSize: 80,
                    type: "odata",
                    data: eval(a[options.field])
                }
            });
   };

   AjaxItemsDropDownEditor = function (container, options) {
     var c  = findObjectByKey($kGridTab_column,"field",options.field);
     jQuery('<input required id="' + options.field + '" class="dropdown-list-ajax" name="' + options.field + '"/>')
            .appendTo(container)
            .kendoDropDownList({
              filter: "contains",
              dataTextField: "code",
              dataValueField: "id",
              optionLabel: "---SELECT---",
              select: eval(c.select), // PriItems :Onchange , SleItems : OnchangeCancel , Items : OnchangeItem , Group : OnchangeGroup
              headerTemplate: '<div class="dropdown-header k-widget k-header">' +
                            '<span>Code</span>' +
                            '<span>Name</span>' +
                        '</div>',
              template: '<span class="k-state-default">#: data.code #</span> - ' +
                    '<span class="k-state-default">#: data.name #</span>',
              virtual: {
                   itemHeight: 26,
                   valueMapper: function(options) {
                         options.success([options.value || 0]); //return the value <-> item index mapping;
                    }
              },
              autoBind: false,
              dataSource: {
                        transport: {
                            dataType: 'json',
                            read: {
                                url:  UrlString("api/"+options.field+"_dropdown_list?api_token="+api_token),
                            }
                        },
                        schema: {
                            model: {
                                id: "id",
                                value : "name",
                            }
                        }
                }
          })
   }


      getDataItemName = function(model, field) {
          var value = "";
          b = field;
          a[b] = jQuery('#'+field+"_dropdown_list").data("json");
          if (model.id > 0 || model.id != "") {
              var result = findObjectByKey(a[b],"id",model.id);
              if (result != null) {
                  value = result.code;
              }else{
                value = '---SELECT---';
              }
          } else {
            value = '---SELECT---';
          }
          return value;
      };


      getUrlAjaxItemName = function(model, field) {
        var url = UrlString("api/"+field+"_dropdown_list?api_token="+api_token);
        var value = "";
        if (model.id > 0 || model.id != "") {
              $.ajax({
              url: url,
              async: false,
              success:function(rs) {
                var result = findObjectByKey(rs,"id",model.id);
                if (result != null) {
                    value = result.code;
                }else{
                  value = '---SELECT---';
                }
              }
           });
         } else {
           value = '---SELECT---';
         }
         return value;
      }


   DatePickerEditor = function(container, options){
     var datePicker = $('<input data-role="datepicker" data-format="dd/MM/yyyy" type="date" name="' + options.field + '" data-bind="value:'+ options.field + '">');
      datePicker.appendTo(container);
      var date = kendo.parseDate(options.model[options.field]);
      options.model[options.field] = date;
   }

   AddChooseObjectResult = function(dataItem){
     // Check array
     var arr = jQuery("#subject_code_dropdown_list").data("json");
     var checkid = arr.find(p => p.id === dataItem['id']);

    jQuery.each(Ermis.columns_subject, function(i, v) {
     jQuery('#form-action').find('input[name="' + v.field + '"]').val(dataItem[v.field]);
     var grid = $kGrid.data("kendoGrid");
     var r = grid.dataSource.data();
     var gridVat = $kGridVat.data("kendoGrid");
     var rs = gridVat.dataSource.data();
       if(v.field_set){
       key_rs = v.field_set;
       }else{
       key_rs = v.field;
       };
       if(checkid && key_rs){
         if(v.field == "code"){
           dataDefaultGrid.data[key_rs].id = checkid['id'];
           dataDefaultGrid.data[key_rs].code = checkid['code'];
         }else{
           dataDefaultGrid.data[key_rs] = checkid[key_rs];
         }
         jQuery.each(r, function(l, k) {
           //if(!k[key_rs]){
           if(v.field == "code"){
             k[key_rs].id = checkid['id'];
             k[key_rs].code = checkid['code'];
           }else{
             k[key_rs] = checkid[key_rs];
           }
            //}
         });
         grid.refresh();
       };
      if(key_rs){
       dataDefaultGrid.vat[key_rs] = dataItem[key_rs];
         jQuery.each(rs, function(l, k) {
           if(!k[key_rs]){
               k[key_rs] = dataItem[key_rs];
            }
         });
         gridVat.refresh();
       }
    });
   }

   AddChangeDescriptionResult = function(data){
     var grid = $kGrid.data("kendoGrid");
     var gridVat = $kGridVat.data("kendoGrid");
     var rs = grid.dataSource.data();
     var rsv = gridVat.dataSource.data();
     var r = data;
     dataDefaultGrid.data["description"] = r;
     dataDefaultGrid.vat["description"] = r;
     jQuery.each(rsv, function(l, k) {
         if (!k['description']) {
             k['description'] = r;
         }
     });
     gridVat.refresh();
     jQuery.each(rs, function(l, k) {
         if (!k['description']) {
             k['description'] = r;
         }
     });
     grid.refresh();
   }

   AddSpeechInput = function(data){
     if(data.field == 'subject'){
          AddChooseObjectResult(data.data);
      }else if(data.field == 'traders' || data.field == 'description'){
          jQuery('#form-action').find('input[name="' + data.field + '"]').val(data.data);
          if(data.field == 'description'){
            AddChangeDescriptionResult(data.data);
          }
      }else if(data.field == 'accounted_auto'){
          var elm = jQuery('#accounted_auto');
          var dropdownlist = elm.data("kendoDropDownList");
          dropdownlist.value(data.data.id);
          elm.val(data.data.id).change();
      }else if (data.field == 'date' && data.data.length>0){
          jQuery('.date-picker').val(data.data);
      }else if(data.field == 'accounted_fast'){
            var grid = $kGrid.data("kendoGrid");
            grid.addRow();
            var rs = grid.dataSource.data()[0];
            initLoadColumn(rs,data.data,grid);
            rs[data.field].id = data.data.id;
            grid.refresh();
      }else if(data.field == 'add_row'){
          var grid = $kGridTab.data("kendoGrid");
          grid.addRow();
      }else if(data.field == 'copy_row' || data.field == 'remove_row' ){
        if(!data.data){
          data.data = 0;
        }else{
          data.data = data.data - 1;
        }
        var grid = $kGridTab.data("kendoGrid");
        var dataItem = $kGridTab.data("kendoGrid").dataSource.data()[data.data];
        if(dataItem){
          if(data.field == 'copy_row'){
            grid.dataSource.add(dataItem.toJSON());
          }else{
            var row = grid.tbody.find("tr[data-uid='" + dataItem.uid + "']");
            grid.removeRow(row);
          }
        }else{
          kendo.alert(Lang.get('messages.no_row'));
        }
      }else if(data.field == 'debit' || data.field == 'credit'|| data.field == 'amount'){
        if(data.row > 0){
          data.row = data.row - 1;
        }
        var grid = $kGridTab.data("kendoGrid");
        var rs = grid.dataSource.data()[data.row];
        if(rs){
            if(data.field == 'debit' || data.field == 'credit'){
                rs[data.field].id = data.data.id;
              }else{
                rs[data.field] = parseInt(data.data);
            };
            grid.refresh();
        }else{
          kendo.alert(Lang.get('messages.no_row'));
        }
      }
   }


   //Filter
   var initFilterMultiSelectContent = function(a,field,type){
    if(a == null || (Array.isArray(a) && a.length == 0)){
        jQuery("#tabs_anim table").find(".filter_tr").addClass("hidden");
    }else{
      var check = a.toString().indexOf(",");
      if(check == 1 || type == "arr"){
        jQuery("#tabs_anim table").find(".filter_tr").addClass("hidden");
        if(type == "arr"){
          var values = jQuery('#' + field).data("kendoMultiSelect"); 
          var data = values.dataSource.data();        
          jQuery.each(a, function (k, v) {
            var val = data.find(w => w.value == v).text.split("-")[0].trim();                      
            jQuery("#tabs_anim table").find("tr."+field+"_"+val).removeClass("hidden");
            });                    
        }else{
        jQuery.each(a, function (k, v) {
          jQuery("#tabs_anim table").find("tr."+field+"_"+v).removeClass("hidden");
          });
        }         
      }else{
        jQuery("#tabs_anim table").find(".filter_tr").addClass("hidden");
        jQuery("#tabs_anim table").find("tr."+field+"_"+a).removeClass("hidden");
      }
    }
  }
