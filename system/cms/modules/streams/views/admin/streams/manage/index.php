<div class="one_half">

<section class="title">
	<h4><?php echo lang('streams.stream_admin');?></h4>
</section>

<section class="item">

	<table class="table-list">
		<tr>
			<td><?php echo anchor('admin/streams/entries/add/'.$this->uri->segment(4), lang('streams.new_entry'), 'class="add"');?></td>
		</tr>
		<tr>
			<td><?php echo anchor('admin/streams/entries/index/'.$this->uri->segment(4), lang('streams.stream_entries'), 'class="data"');?></td>
		</tr>
		<tr>
			<td><?php echo anchor('admin/streams/assignments/'.$this->uri->segment(4), lang('streams.field_assignments'), 'class="list"');?></td>
		</tr>
		<tr>
			<td><?php echo anchor('admin/streams/edit/'.$this->uri->segment(4), lang('streams.edit_stream')); ?></td>
		</tr>
		<tr>
			<td><?php echo anchor('admin/streams/view_options/'.$this->uri->segment(4), lang('streams.stream_view_options')); ?></td>
		</tr>
		<tr>
			<td><?php echo anchor('admin/streams/backup/'.$this->uri->segment(4), lang('streams.backup_table')); ?></td>
		</tr>
		<tr>
			<td><?php echo anchor('admin/streams/delete/'.$this->uri->segment(4), lang('streams.delete_stream')); ?></td>
		</tr>
	</table>

</section>

</div>

<div class="one_half last">

<section class="title">
	<h4><?php echo lang('streams.about_stream'); ?></h4>
</section>

<section class="item">
    <table class="table-list">
		<tbody>
			<tr>
				<td><strong>Stream Name</strong></td>
				<td><?php echo $stream->stream_name; ?></td>
			</tr>
			<tr>
				<td><strong>About</strong></td>
				<td><?php echo $stream->about; ?></td>
			</tr>
			<tr>
				<td><strong>Database Table</strong></td>
				<td><?php echo $table_name; ?></td>
			</tr>
			<tr>
				<td><strong>Size</strong></td>
				<td><?php echo $total_size; ?></td>
			</tr>
			<tr>
				<td><strong>Number of entries</strong></td>
				<td><?php echo $total_rows; ?></td>
			</tr>
			<tr>
				<td><strong>Number of fields</strong></td>
				<td><?php echo $num_of_fields; ?></td>
			</tr>
			<tr>
				<td><strong>Last Updated</strong></td>
				<td><?php echo $last_updated; ?></td>
			</tr>
		</tbody>
    </table>
</section>