<section class="title">
    <h4><?php echo lang('faq_category_index_title'); ?></h4>
</section>
<section class="item">
<?php if(!empty($categories)): ?>
    <?php echo form_open('admin/faq/categories/action', 'class="crud"') ?>
    <table border="0" class="table-list">
        <thead>
            <tr>
                <th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>
                <th><?php echo lang('faq_category_label') ?></th>
                <th class="width-5"><?php echo lang('faq_published_label') ?></th>
                <th class="width-10"><span><?php echo lang('faq_actions_label');?></span></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="4">
                    <div class="inner"><?php $this->load->view('admin/partials/pagination'); ?></div>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach($categories as $c): ?>
            <tr>
                <td><?php echo form_checkbox('action_to[]', $c->id) ?></td>
                <td><?php echo $c->title; ?></td>
                <td><?php echo $c->published; ?></td>
                <td class="buttons buttons-small">
                    <?php echo anchor('admin/faq/categories/edit/'.$c->id, lang('faq_edit_link'), 'rel="ajax" class="btn orange edit button"'); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="table_action_buttons">
    <?php $this->load->view('admin/partials/buttons', array('buttons' => array('delete'))); ?>
    </div>
    <?php echo form_close(); ?>
<?php else: ?>
    <div class="no_data">
        <?php echo lang('faq_no_categories');?>
    </div>
<?php endif; ?>
</section>