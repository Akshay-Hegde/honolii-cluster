<section class="title">
	<h4><?php echo lang('galleries.galleries_label'); ?></h4>
	<div class="gallery-toolbar">
        <div class="gallery-buttons">

                <?php if(!$sort): ?>
                <a class="button" href="admin/galleries/index/0" >Sortable View</a>
                <?php else: ?>
                <a class="button" href="admin/galleries/index/" >Default View</a>
                <?php endif; ?>

        </div>
    </div>
</section>

<section class="item">
	<div class="content">
	<?php echo form_open('admin/galleries/delete');?>
	
	<?php if ( ! empty($galleries)): ?>
	
		<table border="0" class="table-list">
			<thead>
				<tr>
				    <?php if($sort): ?>
					<th width="30"></th>
					<?php endif; ?>
					<th width="30"><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
					<th><?php echo lang('galleries.gallery_label'); ?></th>
					<?php if($sort): ?>
					<th>Child Galleries</th>
					<?php endif; ?>
					<th>Published</th>
					<th width="140"><?php echo lang('galleries.num_photos_label'); ?></th>
					<th width="140"><?php echo lang('galleries.updated_label'); ?></th>
					<th width="200"></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
				    <?php if($sort): ?>
					<td colspan="8">
				    <?php else: ?>
			        <td colspan="6">
			        <?php endif; ?>
						<div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
					</td>
				</tr>
			</tfoot>
			<tbody class="sortable ui-sortable">
				<?php foreach( $galleries as $gallery ): ?>
				<tr>
				    <?php if($sort): ?>
					<td>
					    <?php echo Asset::img('icons/drag_handle.gif', 'Drag Handle'); ?>
					    <input type="hidden" name="action_to[]" value="<?php echo $gallery->id;?>" />
					</td>
					<?php endif; ?>
					<td><?php echo form_checkbox('action_to[]', $gallery->id); ?></td>
					<td><?php echo anchor('admin/galleries/preview/' . $gallery->id, $gallery->title, 'target="_blank" class="modal-large"'); ?></td>
					<?php if($sort): ?>
					<td><?php if(count($gallery->has_child) == 0){ echo '--';}else{ echo anchor('admin/galleries/index/' . $gallery->id, count($gallery->has_child), 'class="button button-block"');} ?></td>
					<?php endif; ?>
					<td><?php if($gallery->published == 0){ echo 'No';}else{ echo 'Yes';} ?></td>
					<td><?php echo $gallery->photo_count; ?></td>
					<td><?php echo format_date($gallery->updated_on); ?></td>
					<td class="align-center buttons buttons-small">
						<?php /* if ($gallery->folder_id && isset($folders[$gallery->folder_id]) && $path = $folders[$gallery->folder_id]->virtual_path): ?>
							<?php echo anchor('admin/files#!path='.$path, lang('galleries.upload_label'), 'class="button"'); ?>
						<?php endif;*/ ?>
						<?php echo anchor('admin/galleries/manage/'.$gallery->id, lang('galleries.manage_label'), 'class="button"'); ?>
						<?php echo anchor('admin/galleries/delete/'.$gallery->id, lang('global:delete'), array('class'=>'confirm button delete')); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	
		<div class="table_action_buttons">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete') )); ?>
		</div>
	
	<?php else: ?>
		<div class="blank-slate">
			<div class="no_data">
				<?php //echo image('album.png', 'galleries', array('alt' => 'No Galleries')); ?>
				<?php echo lang('galleries.no_galleries_error'); ?>
			</div>
		</div>
	<?php endif;?>
	
	<?php echo form_close(); ?>
	</div>
</section>