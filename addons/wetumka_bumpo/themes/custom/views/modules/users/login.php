<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <h2 class="page-title" id="page_title">Please sign in</h2>
        
        <?php if (validation_errors()): ?>
        <div class="error-box">
        	<?php echo validation_errors();?>
        </div>
        <?php endif ?>
        
        <?php echo form_open('users/login', array('id'=>'login','class'=>'form-signin'), array('redirect_to' => '/my-profile')) ?>
        
        		<label class="sr-only" for="email"><?php echo lang('global:email') ?></label>
        		<?php echo form_input('email', $this->input->post('email') ? $this->input->post('email') : '', 'class="form-control" placeholder="Email address" required autofocus')?>
        
        		<label class="sr-only" for="password"><?php echo lang('global:password') ?></label>
        		<input class="form-control" type="password" id="password" name="password" maxlength="20" placeholder="Password" required />
        
        		<label>
        		  <?php echo form_checkbox('remember', '1', false) ?><?php echo lang('user:remember') ?>
        		</label>
        
        		<input type="submit" class="btn btn-lg btn-primary btn-block" value="<?php echo lang('user:login_btn') ?>" name="btnLogin" />
        
        		<?php echo anchor('users/reset_pass', lang('user:reset_password_link'));?>
        
        <?php echo form_close() ?>
    </div>
</div>