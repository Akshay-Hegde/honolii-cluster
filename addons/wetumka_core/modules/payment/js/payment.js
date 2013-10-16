var paymentObj, braintree;

// Payment JS - require jquery
paymentObj = {
	$paymentBTN : $('button','#payment_choice'),
	$paymentForm : $('#form_value_method'),
	
	methodClick : function(event){
		// set value of hidden field
		event.data.$paymentForm.find('input[name="method"]').val(event.currentTarget.name);
		// submit the form
		event.data.$paymentForm.submit();
	}
};
// click payment option buttons
paymentObj.$paymentBTN.click(paymentObj,paymentObj.methodClick);

if(typeof braintreeClientKey != 'undefined' && braintreeClientKey){
	braintree = Braintree.create(braintreeClientKey);
	braintree.onSubmitEncryptForm("form_cc");
}