{{ asset:js file="module::payment.js" }}
{{ asset:css file="module::style.css" }}

<?php echo form_open(null,array('id'=>'form_value_method')); ?>
<fieldset>
    <legend>Pay Invoice</legend>
    <?php if(validation_errors()): ?>

    <div class="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Oops!</strong>
        <?php echo validation_errors('<span>','</span>'); ?>
    </div>

    <?php endif; ?>
    
    <div class="row">
        <div class="col-sm-4">
            <!-- Payment Value -->
            <div class="form-group">
                <?php echo form_label('Payment Amount', 'payment'); ?>
            	<?php echo form_input('payment', set_value('payment',@$payment), 'placeholder="$0.00" class="form-control"'); ?>
        	</div>
            <!-- Invoice Number -->
            <div class="form-group">
                <?php echo form_label('Invoice Number', 'invoice'); ?>
            	<?php echo form_input('invoice', set_value('invoice',@$invoice), 'class="form-control"'); ?>
        	</div>
            <!-- Payment Choice -->
            <?php echo form_hidden('method', ''); ?>
        </div>
        <div class="col-sm-6">
            <p>All fields are required. We accept payments from Dwolla, to learn more about this great service <a href="http://refer.dwolla.com/a/clk/1SVw3m">check out their site and start an account</a>.</p>
        </div>
    </div>
    <div id="payment_choice" class="form-actions">
        <div class="row">
        <?php if(@$data['dwolla']): ?>
            <div class="col-sm-5">
                <?php echo form_button('dwollapayment','<i class="icon dwolla"></i>Pay with Dwolla','class="btn btn-large btn-primary btn-block"'); ?>
            </div>
        <?php endif; if(@$data['dwolla'] && @$data['braintree']): ?>
            <div class="col-sm-1 text-center">
                or
            </div>
        <?php endif; if(@$data['braintree']): ?>
            <div class="col-sm-6">
                <?php echo form_button('cardpayment','<i class="icon creditcard"></i>Pay with Credit Card','class="btn btn-large btn-primary btn-block"'); ?>
            </div>
        <?php endif; ?>
        </div>
    </div>
</fieldset>
<?php echo form_close(); ?>