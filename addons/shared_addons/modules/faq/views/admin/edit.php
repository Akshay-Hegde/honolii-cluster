<section class="title">
    <h4><?php echo lang('faq_edit_title'); ?></h4>
</section>
<section class="item">
<?php echo form_open('admin/faq/edit/'.$faq->id, 'id="faq" class="crud"'); ?>
<div class="form_inputs">
    <fieldset>
        <ul>
            <li>
                <label for="question"><?php echo lang('faq_question_label'); ?><span class="required-icon tooltip">*</span></label>
                <div class="input">
                    <input name="question" type="text" value="<?php echo $faq->question; ?>" />
                </div>
            </li>
                <li class="even">
                <label for="published"><?php echo lang('faq_published_label'); ?></label>
                <div class="input">
                    <?php echo form_dropdown('published', $publish_options, $faq->published); ?>
                </div>
            </li>
            <li>
                <label for="category"><?php echo lang('faq_category_label'); ?></label>
                <div class="input">
                    <?php echo form_dropdown('category', $category_options, $faq->category_id); ?>
                </div>
            </li>
            <li>
                <label for="answer"><?php echo lang('faq_answer_label'); ?></label><br style="clear:both;">
                <textarea class="wysiwyg-simple" name="answer" rows="10" cols="40"><?php echo $faq->answer; ?></textarea>
            </li>
        </ul>
        <input type="hidden" name="faq_id" value="<?php echo $faq->id; ?>" />
    </fieldset>
</div>
<div class="buttons">
<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
</div>
<?php echo form_close(); ?>
</section>