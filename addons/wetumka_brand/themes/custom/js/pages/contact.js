// contact.js
define (['default','lib/pubsub'], function (def,PubSub) {
	"use strict";

	var _ = {};

	// init function
	_.init = function(){
		// validation steps
		this.activeFieldset = null;
		// buttons and form elements
		this.btnSubmit = document.getElementById('button_submit');
		this.contactForm = document.getElementById('contact_form');
		
		// events
		this.btnSubmit.addEventListener("click", function(e){
			PubSub.publish('event.click.btnSubmit',e);
		}, false);
		this.contactForm.addEventListener("submit", function(e){
			PubSub.publish('event.submit.contactForm',e);
			_.submitForm(e);
		}, false);
	};

	// init function - let's get things started
	_.init();
});