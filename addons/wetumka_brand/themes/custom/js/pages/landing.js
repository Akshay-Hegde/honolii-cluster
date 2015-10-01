// landing.js
define (['default','lib/pubsub'], function (def,PubSub) {
	"use strict";

	// -------- PubSub Publish Events ---------------

	var _ = {};
	
	_.init = function(){
		var campaignString = location.search;
		this.contactForm = document.getElementById('capture_form');
		this.btnSubmit = document.getElementById('button_submit');
		this.inputName = document.getElementById('input_fullname');
		this.inputEmail = document.getElementById('input_email');
		this.inputCampaign = document.getElementById('input_campaign');

		this.contactForm.addEventListener("submit", function(e){
			PubSub.publish('event.submit.captureForm',e);
		}, false);
		this.btnSubmit.addEventListener("click", function(e){
			PubSub.publish('event.click.btnSubmit',e);
		}, false);

		this.inputCampaign.value = campaignString;
	};


	_.init();

	return _;
});