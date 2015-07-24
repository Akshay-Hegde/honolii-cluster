// contact.js
define (['H5F'], function (H5F) {
	"use strict";

	var _ = {};

	// init function
	_.init = function(){

		// buttons and form elements
		this.btnNext = document.getElementById('button_next');
		this.btnBack = document.getElementById('button_back');
		this.btnSubmit = document.getElementById('button_submit');
		this.contactForm = document.getElementById('contact_form');
		// shim validation
		H5F.setup(this.contactForm);
		// events
		this.btnSubmit.addEventListener("click", this.e.validateStep2.bind(this), false);
		this.btnBack.addEventListener("click", this.e.resetForm.bind(this), false);
		this.btnNext.addEventListener("click", this.e.validateStep1.bind(this), false);
		this.contactForm.addEventListener("submit", this.e.submitForm.bind(this), false);
		// diplay validation in fields
		this.displayValidate();
	};
	// events functions
	_.e = { 
		
		step1Valid : null,
		step2Valid : null,

		// move form to step 2
		validateStep1 : function(event){
			// this = _; via bind method
			this.e.step1Valid = false;
			// step 1 form fields
			var fName = document.getElementById('input_fullname');
			var eMail = document.getElementById('input_email');
			var subject = document.getElementById('input_subject');
			// if valid move to step 2
			if(fName.validity.valid && eMail.validity.valid && subject.validity.valid){
				this.e.step1Valid = true;
				this.streamID = subject.value;
			}
		},

		// do some form validation
		validateStep2 : function(event){
			// this = _; via bind method
			//debugger;
		},

		// reset form to step 1
		resetForm : function(event){
			// this = _; via bind method
			var buttonsFieldset, activeFieldset = this.activeFieldset;

			//hide and disable the activeFieldset
			activeFieldset.classList.remove('event-show-fieldset-active');
			activeFieldset.disabled = true;

			//go back to step 1
			buttonsFieldset = document.getElementById('contact_submit');
			buttonsFieldset.classList.remove('event-show-fieldset-active');
			buttonsFieldset.disabled = true;

			document.getElementById('contact_start').classList.add('event-show-fieldset-active');
		},

		// submit form
		submitForm : function(event){
			// this = _; via bind method
			if(this.e.step1Valid){
				event.preventDefault();
				this.gotoStep2();
				this.e.step1Valid = false;
			}
			if(this.e.step2Valid){

			}
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
				}
				else {
				  formFields[i].parentNode.appendChild(requireNode);
				}
			}
		}
	};

	// goto step 2
	_.gotoStep2 = function(){
		var activeFieldset, buttonsFieldset;
		document.getElementById('contact_start').classList.remove('event-show-fieldset-active');
		buttonsFieldset = document.getElementById('contact_submit');
		buttonsFieldset.classList.add('event-show-fieldset-active');
		buttonsFieldset.disabled = false;

		switch(this.streamID){
			case '12': //General Inquiry
				activeFieldset = document.getElementById('contact_general');
				break;
			case '13': //Job Inquiry
				activeFieldset = document.getElementById('contact_job');
				break;
			case '14': //RFP Inquiry
				activeFieldset = document.getElementById('contact_rfp');
				break;
		}
		activeFieldset.classList.add('event-show-fieldset-active');
		activeFieldset.disabled = false;
		// save element to root object
		this.activeFieldset = activeFieldset;
	};

	// init function - let's get things started
	_.init();
});