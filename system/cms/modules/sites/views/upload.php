<h3><?php echo ($this->shared) ? sprintf(lang('site:shared_title'), $this->type) : sprintf(lang('site:site_upload_title'), $this->type);?></h3>

<?php echo form_open_multipart('sites/addons/do_upload/'.$this->ref.'/'.$this->type.'/'.$this->slug.'/'.(int) $this->shared, array('class' => 'crud'));?>

	<ol>
		<li>
			<label for="userfile"><?php echo lang('site:upload_desc');?></label><br/>
			<input type="file" name="userfile" class="input" />
		</li>
	</ol>
	
	<div class="buttons float-right padding-top">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('upload') )); ?>
	</div>
<?php echo form_close(); ?>
