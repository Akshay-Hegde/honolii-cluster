<section class="title">
    <h4><?php echo lang('faq_category_edit_title'); ?></h4>
</section>
<section class="item">
<?php echo form_open('admin/faq/categories/edit/'.$category->id, 'id="categories" class="crud"'); ?>
<div class="form_inputs">
    <fieldset>
        <ul>
            <li>
                <label for="title"><?php echo lang('faq_category_label'); ?><span class="required-icon tooltip">*</span></label>
                <div class="input">
                    <input name="title" type="text" value="<?php echo $category->title; ?>" />
                </div>
            </li>
            <li class="even">
                <label for="published"><?php echo lang('faq_published_label'); ?></label>
                <div class="input">
                    <?php echo form_dropdown('published', $publish_options, $category->published); ?>
                <div class="input">
            </li>
            <li>
                <label for="description"><?php echo lang('faq_category_description_label'); ?></label>
                <textarea name="description" rows="5" cols="80"><?php echo $category->description; ?></textarea>
            </li>
        </ul>
    </fieldset>
</div>
<input type="hidden" name="category_id" value="<?php echo $category->id; ?>" />
<div class="buttons">
<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
</div>
<?php echo form_close(); ?>
</section>