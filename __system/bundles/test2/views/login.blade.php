<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <link rel="icon" type="image/ico" href="{{asset('assets/admin/favicon.ico')}}">
    <title><?php echo Config::get('project.name'); ?> - {{ __('cardea::cardea.adminlogin')}} </title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300&subset=latin,latin-ext' rel='stylesheet'>
    <!-- Assets: Styles -->
    {{ Asset::container('header')->styles(); }}
    <!-- Assets: Scripts -->
    {{ Asset::container('header')->scripts(); }}

    <script type="text/javascript">
    (function(a){a.fn.vAlign=function(){return this.each(function(){var b=a(this).height(),c=a(this).outerHeight(),b=(b+(c-b))/2;a(this).css("margin-top","-"+b+"px");a(this).css("top","50%");a(this).css("position","absolute")})}})(jQuery);(function(a){a.fn.hAlign=function(){return this.each(function(){var b=a(this).width(),c=a(this).outerWidth(),b=(b+(c-b))/2;a(this).css("margin-left","-"+b+"px");a(this).css("left","50%");a(this).css("position","absolute")})}})(jQuery);
    $(document).ready(function() {
        if($('#login-wrapper').length) {
            $("#login-wrapper").vAlign().hAlign()
        };
        if($('#login-validate').length) {
            $('#login-validate').validate({
                onkeyup: false,
                errorClass: 'error',
                rules: {
                    username: { required: true },
                    password: { required: true }
                }
            })
        }
        if($('#forgot-validate').length) {
            $('#forgot-validate').validate({
                onkeyup: false,
                errorClass: 'error',
                rules: {
                    forgot_email: { required: true, email: true }
                }
            })
        }
        $('#pass_login').click(function() {
            $('.panel:visible').slideUp('200',function() {
                $('.panel').not($(this)).slideDown('200');
            });
            $(this).children('span').toggle();
        });
    });
    </script>
</head>
<body>
    <div id="login-wrapper" class="clearfix">
        <div class="main-col">
            <img src="{{ asset('assets/admin/img/epigra.png') }}" alt="" class="logo_img" />
            <div class="panel">
                <p class="heading_main">{{ __('cardea::cardea.accountlogin'); }}</p>
                {{ Form::open(action('auth::login@attempt'), 'POST', array('id' => 'login-validate')) }}
                    <label for="username">{{ __('cardea::cardea.username'); }}</label>
                    <input type="text" id="username" name="email" value="" />
                    <label for="password">{{ __('cardea::cardea.password'); }}</label>
                    <input type="password" id="password" name="password" value="" />
                    <label style="float:left; margin-top:10px" for="remember" class="checkbox"><input type="checkbox" id="remember" name="remember" /> {{ __('cardea::cardea.rememberme'); }}</label>
                    <button style="float:right " type="submit" class="btn btn-beoro-3">{{ __('cardea::cardea.login'); }}</button>
                    
                {{ Form::close() }}
            </div>
            <div class="panel" style="display:none">
                <p class="heading_main">{{ __('cardea::cardea.forgotpw'); }}</p>
                <form id="forgot-validate" method="post">
                    <label for="forgot_email">{{ __('cardea::cardea.email'); }}</label>
                    <input type="text" id="forgot_email" name="forgot_email" />
                    <button style="float:right " type="submit" class="btn btn-beoro-3">{{ __('cardea::cardea.resetpw'); }}</button>
                    
                </form>
            </div>
        </div>
        <div class="footer"><?php echo Config::get('epigra.fwname'). ' v.'. Config::get('epigra.version'); ?></div>

        <div class="login_links">
            <a href="javascript:void(0)" id="pass_login"><span>{{ __('cardea::cardea.forgotpw'); }}</span><span style="display:none">{{ __('cardea::cardea.accountlogin'); }}</span></a>
        </div>

    </div>

</body>
</html>