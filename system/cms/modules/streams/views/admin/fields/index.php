<section class="title">
	<h4><?php echo lang('streams.fields');?></h4>
</section>

<section class="item">

<?php if (!empty($fields)): ?>

    <table class="table-list">
		<thead>
			<tr>
			    <th><?php echo lang('streams.label.field_name');?></th>
			    <th><?php echo lang('streams.label.field_slug');?></th>
			    <th><?php echo lang('streams.label.field_type');?></th>
			    <th><?php echo lang('pyro.actions');?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($fields as $field):?>
			<tr>
				<td><?php echo $field->field_name; ?></td>
				<td><?php echo $field->field_slug; ?></td>
				<td><?php echo $this->type->types->{$field->field_type}->field_type_name; ?></td>
				<td class="actions">
					<?php echo anchor('admin/streams/fields/edit/' . $field->id, lang('streams.edit'), 'class="button"');?> 					
					<?php echo anchor('admin/streams/fields/delete/' . $field->id, lang('streams.delete'), 'class="button confirm"'); ?>
				</td>
			</tr>
		<?php endforeach;?>
		</tbody>
    </table>

<?php echo $pagination['links']; ?>

<?php else: ?>
	<p><?php echo lang('streams.start.no_fields');?> <?php echo anchor('admin/streams/fields/add', lang('streams.start.add_one'), 'class="add"'); ?>.</p>
<?php endif;?>

</section>