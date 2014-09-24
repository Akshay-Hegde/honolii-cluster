<ol>
	<li class="odd">
        <label>Video Hosting</label>
        <?php echo form_dropdown('hosting', array( 'vimeo' => 'Vimeo', 'vimeo_channel' => 'Vimeo Channel', 'youtube' => 'YouTube'),'vimeo'); ?>
    </li>
	<li class="even">
		<label>User Name/ID</label>
		<?php echo form_input('username', $options['username']); ?>
	</li>
	<li class="odd">
		<label>Number of items (max:25)</label>
		<?php echo form_input('number', $options['number']); ?>
	</li>
	<li class="even">
		<label>Cache time - minutes</label>
		<?php echo form_input('cache', $options['cache']); ?>
	</li>
	<li class="odd">
        <label>Width</label>
        <?php echo form_input('width', $options['width']); ?>
    </li>
    <li class="even">
        <label>Height</label>
        <?php echo form_input('height', $options['height']); ?>
    </li>
</ol>
<h5>You Tube</h5>
<ol>
    <li class="odd">
        <label>Show suggested videos at ending:</label>
        <?php echo form_checkbox('utsuggested',TRUE, $options['utsuggested']); ?>
    </li>
    <li class="even">
        <label>Force HTML5 video player:</label>
        <?php echo form_checkbox('uthtml5',TRUE, $options['uthtml5']); ?>
    </li>
</ol>
<h5>Vimeo</h5>
<ol>
    <li class="odd">
        <label>Portrait:</label>
        <?php echo form_checkbox('vportrait',TRUE, $options['vportrait']); ?>
    </li>
    <li class="even">
        <label>Title:</label>
        <?php echo form_checkbox('vtitle',TRUE, $options['vtitle']); ?>
    </li>
    <li class="odd">
        <label>Byline:</label>
        <?php echo form_checkbox('vbyline',TRUE, $options['vbyline']); ?>
    </li>
</ol>