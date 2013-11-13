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
        <?php echo form_open(null, array('id' => 'register-form')) ?>
            <fieldset id="" class="">
                <legend>Full Name</legend>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <label class="sr-only" for="first_name">First Name</label>
                            <input class="form-control" type="text" name="first_name" value="<?php echo set_value('first_name'); ?>" id="first_name" required placeholder="First Name" required />
                        </div>
                        <div class="col-md-6">
                            <label class="sr-only" for="last_name">First Name</label>
                            <input class="form-control" type="text" name="last_name" value="<?php echo set_value('last_name'); ?>" id="last_name" title="Last Name" placeholder="Last Name" required />
                        </div>
                    </div>
                </div>
            </fieldset>
            
            <fieldset id="" class="">
                <legend>Shipping Address</legend>
                
                <div class="form-group">
                    <label class="sr-only" for="address_line1">Address</label>
                    <input class="form-control" type="text" id="address_line1" name="address_line1" value="<?php echo set_value('address_line1'); ?>" placeholder="Address" required />
                </div>
                <div class="form-group">
                    <label class="sr-only" for="address_line2">Apartment or Suite Number</label>
                    <input class="form-control" type="text" id="address_line2" name="address_line2" value="<?php echo set_value('address_line2'); ?>" placeholder="Apt/Suite" />
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-8">
                            <label class="sr-only" for="city">City</label>
                            <input class="form-control" type="text" name="city" value="<?php echo set_value('city'); ?>" id="city" placeholder="City" required />
                        </div>
                        <div class="col-md-2">
                            <label class="sr-only" for="state">State</label>
                            <input class="form-control" type="text" name="state" value="<?php echo set_value('state'); ?>" id="state" maxlength="2" pattern="[A-Za-z]{2}" title="The 2 letter abriviation for your state." placeholder="State" required />
                        </div>
                        <div class="col-md-2">
                            <label class="sr-only" for="postcode">Zip</label>
                            <input class="form-control" type="text" name="postcode" value="<?php echo set_value('postcode'); ?>" maxlength="5" pattern="[0-9]{5}" title="We need to know those 5 little digits at the end of your address" id="postcode" placeholder="Zip" required />
                        </div>
                    </div>
                </div>
            </fieldset>
                        
            <fieldset id="" class="">
                <legend>Your Account</legend>
              
                <div class="form-group">
                    <label class="sr-only" for="email">Email Address</label>
                    <input class="form-control" type="email" name="email" maxlength="100" value="<?php echo set_value('email'); ?>" placeholder="Email Address" required />
                    <?php echo form_input('d0ntf1llth1s1n', ' ', 'class="default-form" style="display:none"') ?>
                </div>
                <div class="form-group">
                    <label class="sr-only" for="password">Password</label>
                    <input class="form-control" type="password" name="password" maxlength="20" placeholder="Password" required pattern="(?=^.{6,20}$)(?=.*\d)(?=.*[A-Z])(?=.*[a-z]).*$" title="6 characters or more and must contain one of each - uppercase, lowercase, number " />
                </div>   
                <div class="form-group">
                    <label class="sr-only" for="password_confirm">Confirm Password</label>
                    <input class="form-control" type="password" name="password_confirm" maxlength="20" placeholder="Confirm Password" required />
                </div>        
            </fieldset>
            
            <input type="hidden" name="product_id" value="<?php echo set_value('product_id', @$productID); ?>" id="product_id"/>
            <input type="hidden" name="referral_id" value="Testing" id="referral_id"/>
        
            <button type="submit" class="btn btn-primary btn-large">Continue</button>

        <?php echo form_close() ?>
    </div>
</section>