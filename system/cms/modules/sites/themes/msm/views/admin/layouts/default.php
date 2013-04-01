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

	<title><?php echo $template['title']; ?></title>
	
	<!-- Googlelicious -->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,400italic,600,700,600italic,700italic,300italic' rel='stylesheet' type='text/css'>
	
	<base href="<?php echo base_url(); ?>" />
	
	<?php file_partial('metadata'); ?>
</head>

<body class="pyro" id="top">
	<!-- Prompt IE 6 users to install Chrome Frame. Remove this if you support IE 6 -->
	<!--[if lt IE 7]>
		<p>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p>
	<![endif]-->
	
	<noscript>
		<span class="noscript">PyroCMS requires that JavaScript be turned on for many of the functions to work correctly. Please turn JavaScript on and reload the page.</span>
	</noscript>
	
	<section class="topbar" dir="<?php echo $this->settings->lang_direction; ?>">
		<div class="wrapper">
			<?php file_partial('header'); ?>
			<?php file_partial('navigation'); ?>
		</div>
	</section>
	
	<section class="subbar">
		<div class="wrapper">
			<h2><?php echo isset($description) ? $description : ''; ?></h2>
		</div>
	</section>
	
	<section id="content-body">
		<div class="wrapper">
			<?php 
				template_partial('shortcuts');
				template_partial('filters');
				file_partial('notices'); 
			?>

			<div id="content">
				<?php echo $template['body']; ?>
			</div>
		</div>
	</section>
	
	<footer>
		<div class="wrapper">
			Copyright &copy; 2009 - <?php echo date('Y') ?> PyroCMS LLC &nbsp; -- &nbsp;
			Version <?php echo CMS_VERSION; ?> &nbsp; -- &nbsp;
			Rendered in {elapsed_time} sec. using {memory_usage}.
			
			<div id="lang-select">
				<form action="<?php echo current_url(); ?>" id="change_language" method="get">
					<select name="lang" onchange="this.form.submit();">
						<option value="">-- Select Language --</option>
						<?php foreach($this->config->item('supported_languages') as $key => $lang): ?>
		   					<option value="<?php echo $key; ?>" <?php echo CURRENT_LANGUAGE == $key ? 'selected="selected"' : ''; ?>>
								<?php echo $lang['name']; ?>
							</option>
        				<?php endforeach; ?>
	       			</select>
				</form>
			</div>
		</div>
	</footer>
</body>
</html>