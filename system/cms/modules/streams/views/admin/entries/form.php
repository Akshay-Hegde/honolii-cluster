<section class="title">
<?php if($method == 'new'): ?>
	<h4><span><a href="<?php echo site_url('admin/streams/entries/index/'.$stream->id); ?>"><?php echo $stream->stream_name;?></a></span> &rarr; <?php echo lang('streams.add_entry');?></h4>
<?php else: ?>
	<h4><span><a href="<?php echo site_url('admin/streams/entries/index/'.$stream->id); ?>"><?php echo $stream->stream_name;?></a></span> &rarr; <?php echo lang('streams.edit_entry').' '.$row_edit_id;?></h4>
<?php endif; ?>
</section>

<section class="item">

<?php echo form_open_multipart(uri_string(), 'class="streams_form"'); ?>

<div class="form_inputs">

	<ul>

	<?php $count = 1; foreach( $stream_fields as $field ): ?>

		<li>
			<label for="<?php echo $field->field_slug;?>"><?php echo $field->field_name;?> <?php if( $field->is_required == 'yes' ): ?><span>*</span><?php endif; ?>
			
			<?php if( $field->instructions != '' ): ?>
				<br /><small><?php echo $field->instructions;?></small>
			<?php endif; ?>
			</label>
			
			<div class="input"><?php echo $this->fields->build_form_input( $field, $values[$field->field_slug], $row ); ?></div>
		</li>

	<?php $count++; endforeach; ?>
	
	</ul>	

</duv>

	<?php if( $method == 'edit' ): ?>
	<input type="hidden" value="<?php echo $row_edit_id;?>" name="row_edit_id" />
	<?php endif; ?>

	<div class="float-right buttons">
		<button type="submit" name="btnAction" value="save" class="btn blue"><span><?php echo lang('buttons.save'); ?></span></button>	
		<a href="<?php echo site_url('admin/streams/entries/index/'.$stream->id); ?>" class="btn gray"><?php echo lang('buttons.cancel'); ?></a>
	</div>

<?php echo form_close();?>	

</section>