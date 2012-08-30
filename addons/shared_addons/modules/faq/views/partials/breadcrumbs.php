<div id="breadcrumbs">
<?php
foreach ($template['breadcrumbs'] as $crumb) {
	if(!empty($crumb->uri))
	{
		echo anchor($crumb['uri'], $crumb['name']);
		echo " > ";
	}
	else
	{
		echo '<span class="current">' . $crumb['name'] . '</span>';
	}
}
?>
</div>