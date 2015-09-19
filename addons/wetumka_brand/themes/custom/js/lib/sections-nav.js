// sections-nav.js
define (['default','lib/pubsub'], function (def, PubSub) {
	"use strict";

	// -------- PubSub Publish Events ---------------
	// 
	
	var _ = {};

	// init function
	_.init = function(){
		var sectionNav;

		// ---------- PubSub Subscribers --------------
		PubSub.subscribe('event.window.scrollDelay', function(){_.setSectionNavActive();});

		this.getSections = this.getSections('content','section.block-wrapper');
		this.buildSectionNav('jump-section-nav');
		
		// close nav on click
		sectionNav = this.getSectionNav();
		for (var i = sectionNav.length - 1; i >= 0; i--) {
			sectionNav[i].addEventListener('click', function(e){PubSub.publish('event.click.navToggle', e );}, false);
		}
	};

	_.buildSectionNav = function(nodeID){
		var navParent,sections,title,hash,liNode,aNode;

		navParent = document.getElementById(nodeID);
		sections = this.getSections();

		for (var i = 0; i < sections.length; i++) {
			title = this.getSectionTitle(sections[i]);
			hash = this.getSectionHash(sections[i]);
			title = document.createTextNode(title);
			liNode = document.createElement('li');
			aNode = document.createElement('a');
			// set first and last classes
			if(i === 0){
				liNode.classList.add('first');
			}else if(i === (sections.length - 1)){
				liNode.classList.add('last');
			}

			aNode.setAttribute('href',hash);
			aNode.appendChild(title);
			liNode.appendChild(aNode);

			navParent.appendChild(liNode);
		}

		this.getSectionNav = this.getSectionNav('jump-section-nav','li');
	};

	_.getSections = function(parentID,nodeSelector){ //returns a closure
		var content = document.getElementById(parentID);
		content = content.querySelectorAll(nodeSelector);
		return function(){ return content;};
	};

	_.getSectionNav = function(parentID,nodeSelector){ //returns a closure
		var content = document.getElementById(parentID);
		content.parentNode.parentNode.classList.add('showNavList');
		content = content.querySelectorAll(nodeSelector);
		return function(){ return content;};
	};

	_.getSectionTitle = function(domNode){
		var title;
		if(domNode.classList.contains('intro-section')){
			title = domNode.querySelector('h1');
			title = title.textContent;
		}else if(domNode.classList.contains('sub-section')){
			title = domNode.querySelector('h2');
			title = title.textContent;
		}
		return title;
	};

	_.getSectionHash = function(domNode){
		var hash;
		hash = domNode.getAttribute('id');
		hash = location.pathname + '#' + hash;
		return hash;
	};

	_.setSectionNavActive = function(){
		var winTop = window.pageYOffset,
			winBot = winTop + window.innerHeight,
			inView = Math.floor(window.innerHeight / 2),
			eleTop,eleBot,active,elementObj,elementNavObj;

		elementObj = this.getSections();
		elementNavObj = this.getSectionNav();

		for (var i = 0; i < elementObj.length; i++) {
			eleTop = elementObj[i].offsetTop + inView;
			eleBot = (eleTop + elementObj[i].offsetHeight) - inView;

			if (winBot > eleTop && winTop < eleBot) {
				elementNavObj[i].classList.add('current');
			}else{
				elementNavObj[i].classList.remove('current');
			}
		}
	};

	_.init();

	return _;
});