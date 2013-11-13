{{ asset:js file="module::payment.js" }}
{{ asset:css file="module::style.css" }}
<?php if(validation_errors()): ?>
    <div class="alert alert-warning alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong>Bummer!</strong>
        <?php echo validation_errors('<span>','</span>'); ?>
    </div>
<?php endif; ?>
<section class="row">
    <div class="col-md-5">
        {{ widgets:area slug="new-user-checkout" }}
    </div>
    <div class="col-md-7">
        <h1></h1>
        <?php echo form_open(null, array('id' => 'payment-form')) ?>
            
            <div class="form-group">
            	<!-- Credit Card Number -->
            	<label class="sr-only" for="cc_name">Card Holder Name</label>
            	<input type="text" id="cc_name" class="form-control" autocomplete="off" placeholder="Card Holder Name" data-encrypted-name="cc_name" required />
            	<p class="help-block">Enter name as it appears on card</p>
            </div>
            <div class="form-group">
                <div class="row"> 
                    <div class="col-md-8">
                        <!-- Credit Card Number -->
                    	<label class="sr-only" for="cc_num">Credit Card Number</label>
                    	<input type="text" id="cc_num" class="form-control" autocomplete="off" placeholder="Credit Card Number" data-encrypted-name="cc_num" required />
                	</div>
                    <div class="col-md-4">
                        <!-- Credit Card CVV Number -->
                    	<label class="sr-only" for="cc_cvv">Card Verification Value</label>
                    	<input type="text" class="form-control" autocomplete="off" placeholder="CVV" data-encrypted-name="cc_cvv" required />
                	</div>
            	</div>
        	</div>
            <div class="form-group">
            	<!-- Credit Card Exp Date -->
            	<label>Expiration Date</label>
        	    <div class="row">
        	    	<div class="col-md-2">
        	    		<label class="sr-only" for="cc_month">Expiration Month</label>
        	    		<input type="text" class="form-control" data-encrypted-name="cc_month" maxlength="2" pattern="[0-9]" title="Numbers only, example: 07 (July)" placeholder="MM" required />
        	    	</div>
        	    	<div class="col-md-3">
        	    		<label class="sr-only" for="cc_year">Expiration Year</label>
        	    		<input type="text" class="form-control" data-encrypted-name="cc_year" maxlength="4"  pattern="[0-9]" title="Numbers only, example: 2013" placeholder="YYYY" required />
        	    	</div>
        	    	<div class="col-md-3 col-md-offset-1">
        	    	    <!-- Credit Card Zip Code -->
                        <label class="sr-only" for="zip">Billing Zip Code</label>
                        <input type="text" class="form-control" maxlength="5"  pattern="[0-9]" title="We need the zip code" placeholder="Billing Zip Code" required />
        	    	</div>
        	    </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-large" >Continue</button>

        <?php echo form_close() ?>
    </div>
</section>