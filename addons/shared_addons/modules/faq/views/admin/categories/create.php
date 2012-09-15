<section class="title">
    <h4><?php echo lang('faq_category_create_title'); ?></h4>
</section>
<section class="item">
<?php echo form_open('admin/faq/categories/create', 'id="categories" class="crud"'); ?>
<div class="form_inputs">
    <fieldset>
        <ul>
            <li>
                <label for="title"><?php echo lang('faq_category_label'); ?><span class="required-icon tooltip">*</span></label>
                <div class="input">
                    <input name="title" type="text" value="<?php echo set_value('title'); ?>" />
                </div>
            </li>
            <li class="even">
                <label for="published"><?php echo lang('faq_published_label'); ?></label>
                <div class="input">
                    <?php echo form_dropdown('published', $publish_options, set_value('published')); ?>
                </div>
            </li>
            <li>
                <label for="description"><?php echo lang('faq_category_description_label'); ?></label>
                <textarea name="description" rows="5" cols="80"><?php echo set_value('description'); ?></textarea>
            </li>
        </ul>
    </fieldset>
</div>
<div class="buttons">
<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
</div>
<?php echo form_close(); ?>
</section>