<section class="title">
<?php if($method == 'new'): ?>
	<h4><?php echo lang('streams.add_stream');?></h4>
<?php else: ?>
	<h4><?php echo lang('streams.edit_stream');?></h4>
<?php endif; ?>
</section>

<?php echo form_open(uri_string(), 'class="crud"'); ?>

<section class="item">

	<table cellpadding="0" cellspacing="0" class="form_table">

		<tr>
			<td class="label_col"><label for="stream_name"><?php echo lang('streams.stream_name'); ?></label></td>
			<td><?php echo form_input('stream_name', $stream->stream_name, 'maxlength="60" autocomplete="off" id="stream_name"'); ?>
			<span class="required-icon tooltip"><?php echo lang('required_label');?></span></td>
		</tr>

		<tr>
			<td class="label_col"><label for="stream_slug"><?php echo lang('streams.stream_slug'); ?></label></td>
			<td><?php echo form_input('stream_slug', $stream->stream_slug, 'maxlength="60" id="stream_slug"'); ?>
			<span class="required-icon tooltip"><?php echo lang('required_label');?></span></td>
		</tr>

		<tr>
			<td class="label_col"><label for="about"><?php echo lang('streams.about_stream'); ?></label></td>
			<td><?php echo form_input('about', $stream->about, 'maxlength="255"'); ?></td>
		</tr>
		
		<?php if( $method == 'edit' ): ?>

		<tr>
			<td class="label_col"><label for="title_column"><?php echo lang('streams.title_column');?></label></td>
			<td><?php echo form_dropdown('title_column', $fields, $stream->title_column); ?></td>
		</tr>

		<tr>
			<td class="label_col"><label for="sorting"><?php echo lang('streams.sort_method');?></label></td>
			<td><?php echo form_dropdown('sorting', array('title'=>lang('streams.by_title_column'), 'custom'=>lang('streams.manual_order')), $stream->sorting); ?></td>
		</tr>
		
		<?php endif; ?>
			
	</table>

	<div class="float-right buttons">
		<button type="submit" name="btnAction" value="save" class="btn blue"><span><?php echo lang('buttons.save'); ?></span></button>	
		
		<?php if($this->uri->segment(3)=='add'): ?>

			<a href="<?php echo site_url('admin/streams'); ?>" class="btn gray"><?php echo lang('buttons.cancel'); ?></a>
		
		<?php else: ?>
		
			<a href="<?php echo site_url('admin/streams/manage/'.$stream->id); ?>" class="btn gray"><?php echo lang('buttons.cancel'); ?></a>
		
		<?php endif; ?>
	</div>
		
</section>		
		
<?php echo form_close();?>	