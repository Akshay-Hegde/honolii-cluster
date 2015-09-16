// work.js
define (['default','lib/pubsub','lib/sections-nav'], function (def,PubSub,sections) {
	"use strict";

	// -------- PubSub Publish Events ---------------
	// event.click.pageCTA - (single) user triggered (click,touch) event, new page load

	var _ = {};
	
	_.init = function(){
		var content, cta;

		content = document.getElementById('content');
		cta = content.querySelectorAll('.cta');

		for (var i = cta.length - 1; i >= 0; i--) {
			cta[i].addEventListener('click', function(e){PubSub.publish('event.click.pageCTA', e );}, false);
		}

	};

	_.init();

	return _;
});