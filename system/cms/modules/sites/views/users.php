
<?php if (!empty($users)): ?>
	<h3><?php echo lang('site:user_manager'); ?></h3>

	<table border="0" class="table-list">
		<thead>
			<tr>
				<th><?php echo lang('site:username'); ?></th>
				<th><?php echo lang('site:email'); ?></th>
				<th><?php echo lang('site:active'); ?></th>
				<th><?php echo lang('site:created_on'); ?></th>
				<th><?php echo lang('site:last_login'); ?></th>
				<th><?php echo lang('site:manage'); ?></th>
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
				<td><?php echo $user->active == 1 ? lang('global:yes') : lang('global:no'); ?></td>
				<td><?php echo format_date($user->created_on); ?></td>
				<td><?php echo format_date($user->last_login); ?></td>
				<td class="buttons">
					<?php echo  anchor('sites/users/edit/'.$user->id, 		lang('global:edit'), 'class="btn"'); ?>
					<?php echo $user->active != 1 ?
								anchor('sites/users/enable/'.$user->id, 	lang('global:enable'), 'class="btn orange"') :
								anchor('sites/users/disable/'.$user->id, 	lang('global:disable'), 'class="btn orange"'); ?>
		
					<?php if (count($users) > 1): ?>
						<?php echo  anchor('sites/users/delete/'.$user->id, 	lang('global:delete'), 'class="btn red confirm"'); ?>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

<?php endif;?>