<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<!-- Always force latest IE rendering engine & Chrome Frame -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo lang('cp_admin_title').' - '.$template['title'];?></title>
	
	<base href="<?php echo base_url(); ?>" />
	
	<!-- Mobile Viewport Fix -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	
	<!-- Grab Google CDNs jQuery, fall back if necessary -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
	<script>!window.jQuery && document.write('<script src="<?php echo js_path('jquery/jquery.js'); ?>"><\/script>')</script>
	
	<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

	<?php file_partial('metadata'); ?>
</head>

<body>
<noscript>
	<span class="noscript">PyroCMS requires that JavaScript be turned on for many of the functions to work correctly. Please turn JavaScript on and reload the page.</span>
</noscript>
<div id="page-wrapper">
	<section id="topbar" dir="<?php echo $this->settings->lang_direction; ?>">
<?php file_partial('header'); ?>
<?php file_partial('navigation'); ?>
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

	</section>
	<section id="content-wrapper">
		<header id="page-header">
			<h1><?php echo isset($description) ? $description : ''; ?></h1>
			
			<section id="user-links">
				<span id="user-greeting"><?php echo sprintf(lang('cp_logged_in_welcome'), $super_username); ?></span>
				<?php echo anchor('sites/logout', lang('cp_logout_label')); ?>
				<span id="settings"><?php echo anchor('sites/settings', lang('site.settings')); ?></span>
			</section>

		</header>

			<?php template_partial('shortcuts'); ?>

			<?php template_partial('filters'); ?>

			<?php file_partial('notices'); ?>

		<div id="content">
			<?php echo $template['body']; ?>
		</div>
	</section>
	
	<footer>
		Copyright &copy; 2010 PyroCMS &nbsp; -- &nbsp;
		Version <?php echo CMS_VERSION; ?> &nbsp; -- &nbsp;
		Rendered in {elapsed_time} sec. using {memory_usage}.
	</footer>
</div>
</body>
</html>