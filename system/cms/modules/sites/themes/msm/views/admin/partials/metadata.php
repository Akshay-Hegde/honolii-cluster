<script type="text/javascript">
	var APPPATH_URI			= "<?php echo APPPATH_URI;?>";
	var SITE_URL			= "<?php echo rtrim(site_url(), '/').'/';?>";
	var BASE_URL			= "<?php echo BASE_URL;?>";
	var BASE_URI			= "<?php echo BASE_URI;?>";
	var DEFAULT_TITLE		= "<?php echo lang('site:sites'); ?>";
	var DIALOG_MESSAGE		= "<?php echo lang('global:dialog:delete_message'); ?>";
	var pyro 				= {};
	pyro.apppath_uri		= "<?php echo APPPATH_URI; ?>";
	pyro.base_uri			= "<?php echo BASE_URI; ?>";
</script>

<?php

	Asset::css(array(
		'workless/plugins.css',
		'workless/workless.css',
		'workless/typography.css',
		'workless/forms.css',
		'workless/tables.css',
		'workless/buttons.css',
		'workless/alerts.css',
		'workless/pagination.css',
		'workless/breadcrumbs.css',
		'workless/icons.css',
		'workless/helpers.css',
		'workless/print.css',
		'workless/scaffolding.css',
		'workless/application.css',
		'jquery/colorbox.css',
		'jquery/jquery-ui.css',
	));

	Asset::js('workless/modernizr.js');
	Asset::js('jquery/jquery.js');
	Asset::js('admin/login.js');
	Asset::js('jquery/jquery.colorbox.min.js');
	Asset::js('jquery/jquery-ui.min.js');
	Asset::js('workless/application.js');

	echo Asset::render();

?>

<?php echo $template['metadata']; ?>
