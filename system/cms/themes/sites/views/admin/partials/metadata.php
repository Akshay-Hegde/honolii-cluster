<script type="text/javascript">
	var APPPATH_URI			= "<?php echo APPPATH_URI;?>";
	var SITE_URL			= "<?php echo rtrim(site_url(), '/').'/';?>";
	var BASE_URL			= "<?php echo BASE_URL;?>";
	var BASE_URI			= "<?php echo BASE_URI;?>";
	var DEFAULT_TITLE		= "<?php echo lang('site.sites'); ?>";
	var DIALOG_MESSAGE		= "<?php echo lang('global:dialog:delete_message'); ?>";
	pyro.apppath_uri		= "<?php echo APPPATH_URI; ?>";
	pyro.base_uri			= "<?php echo BASE_URI; ?>";
</script>

<?php

Asset::css(array(
	'admin/style.css',
	'jquery/jquery-ui.css',
	'jquery/colorbox.css',
));

Asset::js('jquery/jquery.js');
Asset::js_inline('jQuery.noConflict();');

Asset::js(array(
	'jquery/jquery-ui.min.js',
	'jquery/jquery.colorbox.min.js',
	'jquery/jquery.livequery.min.js',
	'jquery/jquery.uniform.min.js',
	'admin/functions.js',
));

echo Asset::render();

?>

<?php echo $template['metadata']; ?>
