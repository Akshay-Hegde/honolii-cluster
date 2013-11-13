<?php // if(!@$_GET["water-temp"] || !@$_GET["product"] || !@$_GET["frequency"] ) : // check for all param ?>
    {{ # url:redirect to="membership" # }}
<?php // endif; ?>

<?php if ( ! empty($error_string)):?>
<!-- Woops... -->
<div class="error-box">
    <?php echo $error_string;?>
</div>
<?php endif;?>
<section class="row">
    <div class="col-md-5">
        {{ widgets:area slug="new-user-checkout" }}
    </div>
	<div class="col-md-7">
        <h1></h1>
        <?php echo form_open('register', array('id' => 'register')) ?>
            <fieldset id="" class="">
                <legend>Full Name</legend>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                        	<label class="sr-only" for="first_name">First Name</label>
                        	<input class="form-control" type="text" name="first_name" id="first_name" placeholder="First Name" required />
                        </div>
                        <div class="col-md-6">
                        	<label class="sr-only" for="last_name">First Name</label>
                        	<input class="form-control" type="text" name="last_name" id="last_name" placeholder="Last Name" required />
                        </div>
                    </div>
                </div>
            </fieldset>
            
        	<fieldset id="" class="">
                <legend>Shipping Address</legend>
                
                <div class="form-group">
                    <label class="sr-only" for="address_line1">Address</label>
                    <input class="form-control" type="text" id="address_line1" name="address_line1" placeholder="Address" required />
                </div>
                <div class="form-group">
                    <label class="sr-only" for="address_line2">Apartment or Suite Number</label>
                    <input class="form-control" type="text" id="address_line2" name="address_line2" placeholder="Apt/Suite" />
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-8">
                            <label class="sr-only" for="city">City</label>
                            <input class="form-control" type="text" name="city" id="city" placeholder="City" required />
                        </div>
                        <div class="col-md-2">
                            <label class="sr-only" for="state">State</label>
                            <input class="form-control" type="text" name="state" id="state" placeholder="State" required />
                        </div>
                        <div class="col-md-2">
                            <label class="sr-only" for="postcode">Zip</label>
                            <input class="form-control" type="text" name="postcode" id="postcode" placeholder="Zip" required />
                        </div>
                    </div>
                </div>
            </fieldset>
                    	
            <fieldset id="" class="">
                <legend>Your Account</legend>
			  
                <div class="form-group">
                    <label class="sr-only" for="email">Email Address</label>
                	<input class="form-control" type="email" name="email" maxlength="100" value="<?php echo $_user->email ?>" placeholder="Email Address" required />
                	<?php echo form_input('d0ntf1llth1s1n', ' ', 'class="default-form" style="display:none"') ?>
            	</div>
                <div class="form-group">
                    <label class="sr-only" for="password">Password</label>
                    <input class="form-control" type="password" name="password" maxlength="38" placeholder="Password" required />
                </div>       	 
			</fieldset>
			
			<input type="hidden" name="product_id" value="1001" id="product_id"/>
			<input type="hidden" name="referral_id" value="Testing" id="referral_id"/>
        
            <button type="submit" class="btn btn-primary btn-large">Continue</button>

        <?php echo form_close() ?>
    </div>
</section>