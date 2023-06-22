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

    <title>@lang('welcome.title_page', ['name' => $name ])</title>

    <link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500' rel='stylesheet' type='text/css'>

    <!-- uikit -->
    <link rel="stylesheet" href="{{asset('library/uikit/css/uikit.almost-flat.min.css')}}"/>

    <!-- altair admin error page -->
    <link rel="stylesheet" href="{{asset('assets/css/error_page.min.css')}}" />

</head>
<body class="error_page">

    <div class="error_page_header">
        <div class="uk-width-8-10 uk-container-center">
            @lang('welcome.name' , ['name' => $name ])
        </div>
    </div>
    <div class="error_page_content">
        <div class="uk-width-8-10 uk-container-center">
            <p class="heading_b">@lang('welcome.note1')</p>
            <p class="uk-text-large">
                @lang('welcome.note2')
            </p>
            <a href="{{url('/')}}" >@lang('welcome.back') <span id="container"></span> second(s)!</a>
        </div>
    </div>
    <script type="text/javascript">
        var time = 15; //How long (in seconds) to countdown
        function countDown(){
            time--;
            gett("container").innerHTML = time;
            if(time == -1){
            window.location = "{{url('/')}}";
            }
        }
        function gett(id){
            if(document.getElementById) return document.getElementById(id);
            if(document.all) return document.all.id;
            if(document.layers) return document.layers.id;
            if(window.opera) return window.opera.id;
        }
        function init(){
            if(gett('container')){
            setInterval(countDown, 1000);
            gett("container").innerHTML = time;
            }
            else{
            setTimeout(init, 50);
            }
        }
    document.onload = init();
    </script>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-65191727-1', 'auto');
        ga('send', 'pageview');
    </script>
</body>
</html>
