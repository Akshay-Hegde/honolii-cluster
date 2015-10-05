// about-us.js
define (['default'], function (def) {
	"use strict";

	var _ = {};

	// init function
	_.init = function(){
		this.disqus();
	};

	_.disqus = function(){
		/* * * CONFIGURATION VARIABLES * * */
		var disqus_shortname = 'wetumka';
		
		/* * * DON'T EDIT BELOW THIS LINE * * */
		(function () {
			var s = document.createElement('script'); s.async = true;
			s.type = 'text/javascript';
			s.src = '//' + disqus_shortname + '.disqus.com/count.js';
			(document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
		}());
	};

	_.init();

	return _;
});