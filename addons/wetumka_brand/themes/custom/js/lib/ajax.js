// basic AJAX calls without jQuery
define ([], function () {
	"use strict";

	var _ = {};

	_.get = function(url,callback){
		this.xhr = this.init(url,callback);
		this.xhr.open('GET', encodeURI(url), true);
		this.xhr.send();
	};

	_.post = function(url,callback){
		this.xhr = this.init(callback);
		this.xhr.open('POST', encodeURI(url), true);
		this.xhr.send();
	};

	_.init = function(url,callback){
		var xhr = new XMLHttpRequest();

		if(typeof callback === 'function'){
			xhr.onreadystatechange = function(){

				if(xhr.readyState < 4) {
	        return;
	      }
	       
	      if(xhr.status !== 200) {
	        return;
	      }

	      // all is well  
	      if(xhr.readyState === 4) {
	        callback(xhr,url);
	      }         
			};
			xhr.onerror = function(){
				callback(xhr,'error');
			};
		}

		return xhr;
	};

	return _;
});