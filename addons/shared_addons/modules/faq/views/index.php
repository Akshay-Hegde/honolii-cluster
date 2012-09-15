<h3 class="faq-heading"><?php echo lang('faq_page_title'); ?></h3>
<?php if(!empty($questions)): ?>
    <ul id="faq">
        <?php foreach($questions as $faq): ?>
        <li class="question">
        <span><?php echo lang('faq_question_label'); ?>: </span>
        <?php echo $faq->question; ?>
        </li>
        <li class="answer"><?php echo $faq->answer; ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p class="no-questions"><?php echo lang('faq_no_questions'); ?></p>
<?php endif; ?>

<?php if(!empty($categories)): ?>
    <?php if(!empty($questions)): ?>
        <p class="more-categories"><?php echo lang('faq_more_categories'); ?></p>
    <?php else: ?>
        <p class="help-text"><?php echo lang('faq_categories_text'); ?></p>
    <?php endif; ?>
    <h3 class="faq-heading"><?php echo lang('faq_category_index_title'); ?></h3>
    <ul id="faq-categories">
        <?php foreach($categories as $category): ?>
        <li><?php echo anchor('faq/'.$category->slug, $category->title); ?></li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>

<?php endif; ?>