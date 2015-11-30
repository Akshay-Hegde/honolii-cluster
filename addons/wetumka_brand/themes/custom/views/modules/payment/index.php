{{ asset:js_inline }}
	requirejs(['default']);
{{ /asset:js_inline }}
{{ asset:css_inline }}
	.form-actions {margin-top:38px;}
{{ /asset:css_inline }}
{{ asset:js file="module::payment.js" }}
{{ asset:css file="module::style.css" }}

<section id="payment-module" class="block-wrapper intro-section">
	<div class="container">
		<?php echo form_open(null,array('id'=>'form_value_method')); ?>
		<fieldset>
			<h2>Pay Invoice</h2>
			<p>All fields are required. <?php if(@$data['braintree']): ?> We also accept payments with <a href="//www.dwolla.com/" target="_blank">Dwolla</a>. <?php endif; ?></p>
			
			<?php if(validation_errors()): ?>
			<div class="alert alert-warning alert-dismissable">
				<strong>Oops!</strong>
				<?php echo validation_errors('<span>','</span>'); ?>
			</div>
			<?php endif; ?>
			
			<div class="row">
				<div class="col-md-6">
					<!-- Payment Value -->
					<div class="form-group">
						<?php echo form_label('Payment Amount', 'payment'); ?>
						<?php echo form_input('payment', set_value('payment',@$payment), 'placeholder="$0.00" class="form-control"'); ?>
					</div>
				</div>
				<div class="col-md-6">
						<!-- Invoice Number -->
					<div class="form-group">
						<?php echo form_label('Invoice Number', 'invoice'); ?>
						<?php echo form_input('invoice', set_value('invoice',@$invoice), 'class="form-control"'); ?>
					</div>
				</div>
				<!-- Payment Choice -->
				<?php echo form_hidden('method', ''); ?>
			</div>

			<div id="payment_choice" class="form-actions">
				<div class="row">
				<?php if(@$data['dwolla']): ?>
					<div class="col-sm-5">
						<?php echo form_button('dwollapayment','<i class="icon dwolla"></i>Pay with Dwolla','class="btn btn-large btn-primary btn-block"'); ?>
					</div>
				<?php endif; if(@$data['dwolla'] && @$data['braintree']): ?>
					<div class="col-sm-1 text-center">or</div>
				<?php endif; if(@$data['braintree']): ?>
					<div class="col-sm-6">
						<?php echo form_button('cardpayment','<i class="icon creditcard"></i>Pay with Credit Card','class="btn btn-large btn-primary btn-block"'); ?>
					</div>
				<?php endif; ?>
				</div>
			</div>
		</fieldset>
		<?php echo form_close(); ?>
	</div>
</section>