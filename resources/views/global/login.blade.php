<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no"/>

    <link rel="icon" type="image/png" href="{{ asset('addon/img/icon.png') }}" sizes="16x16">
    <link rel="icon" type="image/png" href="{{ asset('addon/img/icon.png') }}" sizes="32x32">

    <title>@lang('login.title_page')</title>

    <link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500' rel='stylesheet' type='text/css'>

    <!-- uikit -->
    <link rel="stylesheet" href="{{ asset('library/uikit/css/uikit.almost-flat.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('library/kendoui/styles/kendo.common.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('library/kendoui/styles/kendo.material.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('library/kendoui/styles/kendo.default.mobile.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('addon/css/customize2.css') }}" />
    <!-- altair admin login page -->
    <link rel="stylesheet" href="{{ asset('assets/css/login_page.min.css') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script src="{{ asset('library/kendoui/js/jquery.min.js') }}"></script>
    <script src="{{ asset('library/kendoui/js/kendo.all.min.js') }}"></script>
</head>
<body class="login_page">

    <div class="login_page_wrapper">
        <div class="md-card" id="login_card">
            <div class="md-card-content large-padding" id="login_form">
              <div class="logo">
                   <a href="{{url('/')}}">
                       <img src="{{ url('addon/img/logo-big-blue.png') }}" alt="Ermis" />
                   </a>
               </div>
                <form class="login-form">
                  <div class="uk-form-row">
                      @if(session('status'))
                           <div class="alert alert-danger">{{session('status')}}</div>
                       @endif
                     <div id="notification"></div>
                  </div>
                    <div class="uk-form-row">
                        <label for="login_username">@lang('login.username')</label>
                        <input tabindex="1" class="md-input" type="text" id="login_username" name="username" />
                    </div>
                    <div class="uk-form-row">
                        <label for="login_password">@lang('login.password')</label>
                        <input tabindex="1" class="md-input" type="password" id="login_password" name="password" />
                    </div>
                    <div class="uk-form-row">
                        <div tabindex="3" class="g-recaptcha" data-sitekey="6LeBW6YUAAAAADn_VTsleqz8WsfvoTFhgVRn4fSk" data-callback="YourOnSubmitFn"></div>
                    </div>
                    <div class="uk-margin-medium-top">
                        <a href="javascript:;" id="button_login"  tabindex="4" class="md-btn md-btn-primary md-btn-block md-btn-large">@lang('login.sign_in')</a>
                    </div>
                    <div class="uk-margin-top">
                      <span class="icheck-inline">
                        <a href="javascript:;"  tabindex="5" id="login_help_show" class="uk-float-right">@lang('login.get_your_password')</a>
                      </span>
                        <!--<span class="icheck-inline">
                            <input type="checkbox" name="login_page_stay_signed" id="login_page_stay_signed" data-md-icheck />
                            <label for="login_page_stay_signed" class="inline-label">Stay signed in</label>
                        </span>-->
                    </div>
                </form>
            </div>
            <div class="md-card-content large-padding uk-position-relative" id="login_help" style="display: none">
                <button type="button" class="uk-position-top-right uk-close uk-margin-right uk-margin-top back_to_login"></button>
                <h2 class="heading_b uk-text-success">@lang('login.get_password_1')</h2>
                <p>@lang('login.get_password_2')</p>
                <p>@lang('login.get_password_3')</p>
                <p>@lang('login.get_password_4') <a href="javascript:;" id="password_reset_show">@lang('login.reset_your_password')</a>.</p>
            </div>
            <div class="md-card-content large-padding" id="login_password_reset" style="display: none">
                <button type="button" class="uk-position-top-right uk-close uk-margin-right uk-margin-top back_to_login"></button>
                <h2 class="heading_a uk-margin-large-bottom">@lang('login.reset_password')</h2>
                <form>
                    <div class="uk-form-row">
                        <label for="login_email_reset">@lang('login.your_email_address')</label>
                        <input class="md-input" type="text" id="login_email_reset" name="login_email_reset" />
                    </div>
                    <div class="uk-margin-medium-top">
                        <a href="javascript:;" class="md-btn md-btn-primary md-btn-block">@lang('login.reset')</a>
                    </div>
                </form>
            </div>
            <!--<div class="md-card-content large-padding" id="register_form" style="display: none">
                <button type="button" class="uk-position-top-right uk-close uk-margin-right uk-margin-top back_to_login"></button>
                <h2 class="heading_a uk-margin-medium-bottom">Create an account</h2>
                <form>
                    <div class="uk-form-row">
                        <label for="register_username">Username</label>
                        <input class="md-input" type="text" id="register_username" name="register_username" />
                    </div>
                    <div class="uk-form-row">
                        <label for="register_password">Password</label>
                        <input class="md-input" type="password" id="register_password" name="register_password" />
                    </div>
                    <div class="uk-form-row">
                        <label for="register_password_repeat">Repeat Password</label>
                        <input class="md-input" type="password" id="register_password_repeat" name="register_password_repeat" />
                    </div>
                    <div class="uk-form-row">
                        <label for="register_email">E-mail</label>
                        <input class="md-input" type="text" id="register_email" name="register_email" />
                    </div>
                    <div class="uk-margin-medium-top">
                        <a href="index.html" class="md-btn md-btn-primary md-btn-block md-btn-large">Sign Up</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="uk-margin-top uk-text-center">
            <a href="javascript:;" id="signup_form_show">Create an account</a>
        </div>
    </div>-->
    <script>
      jQuery(document).ready(function () {
        Login.url_back  = "{{ url()->previous() }}";
        Login.manage = "{{$manage}}"
      });
    </script>
    <!-- common functions -->
    <script src="{{ asset('assets/js/common.min.js') }}"></script>

    <script src="{{ asset('assets/js/uikit_custom.min.js') }}"></script>
    <!-- altair core functions -->
    <script src="{{ asset('assets/js/altair_admin_common.min.js') }}"></script>

    <script src="{{ asset('addon/scripts/global/framework.js') }}"></script>
    <script src="{{ asset('addon/scripts/global/ermis_template.js') }}"></script>
    <script src="{{ asset('addon/scripts/login.js') }}"></script>

    <!-- altair login page functions -->
    <script src="{{ asset('assets/js/pages/login.min.js') }}"></script>

    <script>
    $.ajaxSetup({

         headers: {

             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

         }

     });

        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-65191727-1', 'auto');
        ga('send', 'pageview');
    </script>
</body>
</html>
