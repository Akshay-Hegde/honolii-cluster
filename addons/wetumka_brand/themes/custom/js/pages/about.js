// about.js
define (['default','lib/pubsub'], function (def,PubSub) {
	"use strict";

	// -------- PubSub Publish Events ---------------
	// event.click.pageCTA - (single) user triggered (click,touch) event, new page load
	// event.click.memberSocial - (multi) user triggered (click,touch) event, new page load - target _blank
	// event.click.partnerLink - (multi) user triggered (click,touch) event, new page load - target _blank
	
	var _ = {};

	_.init = function(){
		var content, cta, social, partner;

		content = document.getElementById('content');
		cta = content.querySelectorAll('.cta');
		social = content.querySelectorAll('.social-icon');
		partner = content.querySelectorAll('.partner-href');

		for (var i = cta.length - 1; i >= 0; i--) {
			cta[i].addEventListener('click', function(e){PubSub.publish('event.click.pageCTA', e );}, false);
		}

		for (var j = social.length - 1; j >= 0; j--) {
			social[j].addEventListener('click', function(e){PubSub.publish('event.click.memberSocial', e );}, false);
		}

		for (var k = partner.length - 1; k >= 0; k--) {
			partner[k].addEventListener('click', function(e){PubSub.publish('event.click.partnerLink', e );}, false);
		}

	};

	_.init();

	return _;

});