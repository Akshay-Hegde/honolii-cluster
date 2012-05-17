<!doctype html>

<!--[if lt IE 7]> 
	<html class="nojs ms lt_ie7" lang="en"> 
<![endif]-->

<!--[if IE 7]>    
	<html class="nojs ms ie7" lang="en"> 
<![endif]-->

<!--[if IE 8]>    
	<html class="nojs ms ie8" lang="en"> 
<![endif]-->

<!--[if gt IE 8]> 
	<html class="nojs ms" lang="en"> 
<![endif]-->

<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="author" content="iKreativ">
	<meta name="description" content="PyroCMS Multi-Site manager">
	<meta name="keywords" content="pyrocms, multi, site, manager, login">
	
	<!-- Mobile Viewport -->
    <meta name="viewport" content="width=device-width">

	<title>Multi-Site Manager - Login</title>
	
	<!-- Googlelicious -->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,400italic,600,700,600italic,700italic,300italic' rel='stylesheet' type='text/css'>
	
	<base href="<?php echo base_url(); ?>" />
	
	<?php 
		Asset::css('workless/minified.css.php');
		Asset::js('workless/modernizr.js');
		Asset::js('jquery/jquery.js');
		Asset::js('admin/login.js');
		echo Asset::render(); 
	?>
</head>

<body class="login noise" id="top">

	<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you support IE 6 -->
	<!--[if lt IE 7]>
		<p>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p>
	<![endif]-->
	
	<div id="login" <?php if (validation_errors()): ?>style="padding-bottom:140px;"<?php endif; ?>>
		<?php echo Asset::img('workless/key.png', 'Login', array('class' => 'login_icon')); ?>

		<h1><?php echo lang('site.sites');?></h1>
		
		<?php $this->load->view('admin/partials/notices') ?>
		
		<?php echo form_open('sites/login'); ?>
			<ul>
				<li>
					<input type="text" name="email" placeholder="<?php echo lang('email_label'); ?>" />
				</li>
				
				<li>
					<input type="password" name="password" placeholder="<?php echo lang('password_label'); ?>"  />
				</li>
				
				<li id="remember_me">
					<input id="remember" type="checkbox" name="remember" value="1" />
					<label for="remember"><?php echo lang('user_remember'); ?></label>
				</li>
				
				<li id="login_button">
					<input type="submit" name="submit" value="<?php echo lang('login_label'); ?>" />
				</li>
			</ul>
		<?php echo form_close(); ?>
	</div>
</body>
</html>