// contact.js
define (['default','lib/H5F','lib/pubsub'], function (def,H5F,PubSub) {
	"use strict";

	var _ = {};

	// init function
	_.init = function(){
		// validation steps
		this.activeFieldset = null;
		// buttons and form elements
		this.btnNext = document.getElementById('button_next');
		this.btnBack = document.getElementById('button_back');
		this.btnSubmit = document.getElementById('button_submit');
		this.contactForm = document.getElementById('contact_form');
		// form fieldsets
		this.setSubmit = document.getElementById('contact_submit');
		this.setStart = document.getElementById('contact_start');
		this.setGen = document.getElementById('contact_general');
		this.setJob = document.getElementById('contact_job');
		this.setRFP = document.getElementById('contact_rfp');

		// shim validation
		H5F.setup(this.contactForm);
		// events
		this.btnSubmit.addEventListener("click", function(e){
			PubSub.publish('event.click.btnSubmit',e);
		}, false);
		this.btnBack.addEventListener("click", function(e){
			PubSub.publish('event.click.btnBack',e);
			_.resetForm(e);
		}, false);
		this.btnNext.addEventListener("click", function(e){
			PubSub.publish('event.click.btnNext',e);
		}, false);
		this.contactForm.addEventListener("submit", function(e){
			PubSub.publish('event.submit.contactForm',e);
			_.submitForm(e);
		}, false);
		// diplay validation in fields
		this.displayValidate();
	};

	// validate fieldset
	_.validateFieldset = function(fieldset){
		var setValid, formFields = [];

		formFields = formFields.concat([].slice.call(fieldset.getElementsByTagName('input')));
		formFields = formFields.concat([].slice.call(fieldset.getElementsByTagName('textarea')));
		formFields = formFields.concat([].slice.call(fieldset.getElementsByTagName('select')));

		for (var i = formFields.length - 1; i >= 0; i--) {
			if(formFields[i].hasAttribute('required') && formFields[i].getAttribute('required') !== 'false'){
				if(formFields[i].validity.valid && setValid !== false){
					setValid = true;
				}else{
					setValid = false;
				}
			}
		}

		return setValid;
	};

	// reset form to step 1
	_.resetForm = function(event){
		
		//hide and disable the activeFieldset
		this.activeFieldset.classList.remove('event-show-fieldset-active');
		this.activeFieldset.disabled = true;

		//go back to step 1
		this.setSubmit.classList.remove('event-show-fieldset-active');
		this.setSubmit.disabled = true;

		this.setStart.classList.add('event-show-fieldset-active');
	};

	// submit form
	_.submitForm = function(event){
		var streamID;

		if(this.validateFieldset(this.setStart)){
			streamID = document.getElementById('input_subject').value;

			switch(streamID){
				case '12': //General Inquiry
					this.activeFieldset = this.setGen;
					break;
				case '13': //Job Inquiry
					this.activeFieldset = this.setJob;
					break;
				case '14': //RFP Inquiry
					this.activeFieldset = this.setRFP;
					break;
			}

			if(this.activeFieldset.disabled){

				event.preventDefault();

				this.setStart.classList.remove('event-show-fieldset-active');
				this.setSubmit.classList.add('event-show-fieldset-active');
				this.setSubmit.disabled = false;
				this.activeFieldset.classList.add('event-show-fieldset-active');
				this.activeFieldset.disabled = false;
			}else if(this.validateFieldset(this.activeFieldset)){
				PubSub.publish('form.submit.contactForm');
			}

		}else{
			event.preventDefault();
		}
	};

	// display validation markers
	_.displayValidate = function(){
		
		var requireNode, formFields = [];

		formFields = formFields.concat([].slice.call(this.contactForm.getElementsByTagName('input')));
		formFields = formFields.concat([].slice.call(this.contactForm.getElementsByTagName('textarea')));
		formFields = formFields.concat([].slice.call(this.contactForm.getElementsByTagName('select')));

		for (var i = formFields.length - 1; i >= 0; i--) {
			
			if(formFields[i].hasAttribute('required') && formFields[i].getAttribute('required') !== 'false'){
				
				requireNode = document.createElement( 'div' );
				requireNode.className = "flag-required";
				requireNode.appendChild(document.createTextNode("*"));

				if (formFields[i].nextSibling) {
				  formFields[i].parentNode.insertBefore(requireNode, formFields[i].nextSibling);
				} else {
				  formFields[i].parentNode.appendChild(requireNode);
				}
			}
		}
	};

	// init function - let's get things started
	_.init();
});