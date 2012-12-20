<?php if ( ! empty($sites)): ?>

	<h3><?php echo lang('site:sites'); ?></h3>

	<table border="0" class="table-list">
		<thead>
			<tr>
				<th><?php echo lang('site:descriptive_name'); ?></th>
				<th><?php echo lang('site:ref'); ?></th>
				<th><?php echo lang('site:domain'); ?></th>
				<th><?php echo lang('global:control-panel'); ?></th>
				<th><?php echo lang('site:created_on'); ?></th>
				<th><?php echo lang('site:addons_upload'); ?></th>
				<th><?php echo lang('site:status'); ?></th>
				<th><?php echo lang('site:manage'); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="8">
					<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($sites as $site): ?>
			<tr>
				<td><?php echo $site->name; ?></td>
				<td><?php echo $site->ref; ?></td>
				<td><a target="_blank" href="http://<?php echo $site->domain; ?>"><?php echo $site->domain; ?></a></td>
				<td><a target="_blank" href="http://<?php echo $site->domain.'/admin'; ?>"><?php echo $site->domain.'/admin'; ?></a></td>
				<td><?php echo format_date($site->created_on); ?></td>
				<td class="addons-upload toggle-box">
					<?php echo form_checkbox('addons-upload', 1, $site->addons_upload, 'id="'.$site->ref.'"'); ?>
					<?php echo ($site->addons_upload) ?
								'<span class="red">'.lang('site:allowed').'</span>'.
								'<span class="green" style="display:none;">'.lang('site:disabled').'</span>' :
								'<span class="green">'.lang('site:disabled').'</span>'.
								'<span class="red" style="display:none;">'.lang('site:allowed').'</span>'; ?>
				</td>
				<td class="active toggle-box">
					<?php echo form_checkbox('active', 1, $site->active, 'id="'.$site->ref.'"'); ?>
					<?php echo ($site->active) ?
								'<span class="green">'.lang('site:active').'</span>'.
								'<span class="red" style="display:none;">'.lang('site:disabled').'</span>' :
								'<span class="red">'.lang('site:disabled').'</span>'.
								'<span class="green" style="display:none;">'.lang('site:active').'</span>'; ?>
				</td>
				<td class="buttons">
					<?php echo anchor('sites/stats/'.$site->id, lang('site:stats'), 'class="btn blue modal"'); ?>
					<?php echo anchor('sites/addons/index/'.$site->ref, lang('site:addons'), 'class="btn"'); ?>
					<?php echo anchor('sites/edit/'.$site->id, 	lang('buttons:edit'), 'class="btn"'); ?>
					<?php echo anchor('sites/delete/'.$site->id, 	lang('buttons:delete'), 'class="btn red modal"'); ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

<?php endif;?>