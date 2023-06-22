$(function() {
    var z = false;
    altair_wizard.advanced_wizard(), altair_wizard.vertical_wizard(),altair_wizard.checkuser_company()
}), altair_wizard = {
    content_height: function(t, i) {
        var e = $(t).find(".step-" + i).actual("outerHeight");
        $(t).children(".content").animate({
            height: e
        }, 280, bez_easing_swiftOut)
    },
    advanced_wizard: function() {
        var t = $("#wizard_advanced"),
            i = $("#wizard_advanced_form");
        t.length && (t.steps({
            headerTag: "h3",
            bodyTag: "section",
            transitionEffect: "slideLeft",
            trigger: "change",
            onInit: function(i, e) {
                altair_wizard.content_height(t, e), altair_md.checkbox_radio($(".wizard-icheck")), altair_uikit.reinitialize_grid_margin(), altair_forms.select_elements(t), t.find("span.switchery").remove(), altair_forms.switches(), $(".uk-accordion").on("toggle.uk.accordion", function() {
                    $window.resize()
                }), setTimeout(function() {
                    $window.resize()
                }, 100)
            },
            onStepChanged: function(i, e) {
                altair_wizard.content_height(t, e), setTimeout(function() {
                    $window.resize()
                }, 100)
            },
            onStepChanging: function(i, e, n) {
              if(z == true){
                var a = t.find(".body.current").attr("data-step"),
                    r = $('.body[data-step="' + a + '"]');
                return r.find("[data-parsley-id]").each(function() {
                    $(this).parsley().validate()
                }), $window.resize(), r.find(".md-input-danger").length ? !1 : !0
              }
            },
            onFinished: function() {
              jQuery(i).parsley().validate();
              var a = jQuery(i).parsley().isValid();
                if(a){
                  var t = JSON.stringify(i.serializeObject(), null, 2);
                  var postdata = { data: t };
                  ErmisTemplateAjaxPost0($(this) ,postdata,'register',
                  function(){
                    var username = i.serializeObject().username;
                     window.location.href = 'welcome/'+username;
                  },
                  function(result){
                      UIkit.modal.alert(result.message);
                  })
                }
            }
        }), $window.on("debouncedresize", function() {
            var i = t.find(".body.current").attr("data-step");
            altair_wizard.content_height(t, i)
        }), i.parsley().on("form:validated", function() {
            setTimeout(function() {
                altair_md.update_input(i.find(".md-input")), $window.resize()
            }, 100)
        }).on("field:validated", function(i) {
            var e = $(i.$element);
            setTimeout(function() {
                altair_md.update_input(e);
                var i = t.find(".body.current").attr("data-step");
                altair_wizard.content_height(t, i)
            }, 100)
        }))
    },
    vertical_wizard: function() {
        var t = $("#wizard_vertical");
        t.length && t.steps({
            headerTag: "h3",
            bodyTag: "section",
            enableAllSteps: !0,
            enableFinishButton: !1,
            transitionEffect: "slideLeft",
            stepsOrientation: "vertical",
            onInit: function(i, e) {
                altair_wizard.content_height(t, e)
            },
            onStepChanged: function(i, e) {
                altair_wizard.content_height(t, e)
            }
        })
    },
    checkuser_company: function(){
      jQuery("#wizard_username").blur(function(e){
        var $this = jQuery(this);
        var data = {};
        data['value'] = jQuery(this).val();
        data['key'] = 1;
        var postdata = { data: JSON.stringify(data) };
        ErmisTemplateAjaxPost0($(this) ,postdata,'check-register',
        function(){
          z = true;
          let specificField = $this.parsley();
          window.ParsleyUI.removeError(specificField, "myCustomError");
        },
        function(result){
          z = false;
          let specificField = $this.parsley();
          window.ParsleyUI.addError(specificField, "myCustomError", result.message);
        })
      });
      jQuery("#wizard_company_taxcode").blur(function(e){
        var $this = jQuery(this);
        var data = {};
        data['value'] = jQuery(this).val();
        data['key'] = 2;
        var postdata = { data: JSON.stringify(data) };
        ErmisTemplateAjaxPost0($(this) ,postdata,'check-register',
        function(){
          z = true;
          let specificField =  $this.parsley();
          window.ParsleyUI.removeError(specificField, "myCustomError");
        },
        function(result){
          z = false;
          let specificField = $this.parsley();
          window.ParsleyUI.addError(specificField, "myCustomError", result.message);
        })
      });
      jQuery("#wizard_email").blur(function(e){
        var $this = jQuery(this);
        var data = {};
        data['value'] = jQuery(this).val();
        data['key'] = 3;
        var postdata = { data: JSON.stringify(data) };
        ErmisTemplateAjaxPost0($(this) ,postdata,'check-register',
        function(){
          z = true;
          let specificField =  $this.parsley();
          window.ParsleyUI.removeError(specificField, "myCustomError");
        },
        function(result){
          z = false;
          let specificField = $this.parsley();
          window.ParsleyUI.addError(specificField, "myCustomError", result.message);
        })
      });
    }

};
