<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>WeLearn - It's not just about you anymore {$template.title}</title>
	<meta name="description" content="WeLearn - A community teaching and learning platform">
	<meta name="author" content="WeLearn Dev Team">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="{$base_url}css/style.css">
    <link rel="stylesheet" href="{$base_url}css/jquery-ui.css">
    <link rel="stylesheet" href="{$base_url}css/jquery.noty.css">
    <link rel="stylesheet" href="{$base_url}css/noty_theme_default.css">
    {$template.cssLinks}

	<script src="{$base_url}js/libs/modernizr-2.0.6.min.js"></script>
</head>
<body>
<div id="container">
    <div id="main" role="main" class="center-container clearfix">
        {$partial:perfil/barra_lateral_esquerda}
        <section id="inner-content-container">
            {$content}
        </section>
        {$partial:perfil/barra_lateral_direita}
    </div>
    <div id="push-footer"></div>
</div> <!--! end of #container -->
<footer>
    <section class="center-container">
        <p><span>WeLearn &copy;</span> - Copyright 2011. All rights reserved.</p>
    </section>
</footer>
{$partial:perfil/barra_usuario}

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="{$base_url}js/libs/jquery-1.7.2.min.js"><\/script>')</script>

<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.15/jquery-ui.min.js"></script>
<script>window.jQuery.ui || document.write('<script src="{$base_url}js/libs/jquery-ui-1.8.16.min.js"><\/script>')</script>

<script src="{$base_url}js/libs/jquery.noty.js"></script>

<script>
    var flashData = {$template.jsNotificacoes};
</script>

<!-- scripts concatenated and minified via ant build script-->
<script src="{$base_url}js/plugins.js"></script>
<script src="{$base_url}js/script.js"></script>
<script src="{$base_url}js/logout_usuario.js"></script>
<!-- end scripts-->

{$template.jsImports}
{$template.jsScripts}

<!--[if lt IE 7 ]>
	<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js"></script>
	<script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
<![endif]-->

</body>
</html>