{{ asset:js file="module::payment.js" }}
{{ asset:css file="module::style.css" }}

<script src="https://js.braintreegateway.com/v1/braintree.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
 var braintreeClientKey = '<?php echo $data['clientKey']; ?>';
</script>

<?php echo form_open(null,array('id'=>'form_cc')); ?>
<fieldset>
    <legend>Credit Card Information</legend>
    
    <?php if(@$data['error']): ?>

            <div class="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Oops!</strong>
                <?php echo $data['error']; ?>
            </div>

    <?php endif; ?>
    
    <div class="row">
        <div class="col-sm-6">
            
            <!-- Credit Card Number -->
            <div class="form-group">
            	<label>Card Holder Name</label>
            	<input type="text" class="form-control" autocomplete="off" data-encrypted-name="cc_name" />
            	<span class="help-block">Enter name as it appears on card</span>
            </div>
            
            <!-- Credit Card Number -->
            <div class="form-group">
            	<label>Credit Card Number</label>
            	<input type="text" class="form-control" autocomplete="off" data-encrypted-name="cc_num" />
            </div>
            
            <!-- Credit Card CVV Number -->
            <div class="form-group">
            	<label>CVV</label>
            	<div class="row">
            		<div class="col-xs-6">
            			<input type="text" class="form-control" autocomplete="off" data-encrypted-name="cc_cvv" />
            		</div>
            		<div class="col-xs-2">
            		    <i class="cvv"></i>
            		</div>
            	</div>
            	
            </div>
            <!-- Credit Card Exp Date -->
            <div class="form-group">
            	<label>Expiration Date</label>
        	    <div class="row">
        	    	<div class="col-sm-6">
        	    		<input type="text" class="form-control" data-encrypted-name="cc_month" placeholder="MM" />
        	    	</div>
        	    	<div class="col-sm-6">
        	    		<input type="text" class="form-control" data-encrypted-name="cc_year" placeholder="YYYY" />
        	    	</div>
        	    </div>
            </div>
            
            <!-- Credit Card Zip Code -->
            <div class="form-group">
                <?php echo form_label('Billing Zip Code', 'zip'); ?>
            	<?php echo form_input('zip', NULL, 'class="form-control"'); ?>
        	</div>
            
        </div>
        <div class="col-sm-5 col-sm-offset-1">
            <table class="table">
                <tr>
                    <td>Payment:</td>
                    <td>${{ session:data name="payment" }}</td>
                </tr>
                <tr>
                    <td>Invoice:</td>
                    <td>{{ session:data name="invoice" }}</td>
                </tr>
            </table>
        </div>
    </div>
    <div id="payment_credit" class="form-actions">
        <?php echo form_submit('submit','Submit Payment','class="btn btn-large btn-primary"'); ?>
        <a href="/payment/cancel" class="btn btn-default btn-large">Cancel</a>
    </div>
</fieldset>
<?php echo form_close(); ?>    