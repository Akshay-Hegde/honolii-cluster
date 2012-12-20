
<?php if (!empty($stats)): ?>
	<h3><?php echo lang('site:stats'); ?></h3>

	<table border="0" class="table-list">
		<thead>
			<tr>
				<th><?php echo lang('site:resource'); ?></th>
				<th><?php echo lang('site:usage'); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo lang('site:users'); ?></td>
				<td><?php echo $stats->users; ?></td>
			</tr>
			<tr>
				<td><?php echo lang('site:last_admin_login'); ?></td>
				<td><?php echo format_date($stats->admin_login); ?></td>
			</tr>
			<tr>
				<td><?php echo lang('site:tables'); ?></td>
				<td><?php echo $stats->tables; ?></td>
			</tr>
			<tr>
				<td><?php echo lang('site:schema_version'); ?></td>
				<td><?php echo $stats->schema_version; ?></td>
			</tr>
			<?php foreach ($stats->disk_usage AS $folder => $usage): ?>
			<tr>
				<td><?php echo $folder; ?></td>
				<td><?php echo byte_format($usage); ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

<?php endif;?>