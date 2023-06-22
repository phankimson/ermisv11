var ErmisTemplateAjaxPost0 = function(e, postdata, url, callback_true, callback_false) {
    RequestURLWaiting(url, 'json', postdata, function(result) {
        if (result.status === true) {
            callback_true(result);
        } else {
            callback_false(result);
        }
    }, true);
};

var ErmisTemplateAjaxGet0 = function(e, postdata, url, callback_true, callback_false) {
    RequestURLWaitingGet(url, 'json', postdata, function(result) {
        if (result.status === true) {
            callback_true(result);
        } else {
            callback_false(result);
        }
    }, true);
};

var ErmisTemplateAjaxPost1 = function(e, elem, url, callback_true, callback_false) {
    e.preventDefault();
    var data = GetAllValueForm(elem);
    var postdata = {
        data: JSON.stringify(data)
    };
    RequestURLWaiting(url, 'json', postdata, function(result) {
        if (result.status === true) {
            callback_true(result);
        } else {
            callback_false(result);
        }
    }, true);
};

var ErmisTemplateAjaxPostAdd1 = function(e, elem, url, obj_add, callback_true, callback_false) {
    e.preventDefault();
    var data = GetAllValueForm(elem);
    // Merge object2 into object1
    $.extend(data, obj_add);
    var postdata = {
        data: JSON.stringify(data)
    };
    RequestURLWaiting(url, 'json', postdata, function(result) {
        if (result.status === true) {
            callback_true(result);
        } else {
            callback_false(result);
        }
    }, true);
};

var ErmisTemplateAjaxPost2 = function(e, data, url, callback_true, callback_false) {
    e.preventDefault();
    var c = GetDataAjax(data.columns, data.elem);
    var obj = c.obj;
    var crit = c.crit;
    var postdata = {
        data: JSON.stringify(obj)
    };
    RequestURLWaiting(url, 'json', postdata, function(result) {
        if (result.status === true) {
            callback_true(result);
        } else {
            callback_false(result);
        }
    }, true);
};

var ErmisTemplateAjaxPost3 = function(e, elem, url, callback_true, callback_false, callback) {
    //var token = jQuery('input[name="__RequestVerificationToken"]').val();
    //var headers = {};
    //headers['__RequestVerificationToken'] = token;
    var a = jQuery(elem).prop('files')[0];
    try {
        var FileUpload = new FormData(); // XXX: Neex AJAX2
        FileUpload.append('file', a.files[0]);
        // You could show a loading image for example...
        RequestFileURLWaiting(url, FileUpload, function(result) {
            if (result.status === true) {
                callback_true(result);
            } else {
                callback_false(result);
            }
            callback(result);
        }, true);
    } catch (e) {
        kendo.alert(Lang.get('messages.failed_import'));
    }
};

var ErmisTemplateAjaxPostAdd3 = function(e, elem, url, obj_add, callback_true, callback_false, callback) {
    //var token = jQuery('input[name="__RequestVerificationToken"]').val();
    //var headers = {};
    //headers['__RequestVerificationToken'] = token;
    var a = jQuery(elem).prop('files')[0];
    try {
        var FileUpload = new FormData(); // XXX: Neex AJAX2
        FileUpload.append("file", a.files[0]);
        FileUpload.append("data", JSON.stringify(obj_add));
        // You could show a loading image for example...
        RequestFileURLWaiting(url, FileUpload, function(result) {
            if (result.status === true) {
                var upload = jQuery("#files").data("kendoUpload");
                upload.clearAllFiles();
                callback_true(result);
            } else {
                callback_false(result);
            }
            callback(result);
        }, true);
    } catch (e) {
        kendo.alert(Lang.get('messages.failed_import'));
    }
};

var ErmisTemplateAjaxPost4 = function(e, ts, data, url, dataId, callback_true, callback_false, callback, crit_false) {
    var jQuerylink = jQuery(e.target);
    e.preventDefault();
    if (!jQuerylink.data('lockedAt') || +new Date() - jQuerylink.data('lockedAt') > 300) {
        $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('messages.message'))).then(function(confirmed) {
            if (confirmed) {
                var c = GetDataAjax(data.columns, data.elem);
                var obj = c.obj;
                var crit = c.crit;
                obj.id = dataId;
                if (ts != null && !obj.type) {
                    obj.type = ts;
                }
                if (crit === true) {
                    var postdata = {
                        data: JSON.stringify(obj)
                    };
                    RequestURLWaiting(url, 'json', postdata, function(result) {
                        if (result.status === true) {
                            callback_true(result, obj);
                        } else {
                            callback_false(result);
                        }
                        callback(result);
                    }, true);

                } else {
                    crit_false();
                }
            }
        });
    }
    jQuerylink.data('lockedAt', +new Date());
};

var ErmisTemplateAjaxPostAdd4 = function(e, ts, data, url, obj_add, dataId, callback_true, callback_false, callback, crit_false) {
    var jQuerylink = jQuery(e.target);
    e.preventDefault();
    if (!jQuerylink.data('lockedAt') || +new Date() - jQuerylink.data('lockedAt') > 300) {
        $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('messages.message'))).then(function(confirmed) {
            if (confirmed) {
                var c = GetDataAjax(data.columns, data.elem);
                var obj = c.obj;
                var crit = c.crit;
                obj.id = dataId;
                $.extend(obj, obj_add);
                if (ts != null && !obj.type) {
                    obj.type = ts;
                }
                if (crit === true) {
                    var postdata = {
                        data: JSON.stringify(obj)
                    };
                    RequestURLWaiting(url, 'json', postdata, function(result) {
                        if (result.status === true) {
                            callback_true(result, obj);
                        } else {
                            callback_false(result);
                        }
                        callback(result);
                    }, true);

                } else {
                    crit_false();
                }
            }
        });
    }
    jQuerylink.data('lockedAt', +new Date());
};

var ErmisTemplateAjaxPostAddImage4 = function(e, elem, ts, data, url, obj_add, dataId, callback_true, callback_false, callback, crit_false) {
    var jQuerylink = jQuery(e.target);
    e.preventDefault();
    if (!jQuerylink.data('lockedAt') || +new Date() - jQuerylink.data('lockedAt') > 300) {
        $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('messages.message'))).then(function(confirmed) {
            if (confirmed) {
                var c = GetDataAjax(data.columns, data.elem);
                var obj = c.obj;
                var crit = c.crit;
                obj.id = dataId;
                $.extend(obj, obj_add);
                if (ts != null && !obj.type) {
                    obj.type = ts;
                }

                var a = jQuery(elem)[0].files[0];
                var fd = new FormData(); // XXX: Neex AJAX2
                fd.append('files', a);
                fd.append('data', JSON.stringify(obj));
                // You could show a loading image for example...
                if (crit === true) {
                    RequestURLImage(url, 'json', fd, function(result) {
                        if (result.status === true) {
                            callback_true(result, obj);
                        } else {
                            callback_false(result);
                        }
                        callback(result);
                    }, true);

                } else {
                    crit_false();
                }
            }
        });
    }
    jQuerylink.data('lockedAt', +new Date());
};

var ErmisTemplateAjaxPost5 = function(e, data, url, callback_true, callback_false, callback) {
    var jQuerylink = jQuery(e.target);
    e.preventDefault();
    if (!jQuerylink.data('lockedAt') || +new Date() - jQuerylink.data('lockedAt') > 300) {
        var c = GetDataAjax(data.columns, data.elem);
        var obj = c.obj;
        var postdata = {
            data: JSON.stringify(obj)
        };
        RequestURLWaiting(url, 'json', postdata, function(result) {
            if (result.status === true) {
                callback_true(result, obj);
            } else {
                callback_false(result);
            }
            callback(result);
        }, true);
    }
    jQuerylink.data('lockedAt', +new Date());
};

var ErmisTemplateAjaxPost6 = function(e, ts, data, url, dataId, obj_add, crit2, callback_true, callback_false, callback, crit_false, crit_false2) {
    var jQuerylink = jQuery(e.target);
    e.preventDefault();
    if (!jQuerylink.data('lockedAt') || +new Date() - jQuerylink.data('lockedAt') > 300) {
        $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('messages.message'))).then(function(confirmed) {
            if (confirmed) {
                var c = GetDataAjax(data.columns, data.elem);
                var obj = c.obj;
                // Merge object2 into object1
                $.extend(obj, obj_add);
                var crit = c.crit;
                obj.id = dataId;
                if (ts != null && !obj.type) {
                    obj.type = ts;
                }
                if (crit === true) {
                    if (crit2) {
                        var postdata = {
                            data: JSON.stringify(obj)
                        };
                        RequestURLWaiting(url, 'json', postdata, function(result) {
                            if (result.status === true) {
                                callback_true(result, obj);
                            } else {
                                callback_false(result);
                            }
                            callback(result);
                        }, true);
                    } else {
                        crit_false();
                    }
                } else {
                    crit_false2();
                }
            }
        });
    }
    jQuerylink.data('lockedAt', +new Date());
};

var ErmisTemplateAjaxPost7 = function(e, elem, url, dataId, obj_add, crit2, callback_true, callback_false, callback, crit_false, crit_false2) {
    var jQuerylink = jQuery(e.target);
    e.preventDefault();
    if (!jQuerylink.data('lockedAt') || +new Date() - jQuerylink.data('lockedAt') > 300) {
        $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('messages.message'))).then(function(confirmed) {
            if (confirmed) {
                var c = GetDataAjaxTabstrip(elem);
                var obj = c.obj;
                // Merge object2 into object1
                $.extend(obj, obj_add);
                var crit = c.crit;
                obj.id = dataId;
                if (crit === true) {
                    if (crit2) {
                        var postdata = {
                            data: JSON.stringify(obj)
                        };
                        RequestURLWaiting(url, 'json', postdata, function(result) {
                            if (result.status === true) {
                                callback_true(result, obj);
                            } else {
                                callback_false(result);
                            }
                            callback(result);
                        }, true);
                    } else {
                        crit_false();
                    }
                } else {
                    crit_false2();
                }
            }
        });
    }
    jQuerylink.data('lockedAt', +new Date());
};

var ErmisTemplateAjaxPost8 = function(e, elem, elem_data, url, callback_true, callback_false, callback) {
    //var token = jQuery('input[name="__RequestVerificationToken"]').val();
    //var headers = {};
    //headers['__RequestVerificationToken'] = token;
    var a = jQuery(elem)[0].files[0];
    var data = GetAllDataForm(elem_data);
    var arr = GetDataAjax(data.columns, data.elem);
    try {
        var fd = new FormData(); // XXX: Neex AJAX2
        fd.append('files', a);
        fd.append('data', JSON.stringify(arr.obj));
        // You could show a loading image for example...
        RequestFileURLWaiting(url, 'json', fd, function(result) {
            if (result.status === true) {
                callback_true(result);
            } else {
                callback_false(result);
            }
            callback(result);
        }, true);
    } catch (e) {
        kendo.alert(Lang.get('messages.failed_import'));
    }
};


var ErmisTemplateAjaxPost9 = function(e, elem, name, url, callback_true, callback_false, callback) {
    try {
        var fd = new FormData(); // XXX: Neex AJAX2
        var a = jQuery(elem)[0].files[0];
        fd.append(name, a);
        // You could show a loading image for example...
        RequestURLImage(url, 'json', fd, function(result) {
            if (result.status === true) {
                callback_true(result);
            } else {
                callback_false(result);
            }
            callback(result);
        }, true);
    } catch (e) {
        kendo.alert(Lang.get('messages.error'));
    }
};

var ErmisTemplateAjaxPost10 = function(e, elem, elem_data, url, dataId, obj_add, crit2, callback_true, callback_false, callback, crit_false, crit_false2) {
    var jQuerylink = jQuery(e.target);
    e.preventDefault();
    if (!jQuerylink.data('lockedAt') || +new Date() - jQuerylink.data('lockedAt') > 300) {
        $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('messages.message'))).then(function(confirmed) {
            if (confirmed) {
                var a = jQuery(elem)[0].files;
                var c = GetDataAjaxTabstrip(elem_data);
                var obj = c.obj;
                // Merge object2 into object1
                $.extend(obj, obj_add);
                var crit = c.crit;
                obj.id = (dataId ? dataId : null);
                if (crit === true) {
                    if (crit2) {
                        var fd = new FormData(); // XXX: Neex AJAX2
                        fd.append('files', a);
                        fd.append('data', JSON.stringify(obj));
                        RequestURLImage(url, 'json', fd, function(result) {
                            if (result.status === true) {
                                callback_true(result, obj);
                            } else {
                                callback_false(result);
                            }
                            callback(result);
                        }, true);
                    } else {
                        crit_false();
                    }
                } else {
                    crit_false2();
                }
            }
        });
    }
    jQuerylink.data('lockedAt', +new Date());
};

var ErmisTemplateAjaxPost11 = function(e, elem, elem_data, url, dataId, obj_add, crit2, callback_true, callback_false, callback, crit_false, crit_false2) {
    var jQuerylink = jQuery(e.target);
    e.preventDefault();
    if (!jQuerylink.data('lockedAt') || +new Date() - jQuerylink.data('lockedAt') > 300) {
        $.when(KendoUiConfirm(Lang.get('messages.are_you_sure'), Lang.get('messages.message'))).then(function(confirmed) {
            if (confirmed) {
                var a = jQuery(elem)[0].files;
                var c = GetDataAjax(elem_data);
                var obj = c.obj;
                // Merge object2 into object1
                $.extend(obj, obj_add);
                var crit = c.crit;
                obj.id = (dataId ? dataId : null);
                if (crit === true) {
                    if (crit2) {
                        var fd = new FormData(); // XXX: Neex AJAX2
                        fd.append('files', a);
                        fd.append('data', JSON.stringify(obj));
                        RequestURLImage(url, 'json', fd, function(result) {
                            if (result.status === true) {
                                callback_true(result, obj);
                            } else {
                                callback_false(result);
                            }
                            callback(result);
                        }, true);
                    } else {
                        crit_false();
                    }
                } else {
                    crit_false2();
                }
            }
        });
    }
    jQuerylink.data('lockedAt', +new Date());
};

var ErmisTemplateEvent0 = function(e, crit, crit_true, crit_false) {
    var jQuerylink = jQuery(e.target);
    e.preventDefault();
    if (!jQuerylink.data('lockedAt') || +new Date() - jQuerylink.data('lockedAt') > 300) {
        if (crit) {
            crit_true();
        } else {
            crit_false();
        }
    }
    jQuerylink.data('lockedAt', +new Date());
};

var ErmisTemplateEvent1 = function(e, result) {
    var jQuerylink = jQuery(e.target);
    e.preventDefault();
    if (!jQuerylink.data('lockedAt') || +new Date() - jQuerylink.data('lockedAt') > 300) {
        result();
    }
    jQuerylink.data('lockedAt', +new Date());
};

var ErmisTemplateEvent3 = function(e, callback) {
    var jQuerylink = jQuery(e.target);
    e.preventDefault();
    if (!jQuerylink.data('lockedAt') || +new Date() - jQuerylink.data('lockedAt') > 300) {
        callback();
    }
    jQuerylink.data('lockedAt', +new Date());
};

var ErmisKendoEditorFullTemplate = function(elem) {
    jQuery(elem).kendoEditor({
        tools: [
            "bold",
            "italic",
            "underline",
            "strikethrough",
            "justifyLeft",
            "justifyCenter",
            "justifyRight",
            "justifyFull",
            "insertUnorderedList",
            "insertOrderedList",
            "indent",
            "outdent",
            "createLink",
            "unlink",
            "insertImage",
            "insertFile",
            "subscript",
            "superscript",
            "tableWizard",
            "createTable",
            "addRowAbove",
            "addRowBelow",
            "addColumnLeft",
            "addColumnRight",
            "deleteRow",
            "deleteColumn",
            "viewHtml",
            "formatting",
            "cleanFormatting",
            "fontName",
            "fontSize",
            "foreColor",
            "backColor",
            "print"
        ]
    });
};

var ErmisKendoButtonTemplate = function(elem, icon) {
    jQuery(elem).kendoButton({
        icon: icon
    });
};

var ErmisHandsontableTemplate = function(hot, container, column, lang, data) {
    //if(hot){
    //hot.destroy();
    //};
    //if(column.dataSchema != null){
    if (column.dataSchema.length == 0) {
        column.dataSchema = null;
    };
    //};
    hot = new Handsontable(container, {
        dataSchema: column.dataSchema,
        data: data,
        colHeaders: column.ArrayHeaders,
        rowHeaders: true,
        //                  fixedColumnsLeft: 2,
        //                  manualColumnFreeze: true,
        height: (0.3 * $(window).height()),
        minSpareRows: 1,
        manualColumnResize: true,
        manualRowResize: true,
        language: lang,
        // as a boolean
        contextMenu: true,
        // as a array
        contextMenu: ['row_above', 'row_below', 'remove_row', '---------', 'undo', 'redo'],
        //                  stretchH: 'all',
        columns: column.ArrayColumn,
        hiddenColumns: {
            columns: [0],
            indicators: true
        },
        licenseKey: 'non-commercial-and-evaluation'
    });
    return hot;

}

var ErmisKendoGridVoucherTemplate = function($kGrid, dataSource, selectable, height, pageable, colunm) {
    var grid = $kGrid.kendoGrid({
        dataSource: dataSource,
        selectable: selectable,
        height: height,
        sortable: true,
        pageable: pageable,
        filterable: true,
        columns: colunm,
        dataBound: function() {
            var rows = this.items();
            var data = this.dataSource.data();
            $(rows).each(function(i, row) {
                var index = $(this).index() + 1;
                var rowLabel = $(this).find(".row-number");
                $(rowLabel).html(index);
                if (data[index - 1].get("active") == 0) {
                    var element = $('tr[data-uid="' + data[index - 1].uid + '"] ');
                    element.addClass("color_none_active");
                }
            });
        },
    }).data("kendoGrid");

    grid.thead.kendoTooltip({
        filter: "th",
        content: function(e) {
            var target = e.target; // element for which the tooltip is shown
            return $(target).text();
        }
    });
};

var ErmisKendoGridCheckboxTemplate = function($kGrid, data, height, pageSize, selectable, key, get, colunm, result) {
    if ($kGrid.length > 0) {
        var grid = $kGrid.kendoGrid({
          dataSource: {
              pageSize: pageSize,
              data: data,
              schema: {
                  model: {
                      id: get
                  }
              }
          },
            height: height,
            selectable: selectable,
            dataBound: onDataBound,
            sortable: true,
            pageable: true,
            filterable: true,
            columns: colunm
        }).data("kendoGrid");

        grid.thead.kendoTooltip({
            filter: "th",
            content: function(e) {
                var target = e.target; // element for which the tooltip is shown
                return $(target).text();
            }
        });

        grid.table.on("click", ".k-checkbox." + key, selectRow);
        //bind click event to the checkbox
        //grid.table.on("click", ".k-checkbox" , selectRow);
        jQuery('#header-chb-' + key).change(function(ev) {
            var checked = ev.target.checked;
            jQuery('.k-checkbox.' + key).not('#header-chb-' + key).each(function(idx, item) {
                if (checked) {
                    if (!$(item).is(':checked')) {
                        $(item).click();
                    }
                } else {
                    if ($(item).is(':checked')) {
                        $(item).click();
                    }
                }
            });

        });
        //if(check_all == true){
        //  $('#header-chb-'+key).on("click", ".k-checkbox."+key, selectRow);
        //};

        jQuery(".choose-" + key).bind("click", function() {
            result(checkedData);
        });
        var checkedData = [];
        //on click of the checkbox:
        function selectRow() {
            var checked = this.checked,
                row = $(this).closest("tr"),
                grid = $kGrid.data("kendoGrid"),
                dataItem = grid.dataItem(row);
                var total = parseInt(ConvertNumber(jQuery(".total-"+ key).text(),Ermis.decimal_symbol));
            if (checked) {
                jQuery(".total-"+ key).text(FormatNumberDecimal(total+parseInt(dataItem.total_amount),Ermis.decimal))
                //-select the row
                checkedData.push(dataItem);
                row.addClass("k-state-selected");

                var checkHeader = true;

            jQuery.each(grid.items(), function (index, item) {
                if (!(jQuery(item).hasClass("k-state-selected"))) {
                    checkHeader = false;
                }
            });
            var a = jQuery('.k-checkbox.' + key+":checked").not('#header-chb-' + key).length;
            if(a == grid.items().length){
                checkHeader = true;
            };
             jQuery('#header-chb-' + key)[0].checked = checkHeader;
            } else {
                jQuery(".total-"+ key).text(FormatNumberDecimal(total-parseInt(dataItem.total_amount),Ermis.decimal));
                checkedData = checkedData.filter(x => x.id != dataItem.id)
                //-remove selection
                row.removeClass("k-state-selected");
                 jQuery('#header-chb-' + key)[0].checked = false;
            }
        }

        //on dataBound event restore previous selected rows:
    function onDataBound(e) {
        var view = this.dataSource.view();
        var checkHeader = 0;
        for (var i = 0; i < view.length; i++) {
          if(checkedData.filter(el => el.id == view[i].id).length > 0){
            this.tbody.find("tr[data-uid='" + view[i].uid + "']")
                .addClass("k-state-selected")
                .find(".k-checkbox")
                .attr("checked", "checked");
                checkHeader++;
            }
          };
          if(checkHeader<view.length){
            jQuery('#header-chb-' + key)[0].checked = false;
          }else if(checkHeader == view.length && view.length >0){
            jQuery('#header-chb-' + key)[0].checked = true;
          }
        }
    }
};

var ErmisKendoGridCheckboxTemplate1 = function($kGrid, data, height, pageSize, column, onChange, onDataBound, get) {
    var oldPageSize = 0;
    $kGrid.kendoGrid({
        dataSource: {
            pageSize: pageSize,
            data: data,
            schema: {
                model: {
                    id: get
                }
            }
        },
        dataBound: onDataBound,
        pageable: true,
        scrollable: false,
        persistSelection: true,
        sortable: true,
        height: height,
        change: onChange,
        columns: column
    });

    function onClick(e) {
        var grid = $kGrid.data("kendoGrid");

        oldPageSize = grid.dataSource.pageSize();
        grid.dataSource.pageSize(grid.dataSource.data().length);

        if (grid.dataSource.data().length === grid.select().length) {
            grid.clearSelection();
        } else {
            grid.select("tr");
        };
        grid.dataSource.pageSize(oldPageSize);
    };

    var grid = $kGrid.data("kendoGrid");
    oldPageSize = grid.dataSource.pageSize();
    grid.dataSource.pageSize(grid.dataSource.data().length);
    grid.select("tr");
    grid.dataSource.pageSize(oldPageSize);

    grid.thead.on("click", ".k-checkbox", onClick);

}
var ErmisKendoGridTemplate5 = function($kGrid, height, dataSource, scrollable, sortable, pageable, columns) {
    var grid = $kGrid.kendoGrid({
        height: height,
        dataSource: dataSource,
        scrollable: scrollable,
        sortable: sortable,
        pageable: pageable,
        columns: columns

    });
    grid.data("kendoGrid").thead.kendoTooltip({
        filter: "th",
        content: function(e) {
            var target = e.target; // element for which the tooltip is shown
            return $(target).text();
        }
    });
}

var ErmisKendoGridTemplate4 = function($kGrid, data, aggregate, pageSize, pageable, height, columns) {
    dataSource = {
        data: data,
        pageSize: pageSize,
        aggregate: aggregate
    }
    var grid = $kGrid.kendoGrid({
        dataSource: dataSource,
        selectable: "row",
        height: height,
        filterable: true,
        groupable: true,
        sortable: true,
        pageable: pageable,
        columns: columns
    });

    grid.data("kendoGrid").thead.kendoTooltip({
        filter: "th",
        content: function(e) {
            var target = e.target; // element for which the tooltip is shown
            return $(target).text();
        }
    });
};

var ErmisKendoGridTemplate3 = function($kGrid, data, aggregate, field, pageSize, editable, height, columns) {
    dataSource = new kendo.data.DataSource({
        data: data,
        aggregate: aggregate,
        batch: true,
        autoBind: true,
        pageSize: pageSize,
        schema: {
            model: {
                id: "id",
                fields: field
            }
        }
    });
    var grid = $kGrid.kendoGrid({
        dataSource: dataSource,
        save: function(data) {
            var grid = this;
            setTimeout(function() {
                grid.refresh();
            });
        },
        editable: editable,
        height: height,
        columns: columns,
        navigatable: true
    });
    grid.data("kendoGrid").thead.kendoTooltip({
        filter: "th",
        position: "top",
        content: function(e) {
            var target = e.target; // element for which the tooltip is shown
            return $(target).text();
        }
    });
};

var ErmisKendoGridTemplate2 = function($kGrid, dataSource, onChange, selectable, height, pageable, columns) {
    var grid = $kGrid.kendoGrid({
        dataSource: dataSource,
        change: onChange,
        selectable: selectable,
        height: height,
        groupable: true,
        sortable: true,
        pageable: pageable,
        filterable: true,
        columns: columns,
        dataBound: function() {
            var rows = this.items();
            var data = this.dataSource.data();
            $(rows).each(function(i, row) {
                var index = $(this).index() + 1;
                var rowLabel = $(this).find(".row-number");
                $(rowLabel).html(index);
                if (data[index - 1].get("active") == 0) {
                    var element = $('tr[data-uid="' + data[index - 1].uid + '"] ');
                    element.addClass("color_none_active");
                }
            });
        },
    });
    grid.data("kendoGrid").thead.kendoTooltip({
        filter: "th",
        content: function(e) {
            var target = e.target; // element for which the tooltip is shown
            return $(target).text();
        }
    });
};

var ErmisKendoGridTemplate1 = function(elem, data, pagesize, field, aggregate, height, edittable, change, pageable) {
    dataSource = new kendo.data.DataSource({
        data: data,
        batch: false,
        pageSize: pagesize,
        schema: {
            model: {
                id: "id",
                fields: field
            }
        },
        aggregate: aggregate,
    });
    var grid = $kGrid.kendoGrid({
        dataSource: dataSource,
        save: function(data) {
            var grid = this;
            setTimeout(function() {
                grid.refresh();
            });
        },
        height: height,
        editable: edittable,
        selectable: "row",
        filterable: true,
        change: change,
        pageable: pageable,
        columns: Ermis.columns
    });

    grid.data("kendoGrid").thead.kendoTooltip({
        filter: "th",
        content: function(e) {
            var target = e.target; // element for which the tooltip is shown
            return $(target).text();
        }
    });

};

var ErmisKendoGridTemplate0 = function($kGrid, pageSize, data, onChange, selectable, height, pageable, fields, columns) {
    var dataSource = new kendo.data.DataSource({
        pageSize: pageSize,
        data: data
    });
    var grid = $kGrid.kendoGrid({
        dataSource: dataSource,
        change: onChange,
        selectable: selectable,
        height: height,
        scrollable: {
            endless: true
        },
        groupable: true,
        sortable: true,
        pageable: pageable,
        filterable: true,
        schema: {
            model: {
                id: "id",
                fields: fields
            }
        },
        columns: columns,
    });
    grid.data("kendoGrid").thead.kendoTooltip({
        filter: "th",
        content: function(e) {
            var target = e.target; // element for which the tooltip is shown
            return $(target).text();
        }
    });
};

var ErmisKendoGridTemplateToolTip0 = function($kGrid, pageSize, data, onChange, selectable, height, pageable, fields, columns, elemTooltip, filterTooltip, toolTip) {
    var dataSource = new kendo.data.DataSource({
        pageSize: pageSize,
        data: data
    });
    var grid = $kGrid.kendoGrid({
        dataSource: dataSource,
        change: onChange,
        selectable: selectable,
        height: height,
        scrollable: {
            endless: true
        },
        groupable: true,
        sortable: true,
        pageable: pageable,
        filterable: true,
        schema: {
            model: {
                id: "id",
                fields: fields
            }
        },
        columns: columns,
    });
    grid.data("kendoGrid").thead.kendoTooltip({
        filter: "th",
        content: function(e) {
            var target = e.target; // element for which the tooltip is shown
            return $(target).text();
        }
    });

    jQuery(elemTooltip).kendoTooltip({
        filter: filterTooltip,
        content: toolTip,
        position: "top",
    });
};

var ErmisKendoGridTemplate = function($kGrid, pageSize, data, onChange, selectable, height, pageable, fields, columns) {
    var dataSource = new kendo.data.DataSource({
        pageSize: pageSize,
        transport: {
            read: function(e) {
                e.success(data);
            },
        },
        serverPaging: true,
    });
    var grid = $kGrid.kendoGrid({
        dataSource: dataSource,
        change: onChange,
        selectable: selectable,
        height: height,
        scrollable: {
            virtual: true
        },
        groupable: true,
        sortable: true,
        pageable: pageable,
        filterable: true,
        schema: {
            model: {
                id: "id",
                fields: fields
            }
        },
        columns: columns,
    });
    grid.data("kendoGrid").thead.kendoTooltip({
        filter: "th",
        content: function(e) {
            var target = e.target; // element for which the tooltip is shown
            return $(target).text();
        }
    });
};

var ErmisKendoGridTemplateDefault = function($kGrid, pageSize, data, onChange, selectable, height, pageable, fields, columns) {
    var dataSource = new kendo.data.DataSource({
        pageSize: pageSize,
        transport: {
            read: function(e) {
                e.success(data);
            },
        }
    });
    var grid = $kGrid.kendoGrid({
        dataSource: dataSource,
        change: onChange,
        selectable: selectable,
        height: height,
        groupable: true,
        sortable: true,
        pageable: pageable,
        filterable: true,
        schema: {
            model: {
                fields: fields
            }
        },
        columns: columns,
        dataBound: function() {
            var rows = this.items();
            $(rows).each(function() {
                var index = $(this).index() + 1;
                var rowLabel = $(this).find(".row-number");
                $(rowLabel).html(index);
            });
        },
    });
    grid.data("kendoGrid").thead.kendoTooltip({
        filter: "th",
        content: function(e) {
            var target = e.target; // element for which the tooltip is shown
            return $(target).text();
        }
    });
};

var ErmisKendoGridTemplateDefaultTooltip0 = function($kGrid, pageSize, data, onChange, selectable, height, pageable, fields, columns, elemTooltip, filterTooltip, toolTip) {
    var dataSource = new kendo.data.DataSource({
        pageSize: pageSize,
        transport: {
            read: function(e) {
                e.success(data);
            },
        }
    });
    var grid = $kGrid.kendoGrid({
        dataSource: dataSource,
        change: onChange,
        selectable: selectable,
        height: height,
        groupable: true,
        sortable: true,
        pageable: pageable,
        filterable: true,
        schema: {
            model: {
                fields: fields
            }
        },
        columns: columns,
        dataBound: function() {
            var rows = this.items();
            $(rows).each(function() {
                var index = $(this).index() + 1;
                var rowLabel = $(this).find(".row-number");
                $(rowLabel).html(index);
            });
        },
    });
    grid.data("kendoGrid").thead.kendoTooltip({
        filter: "th",
        content: function(e) {
            var target = e.target; // element for which the tooltip is shown
            return $(target).text();
        }
    });

    jQuery(elemTooltip).kendoTooltip({
        filter: filterTooltip,
        content: toolTip,
        position: "top",
    });
};

var ErmisKendoGridAddData = function($kGrid,data,page_size,field){
  var grid = $kGrid.data("kendoGrid");
  var ds = new kendo.data.DataSource({
      data: data,
      pageSize: page_size,
      schema: {
          model: {
              fields: field
          }
      }
  });
  grid.setDataSource(ds);
  grid.dataSource.page(1);
}


var ErmisKendoTreeViewTemplate = function($kGrid, data, parentId, expanded, onChange, selectable, height, pageable, fields, columns) {
    var dataSource = new kendo.data.TreeListDataSource({
        data: data,
        schema: {
            model: {
                id: "id",
                parentId: parentId,
                fields: fields,
                expanded: expanded
            },

        }
    });
    var grid = $kGrid.kendoTreeList({
        dataSource: dataSource,
        change: onChange,
        selectable: selectable,
        height: height,
        groupable: true,
        sortable: true,
        pageable: pageable,
        filterable: true,
        columns: columns
    });

    grid.data("kendoTreeList").thead.kendoTooltip({
        filter: "th",
        content: function(e) {
            var target = e.target; // element for which the tooltip is shown
            return $(target).text();
        }
    });
};

var ErmisKendoWindowTemplate = function(elem, $width, $title) {
    var window = elem.kendoWindow({
        width: $width,
        title: $title,
        visible: false,
        actions: [
            "Pin",
            "Minimize",
            "Maximize",
            "Close"
        ],
        modal: true
    }).data("kendoWindow").center().close();
    return window;
};

var ErmisKendoWindowTemplate1 = function(elem, $width, $title, onClose) {
    var window = elem.kendoWindow({
        width: $width,
        title: $title,
        visible: false,
        actions: [
            "Pin",
            "Minimize",
            "Maximize",
            "Close"
        ],
        modal: true,
        close: onClose
    }).data("kendoWindow").center().close();
    return window;
};

var ErmisKendoDialogTemplate = function(elem, $width, $title, content, title1, title2, title3, action1, action2) {
    var dialog = jQuery(elem).kendoDialog({
        width: $width,
        title: $title,
        closable: true,
        content: content,
        modal: true,
        actions: [{
                text: title1,
                action: action1
            },
            {
                text: title2,
                action: action2
            },
            {
                text: title3,
                primary: true
            }
        ]
    });
    return dialog
};

var ErmisKendoDialogTemplate1 = function(elem, $width, $title, content, title1, title2, action1) {
    var dialog = jQuery(elem).kendoDialog({
        width: $width,
        title: $title,
        closable: true,
        content: content,
        modal: true,
        actions: [{
                text: title1,
                action: action1
            },
            {
                text: title2,
                primary: true
            }
        ]
    });
    return dialog
};

var ErmisKendoComboboxTemplate = function(elem, text, value, filter, data) {
    jQuery(elem).kendoComboBox({
        dataTextField: text,
        dataValueField: value,
        filter: filter,
        dataSource: data
    });
};

var ErmisKendoDroplistTemplate = function(elem, filter) {
    jQuery(elem).kendoDropDownList({
        filter: filter,
        autoBind: false,
    });
};

var ErmisKendoDroplistTemplate1 = function(elem, filter, onChange) {
    jQuery(elem).kendoDropDownList({
        filter: filter,
        autoBind: false,
        change: onChange
    });
};

var ErmisKendoMonthPickerTemplate = function(elem, start, depth, format) {
    jQuery(elem).kendoDatePicker({
        // defines the start view
        start: start,

        // defines when the calendar should return date
        depth: depth,

        // display month and year in the input
        format: format,

        // specifies that DateInput is used for masking the input element
        dateInput: true
    });
};

var ErmisKendoDatePickerTemplate = function(elem, format) {
    jQuery(elem).kendoDatePicker({
        format: format
    });
};

var ErmisKendoStartPickerTemplate = function(elem, format) {
    start = jQuery(elem).kendoDatePicker({
        change: startChange,
        format: format
    }).data("kendoDatePicker");

    function startChange() {
        var startDate = start.value(),
            endDate = end.value();

        if (startDate) {
            startDate = new Date(startDate);
            startDate.setDate(startDate.getDate());
            end.min(startDate);
        } else if (endDate) {
            start.max(new Date(endDate));
        } else {
            endDate = new Date();
            start.max(endDate);
            end.min(endDate);
        }
    }
};

var ErmisKendoStartEndDroplistTemplate = function(elemStart, elemEnd, format, elem, filter) {
    var start = jQuery(elemStart).kendoDatePicker({
        change: startChange,
        format: format
    }).data("kendoDatePicker");
    var end = jQuery(elemEnd).kendoDatePicker({
        change: endChange,
        format: format
    }).data("kendoDatePicker");

    function startChange() {
        var startDate = start.value(),
            endDate = end.value();

        if (startDate) {
            startDate = new Date(startDate);
            startDate.setDate(startDate.getDate());
            end.min(startDate);
        } else if (endDate) {
            start.max(new Date(endDate));
        } else {
            endDate = new Date();
            start.max(endDate);
            end.min(endDate);
        }
    }

    function endChange() {
        var endDate = end.value(),
            startDate = start.value();

        if (endDate) {
            endDate = new Date(endDate);
            endDate.setDate(endDate.getDate());
            start.max(endDate);
        } else if (startDate) {
            end.min(new Date(startDate));
        } else {
            endDate = new Date();
            start.max(endDate);
            end.min(endDate);
        }
    }
    jQuery(elem).kendoDropDownList({
        filter: filter,
        select: onSelect
    });

    function onSelect(e) {
        if (e.item) {
            var dataItem = this.dataItem(e.item);
            var year = '';
            var result = '';
            if (dataItem.value === "today") {
                end.value(new Date);
                start.value(new Date);
            } else if (dataItem.value === "this_week") {
                end.value(moment().endOf('week').format("DD/MM/YYYY"));
                start.value(moment().startOf('week').format("DD/MM/YYYY"));
            } else if (dataItem.value === "this_month") {
                end.value(moment().endOf('month').format("DD/MM/YYYY"));
                start.value(moment().startOf('month').format("DD/MM/YYYY"));
            } else if (dataItem.value === "this_quarter") {
                end.value(moment().endOf('quarter').format("DD/MM/YYYY"));
                start.value(moment().startOf('quarter').format("DD/MM/YYYY"));
            } else if (dataItem.value === "this_year") {
                end.value(moment().endOf('year').format("DD/MM/YYYY"));
                start.value(moment().startOf('year').format("DD/MM/YYYY"));
            } else if (dataItem.value === "january") {
                year = moment().format('YYYY');
                result = getMonthDateRange(year, "01");
                end.value(result.end.format("DD/MM/YYYY"));
                start.value(result.start.format("DD/MM/YYYY"));
            } else if (dataItem.value === "february") {
                year = moment().format('YYYY');
                result = getMonthDateRange(year, "02");
                end.value(result.end.format("DD/MM/YYYY"));
                start.value(result.start.format("DD/MM/YYYY"));
            } else if (dataItem.value === "march") {
                year = moment().format('YYYY');
                result = getMonthDateRange(year, "03");
                end.value(result.end.format("DD/MM/YYYY"));
                start.value(result.start.format("DD/MM/YYYY"));
            } else if (dataItem.value === "april") {
                year = moment().format('YYYY');
                result = getMonthDateRange(year, "04");
                end.value(result.end.format("DD/MM/YYYY"));
                start.value(result.start.format("DD/MM/YYYY"));
            } else if (dataItem.value === "may") {
                year = moment().format('YYYY');
                result = getMonthDateRange(year, "05");
                end.value(result.end.format("DD/MM/YYYY"));
                start.value(result.start.format("DD/MM/YYYY"));
            } else if (dataItem.value === "june") {
                year = moment().format('YYYY');
                result = getMonthDateRange(year, "06");
                end.value(result.end.format("DD/MM/YYYY"));
                start.value(result.start.format("DD/MM/YYYY"));
            } else if (dataItem.value === "july") {
                year = moment().format('YYYY');
                result = getMonthDateRange(year, "07");
                end.value(result.end.format("DD/MM/YYYY"));
                start.value(result.start.format("DD/MM/YYYY"));
            } else if (dataItem.value === "august") {
                year = moment().format('YYYY');
                result = getMonthDateRange(year, "08");
                end.value(result.end.format("DD/MM/YYYY"));
                start.value(result.start.format("DD/MM/YYYY"));
            } else if (dataItem.value === "september") {
                year = moment().format('YYYY');
                result = getMonthDateRange(year, "09");
                end.value(result.end.format("DD/MM/YYYY"));
                start.value(result.start.format("DD/MM/YYYY"));
            } else if (dataItem.value === "october") {
                year = moment().format('YYYY');
                result = getMonthDateRange(year, "10");
                end.value(result.end.format("DD/MM/YYYY"));
                start.value(result.start.format("DD/MM/YYYY"));
            } else if (dataItem.value === "november") {
                year = moment().format('YYYY');
                result = getMonthDateRange(year, "11");
                end.value(result.end.format("DD/MM/YYYY"));
                start.value(result.start.format("DD/MM/YYYY"));
            } else if (dataItem.value === "december") {
                year = moment().format('YYYY');
                result = getMonthDateRange(year, "12");
                end.value(result.end.format("DD/MM/YYYY"));
                start.value(result.start.format("DD/MM/YYYY"));
            } else if (dataItem.value === "the_1st_quarter") {
                year = moment().format('YYYY');
                result = getQuarterDateRange(year, "01");
                end.value(result.end.format("DD/MM/YYYY"));
                start.value(result.start.format("DD/MM/YYYY"));
            } else if (dataItem.value === "the_2nd_quarter") {
                year = moment().format('YYYY');
                result = getQuarterDateRange(year, "04");
                end.value(result.end.format("DD/MM/YYYY"));
                start.value(result.start.format("DD/MM/YYYY"));
            } else if (dataItem.value === "the_3rd_quarter") {
                year = moment().format('YYYY');
                result = getQuarterDateRange(year, "07");
                end.value(result.end.format("DD/MM/YYYY"));
                start.value(result.start.format("DD/MM/YYYY"));
            } else if (dataItem.value === "the_4th_quarter") {
                year = moment().format('YYYY');
                result = getQuarterDateRange(year, "10");
                end.value(result.end.format("DD/MM/YYYY"));
                start.value(result.start.format("DD/MM/YYYY"));
            }
        }
    }
};


var ErmisKendoEndPickerTemplate = function(elem, format) {
    end = jQuery(elem).kendoDatePicker({
        change: endChange,
        format: format
    }).data("kendoDatePicker");

    function endChange() {
        var endDate = end.value(),
            startDate = start.value();

        if (endDate) {
            endDate = new Date(endDate);
            endDate.setDate(endDate.getDate());
            start.max(endDate);
        } else if (startDate) {
            end.min(new Date(startDate));
        } else {
            endDate = new Date();
            start.max(endDate);
            end.min(endDate);
        }
    }
};

var ErmisKendoTabstripAjaxTemplate = function(jqueryElem, dataAttr, url, callback_true, callback_false) {
    jqueryElem.kendoTabStrip({
        select: onSelected
    });
    jqueryElem.show();

    function onSelected(e) {
        var search = jQuery(e.item).attr(dataAttr);
        var postdata = {
            data: search
        };
        RequestURLWaiting(url, 'json', postdata, function(result) {
            if (result.status === true) {
                callback_true(result);
            } else {
                callback_false(result);
            }
        }, true);
    }
};

var ErmisKendoContextMenuTemplate = function(elem, target) {
    jQuery(elem).kendoContextMenu({
        target: target
    });
};

var ErmisKendoUploadTemplate = function(elem, multiple) {
    jQuery(elem).kendoUpload({
        "multiple": multiple
    });
};

var ErmisKendoMultiSelectTemplate = function(elem, autoClose, tagTemplate) {
    jQuery(elem).kendoMultiSelect({
        autoClose: autoClose,
        tagTemplate: (tagTemplate),
        //select: onSelectMultil
    });
};

var ErmisKendoNumbericTemplate = function(elem, format, decimals, min, max, step) {
    jQuery(elem).kendoNumericTextBox({
        format: format,
        decimals: decimals,
        min: min,
        max: max,
        step: step
    });
};

var ErmisKendoColorPickerTemplate = function(elem, buttons, value) {
    jQuery(elem).kendoColorPicker({
        buttons: buttons,
        value: value
    })
};

var ErmisKendoTimePickerTemplate = function(elemStart, elemEnd) {
    function startChange() {
        var startTime = start.value();

        if (startTime) {
            startTime = new Date(startTime);

            end.max(startTime);

            startTime.setMinutes(startTime.getMinutes() + this.options.interval);

            end.min(startTime);
            end.value(startTime);
        }
    }

    //init start timepicker
    var start = jQuery(elemStart).kendoTimePicker({
        change: startChange
    }).data("kendoTimePicker");

    //init end timepicker
    var end = jQuery(elemEnd).kendoTimePicker().data("kendoTimePicker");
};

var ErmisTooltipMaxlenght = function(elem) {
    jQuery(elem).each(function() {
        jQuery(this).kendoTooltip({
            placement: 'bottom',
            trigger: 'focus',
            html: true,
            content: function(e) {
                var target = e.target; // the element for which the tooltip is shown
                return '<span class="n-char-' + target.data("name") + '">' + target.val().length + '</span> / ' + target.context.maxLength + ' ' + Lang.get('messages.characters_used');
            }

        });

        jQuery(this).on('keyup', function() {
            var a = jQuery(this).data("name");
            jQuery('.k-tooltip .k-tooltip-content .n-char-' + a).text($(this).val().length);
        });
    });
};
