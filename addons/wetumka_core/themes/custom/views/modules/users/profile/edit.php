<h2><?= lang('profile_edit') ?></h2>
<div id="user_edit_profile">
	<?php if (validation_errors()): ?>
	<div class="notice">
		<?= validation_errors();?>
	</div>
	<?php endif; ?>
   
	<?= form_open('edit-profile', array('id'=>'user_edit_profile'));?>
	<?= form_fieldset(lang('profile_personal_section')) ?>
		<div class="clearfix append-bottom">
			<div class="span-4"><?= form_label(lang('profile_display_name').':', 'display_name'); ?></div>         
			<div class="span-10 last"><?= form_input('display_name', set_value('display_name', $profile->display_name), 'class="span-9 text" maxlength="80"'); ?></div>
		</div>
		<div class="clearfix">
			<div class="span-4"><?= form_label(lang('profile_dob').':'); ?></div>
			<div class="span-2"><?= form_dropdown('dob_month', $months, $profile->dob_month, 'class="span-2"') ?></div> 
			<div class="span-2"><?= form_dropdown('dob_day', $days, $profile->dob_day, 'class="span-2"') ?></div>
			<div class="span-3 last"><?= form_dropdown('dob_year', $years, $profile->dob_year, 'class="span-3"') ?></div>
			<div class="prepend-4 span-2 field-help"><?= lang('profile_dob_month') ?></div>
			<div class="span-2 field-help"><?= lang('profile_dob_day') ?></div> 
			<div class="span-3 field-help"><?= lang('profile_dob_year') ?></div>   
		</div>
		<div class="clearfix append-bottom">
			<div class="span-4"><?= form_label(lang('profile_gender').':', 'gender'); ?></div>         
			<div class="span-10 last"><?= form_dropdown('gender', array(''=> 'Not telling', 'm'=>'Male', 'f'=>'Female'), $profile->gender, 'class="span-4"'); ?></div>
		</div>
		<div class="clearfix append-bottom">
			<div class="span-4"><?= form_label(lang('profile_bio').':', 'bio'); ?></div>         
			<div class="span-10 last"><?= form_textarea('bio', $profile->bio, 'class="span-9"'); ?></div>
		</div>
	<?= form_fieldset_close() ?>
	<?= form_fieldset(lang('profile_contact_section')) ?>
		<div class="clearfix append-bottom">
			<div class="span-4"><?= form_label(lang('profile_phone').':', 'phone'); ?></div>
			<div class="span-10 last"><?= form_input('phone', $profile->phone, 'class="span-9 text" maxlength="80"'); ?></div>
		</div>
		<div class="clearfix append-bottom">
			<div class="span-4"><?= form_label(lang('profile_mobile').':', 'mobile'); ?></div>
			<div class="span-10 last"><?= form_input('mobile', $profile->mobile, 'class="span-9 text" maxlength="80"'); ?></div>
		</div>
		<div class="clearfix append-bottom">
			<div class="span-4"><?= form_label(lang('profile_address_line1').':', 'address_line1'); ?></div>
			<div class="span-10 last"><?= form_input('address_line1', $profile->address_line1, 'class="span-9 text" maxlength="80"'); ?></div>
		</div>
		<div class="clearfix append-bottom">
			<div class="span-4"><?= form_label(lang('profile_address_line2').':', 'address_line2'); ?></div>
			<div class="span-10 last"><?= form_input('address_line2', $profile->address_line2, 'class="span-9 text" maxlength="80"'); ?></div>
		</div>
		<div class="clearfix append-bottom">
			<div class="span-4"><?= form_label(lang('profile_address_line3').':', 'address_line3'); ?></div>
			<div class="span-10 last"><?= form_input('address_line3', $profile->address_line3, 'class="span-9 text" maxlength="80"'); ?></div>
		</div>
		<div class="clearfix append-bottom">
			<div class="span-4"><?= form_label(lang('profile_address_postcode').':', 'postcode'); ?></div>
			<div class="span-10 last"><?= form_input('postcode', $profile->postcode, 'class="span-9 text" maxlength="80"'); ?></div>
		</div>
		<div class="clearfix append-bottom">
			<div class="span-4"><?= form_label(lang('profile_website').':', 'website'); ?></div>
			<div class="span-10 last"><?= form_input('website', $profile->website, 'class="span-9 text" maxlength="80"'); ?></div>
		</div>
	<?= form_fieldset_close() ?>

	<?= form_fieldset(lang('profile_messenger_section')) ?>
		<div class="clearfix append-bottom">
			<div class="span-4"><?= form_label(lang('profile_msn_handle').':', 'msn_handle'); ?></div>
			<div class="span-10 last"><?= form_input('msn_handle', $profile->msn_handle, 'class="span-9 text" maxlength="80"'); ?></div>
		</div>
		<div class="clearfix append-bottom">
			<div class="span-4"><?= form_label(lang('profile_aim_handle').':', 'aim_handle'); ?></div>
			<div class="span-10 last"><?= form_input('aim_handle', $profile->aim_handle, 'class="span-9 text" maxlength="80"'); ?></div>
		</div>
		<div class="clearfix append-bottom">
			<div class="span-4"><?= form_label(lang('profile_yim_handle').':', 'yim_handle'); ?></div>
			<div class="span-10 last"><?= form_input('yim_handle', $profile->yim_handle, 'class="span-9 text" maxlength="80"'); ?></div>
		</div>
		<div class="clearfix append-bottom">
			<div class="span-4"><?= form_label(lang('profile_gtalk_handle').':', 'gtalk_handle'); ?></div>
			<div class="span-10 last"><?= form_input('gtalk_handle', $profile->gtalk_handle, 'class="span-9 text" maxlength="80"'); ?></div>
		</div>
	<?= form_fieldset_close() ?>
	<?= form_fieldset(lang('profile_social_section')) ?>
		<div class="clearfix append-bottom">
			<div class="span-4"><?= form_label(lang('profile_gravatar').':', 'gravatar'); ?></div>
			<div class="span-10 last"><?= form_input('gravatar', $profile->gravatar, 'class="span-9 text" maxlength="80"'); ?></div>
		</div>

		<!--
		<dl>
			<dt><label for="twitter"><?php echo lang('profile_twitter') ?></label></dt>
			<dd>
				<?php if (!$this->user->twitter_access_token)
						echo anchor('users/profile/twitter', 'Connect with Twitter');
					  else
						echo 'Twitter Connected';
				?>
			</dd>
		</dl>
		-->

	<?= form_fieldset_close() ?>

	<?= form_submit('', lang('profile_save_btn'), 'class="cta"'); ?>

	<?= form_close(); ?>
</div>