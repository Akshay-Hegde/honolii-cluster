<?php echo form_open('sites/settings', 'class="crud"');?>

	<h3><?php echo lang('site:settings');?></h3>
	
	<div class="box-container settings">	
	
		<?php foreach ($settings AS $setting): ?>
			<li class="<?php echo alternator('', 'even'); ?>" >
				<label for="<?php echo $setting->slug; ?>"><?php echo lang('site:'.$setting->slug); ?></label>
				<?php echo form_input($setting->slug, set_value($setting->slug, $setting->value)); ?>
			</li>
		<?php endforeach; ?>
		
		<div class="float-right padding-top padding-right">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
		</div>
		
	</div>

<?php echo form_close(); ?>