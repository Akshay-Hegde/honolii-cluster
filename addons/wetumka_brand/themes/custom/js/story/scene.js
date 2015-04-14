define ([], function () {
	
	var Scene = function (current) {
		current = current;
	};

	Scene.prototype.route_start = function(section){
		debugger;
		console.log(this.name);
		//story.current = this.name;
	};

	return Scene;
});