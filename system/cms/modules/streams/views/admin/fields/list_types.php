<section class="title">
	<h4><?php echo lang('streams.field_types');?></h4>
</section>

<section class="item">

    <table class="table-list">
		<thead>
			<tr>
			    <th><?php echo lang('streams.field_type');?></th>
			    <th><?php echo lang('version_label');?></th>
			    <th><?php echo lang('global:author'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($types as $type):?>
			<tr>
				<td><?php echo $type->field_type_name; ?></td>
				<td><?php if(isset($type->version)): echo $type->version; endif; ?></td>
				<td><?php if(isset($type->author) and is_array($type->author)):?><a href="<?php echo $type->author['url']; ?>"><?php echo $type->author['name']; ?></a><?php endif; ?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>

</section>