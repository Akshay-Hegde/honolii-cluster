
<?php if (!empty($users)): ?>
	<h3><?php echo lang('site.user_manager'); ?></h3>

	<table border="0" class="table-list">
		<thead>
			<tr>
				<th><?php echo lang('site.username'); ?></th>
				<th><?php echo lang('site.email'); ?></th>
				<th><?php echo lang('site.active'); ?></th>
				<th><?php echo lang('site.last_login'); ?></th>
				<th><?php echo lang('site.created_on'); ?></th>
				<th><?php echo lang('site.manage'); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="6">
					<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($users as $user): ?>
			<tr>
				<td><?php echo $user->username; ?></td>
				<td><?php echo $user->email; ?></td>
				<td><?php echo $user->active == 1 ? lang('dialog.yes') : lang('dialog.no'); ?></td>
				<td><?php echo format_date($user->last_login); ?></td>
				<td><?php echo format_date($user->created_on); ?></td>
				<td class="buttons">
					<?php echo $user->active != 1 ?
								anchor('admin/site_manager/users/enable/'.$user->id, 	lang('buttons.enable'), 'class="button"') :
								anchor('admin/site_manager/users/disable/'.$user->id, 	lang('buttons.disable'), 'class="button"'); ?>

					<?php echo  anchor('admin/site_manager/users/delete/'.$user->id, 	lang('buttons.delete'), 'class="button confirm"'); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

<?php endif;?>