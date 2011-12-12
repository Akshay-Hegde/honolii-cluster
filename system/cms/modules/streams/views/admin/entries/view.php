<section class="title">
	<h4><span><?php echo $stream->stream_name;?></span> &rarr; <?php echo lang('streams.entry');?> <?php echo $row->id;?></h4>
</section>

<section class="item">

    <table class="table-list">
		<thead>
			<tr>
				<th><?php echo lang('streams.label.field');?></th>
			    <th><?php echo lang('streams.value');?></th>
			</tr>
		</thead>
		<tbody>
		<tr>
			<td width="25%"><strong><?php echo lang('streams.id');?></strong></td>
			<td><?php echo $row->id;?></td>
		</tr>
		<tr>
			<td><strong><?php echo lang('streams.created_date');?></strong></td>
			<td><?php echo date('M j Y g:i a', $row->created);?></td>
		</tr>
		<tr>
			<td><strong><?php echo lang('streams.updated_date');?></strong></td>
			<td><?php if( $row->updated ): echo date('M j Y g:i a', $row->updated); endif; ?></td>
		</tr>
		<tr>
			<td><strong><?php echo lang('streams.created_by');?></strong></td>
			<td><?php echo username_from_id($row->created_by); ?></td>
		</tr>
		
		<?php foreach ($stream_fields as $stream_field):?>
			<tr>
				<td><strong><?php echo $stream_field->field_name;?></strong></td>
				<td><?php $node = $stream_field->field_slug; echo $row->$node; ?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
    </table>
  
 	<div class="float-right buttons">
    	<?php echo anchor('admin/streams/entries/edit/'.$this->uri->segment(5).'/'.$row->id, lang('global:edit'), 'class="btn orange"')?>
	    <?php echo anchor('admin/streams/entries/delete/'.$this->uri->segment(5).'/'.$row->id, lang('global:delete'), 'class="btn red confirm"')?>
	</div>
   
</section>