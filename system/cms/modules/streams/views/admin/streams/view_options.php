<section class="title">
	<h4><span><a href="<?php echo site_url('admin/streams/manage/'.$stream->id); ?>"><?php echo $stream->stream_name;?></a></span> &rarr; <?php echo lang('streams.view_options');?></h4>
</section>

<section class="item">

<?php echo form_open(uri_string(), 'class="crud"'); ?>

	<table cellpadding="0" cellspacing="0" class="form_table">

		<tr>
			<td width="20%" class="label_col"><label for="stream_name"><?php echo lang('streams.id');?></label></td>
			<td><input type="checkbox" name="view_options[]" id="stream_name" value="id"<?php if(in_array('id', $stream->view_options)): echo ' checked '; endif; ?>/></td>
		</tr>

		<tr>
			<td class="label_col"><label for="created"><?php echo lang('streams.created_date');?></label></td>
			<td><input type="checkbox" name="view_options[]" id="created" value="created"<?php if(in_array('created', $stream->view_options)): echo ' checked '; endif; ?>/></td>
		</tr>

		<tr>
			<td class="label_col"><label for="updated"><?php echo lang('streams.updated_date');?></label></td>
			<td><input type="checkbox" name="view_options[]" id="updated" value="updated"<?php if(in_array('updated', $stream->view_options)): echo ' checked '; endif; ?>/></td>
		</tr>

		<tr>
			<td class="label_col"><label for="created_by"><?php echo lang('streams.created_by');?></label></td>
			<td><input type="checkbox" name="view_options[]" id="created_by" value="created_by"<?php if(in_array('created_by', $stream->view_options)): echo ' checked '; endif; ?>/></td>
		</tr>
		
	<?php if( $stream_fields ): ?>
	
	<?php foreach( $stream_fields as $stream_field ): ?>

		<tr>
			<td class="label_col"><label for="<?php echo $stream_field->field_slug;?>"><?php echo $stream_field->field_name;?></label></td>
			<td><input type="checkbox" name="view_options[]" id="<?php echo $stream_field->field_slug;?>" value="<?php echo $stream_field->field_slug;?>"<?php if(in_array($stream_field->field_slug, $stream->view_options)): echo ' checked '; endif; ?>/>
		</tr></td>
		
	<?php endforeach; ?>
	
	<?php endif; ?>
					
	</table>

		<button type="submit" name="btnAction" value="save" class="btn blue"><span><?php echo lang('buttons.save'); ?></span></button>	
		<a href="<?php echo site_url('admin/streams/manage/'.$stream->id); ?>" class="btn gray"><?php echo lang('buttons.cancel'); ?></a>
	
<?php echo form_close();?>

</section>
