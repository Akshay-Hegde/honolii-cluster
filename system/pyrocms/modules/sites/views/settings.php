<?php echo form_open('sites/settings/index', 'class="crud"');?>

	<h3><?php echo lang('site.settings');?></h3>
	
	<div class="box-container">	
	
		<?php foreach ($settings AS $setting): ?>
			<li class="<?php echo alternator('', 'even'); ?>" >
				<label for="<?php echo $setting->slug; ?>"><?php echo lang('site.'.$setting->slug); ?></label>
				<?php echo form_input($setting->slug, set_value($setting->slug, $setting->value)); ?>
			</li>
		<?php endforeach; ?>
		
		<div class="buttons float-right padding-top">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save') )); ?>
		</div>
		
	</div>

<?php echo form_close(); ?>