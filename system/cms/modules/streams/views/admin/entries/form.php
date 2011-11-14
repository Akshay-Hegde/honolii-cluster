<section class="title">
<?php if($method == 'new'): ?>
	<h4><span><a href="<?php echo site_url('admin/streams/entries/index/'.$stream->id); ?>"><?php echo $stream->stream_name;?></a></span> &rarr; <?php echo lang('streams.add_entry');?></h4>
<?php else: ?>
	<h4><span><a href="<?php echo site_url('admin/streams/entries/index/'.$stream->id); ?>"><?php echo $stream->stream_name;?></a></span> &rarr; <?php echo lang('streams.edit_entry').' '.$row_edit_id;?></h4>
<?php endif; ?>
</section>

<section class="item">

<?php echo form_open_multipart(uri_string(), 'class="streams_form"'); ?>

<table cellpadding="0" cellspacing="0" class="form_table">

	<?php $count = 1; foreach( $stream_fields as $field ): ?>

		<tr>
			<td width="25%" class="label_col"><label for="<?php echo $field->field_slug;?>"><?php echo $field->field_name;?></label> <?php if( $field->is_required == 'yes' ): ?>
				<span class="required"> *<?php echo lang('streams.required');?></span>
			<?php endif; ?>
			<?php if( $field->instructions != '' ): ?>
				<small><?php echo $field->instructions;?></small>
			<?php endif; ?>
			</td>
			<td><?php echo $this->fields->build_form_input( $field, $values[$field->field_slug], $row ); ?>
			</td>
		</tr>

	<?php $count++; endforeach; ?>		

</table>

	<?php if( $method == 'edit' ): ?>
	<input type="hidden" value="<?php echo $row_edit_id;?>" name="row_edit_id" />
	<?php endif; ?>

	<div class="float-right buttons">
		<button type="submit" name="btnAction" value="save" class="btn blue"><span><?php echo lang('buttons.save'); ?></span></button>	
		<a href="<?php echo site_url('admin/streams/entries/index/'.$stream->id); ?>" class="btn gray"><?php echo lang('buttons.cancel'); ?></a>
	</div>

<?php echo form_close();?>	

</section>