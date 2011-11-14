<section class="title">
<?php if($method == 'new'): ?>
	<h4><?php echo lang('streams.add_field'); ?></h4>
<?php else: ?>
	<h4><?php echo lang('streams.edit_field'); ?></h4>
<?php endif; ?>
</section>

<section class="item">

<?php echo form_open(uri_string()); ?>

	<table cellpadding="0" cellspacing="0" class="form_table" id="field_table">

		<tr>
			<td width="25%" class="label_col"><label for="field_name"><?php echo lang('streams.label.field_name');?></label></td>
			<td><?php echo form_input('field_name', $field->field_name, 'maxlength="60" id="field_name" autocomplete="off"'); ?>
			<span class="required-icon tooltip"><?php echo lang('required_label');?></span></td>
		</tr>
		<tr>
			<td class="label_col"><label for="field_slug"><?php echo lang('streams.label.field_slug');?></label></td>
			<td><?php echo form_input('field_slug', $field->field_slug, 'maxlength="60" id="field_slug"'); ?>
			<span class="required-icon tooltip"><?php echo lang('required_label');?></span></td>
		</tr>

		<?php
		
			// We send some special params in an edit situation
			$ajax_url = 'streams/ajax/build_parameters';	
		
			if($this->uri->segment(4) == 'edit'):
			
				$ajax_url .= '/edit/'.$current_field->id;
			
			endif;
		
		?>
		
		<tr>
			<td class="label_col"><label for="field_type"><?php echo lang('streams.label.field_type'); ?></label></td>
			<td><?php echo form_dropdown('field_type', $field_types, $field->field_type, 'id="field_type" onchange="add_field_parameters(\''.site_url($ajax_url).'\');"'); ?>
			<span class="required-icon tooltip"><?php echo lang('required_label');?></span></td>
		</tr>
	
		<div id="parameters">
		
		<?php if( $method == "edit" or isset($current_type->custom_parameters) ): ?>
		
		<?php
		
		$data = array();
		
		$data['count'] = 0;
		
		if( isset($current_type->custom_parameters) ):
		
			foreach( $current_type->custom_parameters as $param ):
			
				if( method_exists($current_type, 'param_'.$param) ):
				
					$call = 'param_'.$param;
					
					$data['input'] 			= $current_type->$call($current_field->field_data[$param]);
					
					if(!isset($current_type->lang[CURRENT_LANGUAGE][$param])):
	
						$data['input_name']		= $current_type->lang['en'][$param];
				
					else:

						$data['input_name']		= $current_type->lang[CURRENT_LANGUAGE][$param];
					
					endif;
					
				else:
		
					$data['input'] 			= $parameters->$param($current_field->field_data[$param]);
					$data['input_name']		= $this->lang->line('streams.'.$param);
				
				endif;
				
				$data['input_slug']		= $param;
					
				echo $this->load->view('admin/ajax/extra_field', $data, TRUE);
				
				$data['count']++;
			
			endforeach;
		
		endif;
	
		?>
		
		<?php endif; ?>
		
		</div>
	
	</table>

		
		<div class="float-right buttons">
		<button type="submit" name="btnAction" value="save" class="btn blue"><span><?php echo lang('buttons.save'); ?></span></button>	
		<a href="<?php echo site_url('admin/streams/fields'); ?>" class="btn gray cancel"><?php echo lang('buttons.cancel'); ?></a>
	</div>
	
<?php echo form_close();?>

</section>