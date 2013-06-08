<h3 class="col-headline"><?php echo sprintf(lang('profile_of_title'), $_user->display_name);?></h3>

<!-- Container for the user's profile -->
<div id="user_profile_container">
	<?php //echo gravatar($_user->email, 50);?>
	<!-- Details about the user, such as role and when the user was registered -->
	<div id="user_details">
		<h3><?php echo lang('profile_user_details_label');?></h3>
		<p><strong><?php echo lang('profile_role_label');?>:</strong> <?php echo $_user->group; ?></p>
		<p><strong><?php echo lang('profile_registred_on_label');?>:</strong> <?php echo format_date($_user->created_on); ?></p>
		<?php if($_user->last_login > 0): ?>
		<p><strong><?php echo lang('profile_last_login_label');?>:</strong> <?php echo format_date($_user->last_login); ?></p>
		<?php endif; ?>
	</div>
	
<?php if ($_user): ?>
	<p><strong><?php echo lang('user:first_name');?>:</strong> <?php echo $_user->first_name; ?></p>
	<p><strong><?php echo lang('user:last_name');?>:</strong> <?php echo $_user->last_name; ?></p>
    <p><strong>Phone:</strong> <?php echo $_user->phone; ?></p>
<?php else: ?>
	<!-- The user hasn't created a profile yet... -->
	<div id="user_no_profile">
		<p><?php echo lang('profile_not_set_up');?></p>
	</div>
<?php endif; ?>
</div>