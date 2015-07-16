// story.js
define (['jquery', 'story/svg', 'story/assets', 'routie'], function ($, svg, AssetManager) {  
  
  var story, Scene, imgPath;

  // story manager
  story = {
  	current : [], // route hash string
  	scenes : {}
  };

  // scene manager
  Scene = function () {
  	this.section = false; // section string
  	this.assetsManager = new AssetManager(); // AssetManager Class instance
  };
	Scene.prototype.route_start = function(section){
		if(section !== undefined){
			story.current.unshift(routie.lookup(this.name,{'section':section}));
			story.scenes[this.name].section = section;
		}else{
			story.current.unshift(this.name);
		}

		story.scenes[this.name].route();
	};

	// routes - it all starts here
  story.scenes.splash = new Scene();
  story.scenes.scene_1 = new Scene();
  story.scenes.scene_2 = new Scene();
  story.scenes.scene_3 = new Scene();
  story.scenes.scene_4 = new Scene();
  story.scenes.scene_5 = new Scene();
  
  // assets manager
  // imgPath = themePath + '/img/';

  story.scenes.splash.assetsManager.queueDownload([
  	'svg/scn-1-waves.svg',
  	'svg/wetumka-logo.svg'
  ]);

  // routes functions
	story.scenes.splash.route = function(){
		this.assetsManager.downloadAll(function(){
			var am = story.scenes.splash.assetsManager; // scene asset manager object
			var canvas = Snap("#splash");
			// asset vars
			var wave, w1, w2;
			var logo;

			// setup scene
			canvas.attr({
				viewBox: '0 0 1280 800'
			});
			// load waves background
			wave = am.getAsset(am.downloadQueue[0]);
			wave = Snap($(wave).find('svg')[0]);
			w1 = wave.select('.wave_1 .stroke');
			w2 = wave.select('.wave_2 .stroke');

      w2.attr({fill:"l(.5, 0, .5, 1)#0286BA:10-#006798:90"});
      w1.attr({fill:"l(.5, 0, .5, 1)#0286BA-#006798:60"});

			canvas.append(wave);
			// load logo
			logo = am.getAsset(am.downloadQueue[1]);
			logo = Snap($(logo).find('svg')[0]);

			canvas.append(logo);
		});
	};

	story.scenes.scene_1.route = function(){
		console.log('1');
	};

	story.scenes.scene_2.route = function(){
		console.log('2');
	};

	story.scenes.scene_3.route = function(){
		console.log('3');
	};

	story.scenes.scene_4.route = function(){
		console.log('4');
	};

	story.scenes.scene_5.route = function(){
		console.log('5');
	};
	// routes config
  routie({
    'splash ': story.scenes.splash.route_start,
		'scene_1 scene_1/:section?': story.scenes.scene_1.route_start,
		'scene_2 scene_2/:section?': story.scenes.scene_2.route_start,
		'scene_3 scene_3/:section?': story.scenes.scene_3.route_start,
		'scene_4 scene_4/:section?': story.scenes.scene_4.route_start,
		'scene_5 scene_5/:section?': story.scenes.scene_5.route_start
	});

  // // Set pixel density class
  // if (window.devicePixelRatio > 1) {
  //   $('body').addClass('px_density_2x');
  // } else {
  //   $('body').addClass('px_density_1x');
  // }
    
});