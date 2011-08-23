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

	<link rel="stylesheet" href="{$base_url}css/style.css?v1">
    {$template.cssLinks}

	<script src="{$base_url}js/libs/modernizr-2.0.6.min.js"></script>
</head>
<body>

<div id="container">
	<header>
        <div class="center-container">
            <section id="logo">
                <h1>WeLearn</h1>
                <blockquote>It's not just about you anymore.</blockquote>
            </section>
            {$formLoginOpen}
                <fieldset>
                    <legend>Log in</legend>
                    <label for="login">Nome de usuário ou email</label>
                    <input type="text" name="login" id="login" placeholder="login" />
                    <label for="password">Senha</label>
                    <input type="password" name="password" id="password" placeholder="password" />
                    <button type="submit" name="submitLogin">Entrar</button>
                </fieldset>
            {$formLoginClose}
        </div>
	</header>
	<div id="main" role="main" class="center-container clearfix">
        {$content}
	</div>
	<footer>
        <section class="center-container">
            <p>
                <span>WeLearn &copy;</span> - Copyright 2011. All rights reserved.
            </p>
        </section>
	</footer>
</div> <!--! end of #container -->

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="{$base_url}js/libs/jquery-1.6.2.min.js"><\/script>')</script>

<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.15/jquery-ui.min.js"></script>
<script>window.jQuery.ui || document.write('<script src="{$base_url}js/libs/jquery-ui-1.8.16.min.js"><\/script>')</script>

<!-- scripts concatenated and minified via ant build script-->
<script src="{$base_url}js/plugins.js"></script>
<script src="{$base_url}js/script.js"></script>
<!-- end scripts-->
<script src="{$base_url}js/login_usuario.js"></script>

{$template.jsImports}
{$template.jsScripts}

<!--[if lt IE 7 ]>
	<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.2/CFInstall.min.js"></script>
	<script>window.attachEvent("onload",function(){CFInstall.check({mode:"overlay"})})</script>
<![endif]-->

</body>
</html>