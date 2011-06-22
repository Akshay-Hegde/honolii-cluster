
<?php if (!empty($sites)): ?>
	<h3><?php echo lang('site.sites'); ?></h3>

	<table border="0" class="table-list">
		<thead>
			<tr>
				<th><?php echo lang('site.name'); ?></th>
				<th><?php echo lang('site.ref'); ?></th>
				<th><?php echo lang('site.domain'); ?></th>
				<th><?php echo lang('site.created_on'); ?></th>
				<th><?php echo lang('site.manage'); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="5">
					<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($sites as $site): ?>
			<tr>
				<td><?php echo $site->id == 1 ? lang('site.main_site') : $site->name; ?></td>
				<td><?php echo $site->ref; ?></td>
				<td><?php echo $site->domain; ?></td>
				<td><?php echo date($this->config->item('date_format'), $site->created_on); ?></td>
				<td class="buttons">
					<?php echo anchor('admin/sites/edit/'.$site->id, 	lang('buttons.edit'), 'class="button"'); ?>
					<?php echo anchor('admin/sites/delete/'.$site->id, 	lang('buttons.delete'), 'class="button confirm"
									  title="'.lang('site.delete_site').'"'); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

<?php endif;?>