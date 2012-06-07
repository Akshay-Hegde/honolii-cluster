// Image write object and methods
function imageInfo(imageID,imageWidth,imageHeight){
	this.imageID = imageID;
	this.imageWidth = imageWidth;
	this.imageHeight = imageHeight;
	this.modulePath = '/files/';
}
function imageWrite(location,type){
	with (this){
		if(imageWidth){
			modulePath = modulePath+'thumb/'+imageID+'/'+imageWidth;
			if(imageHeight){
				modulePath = modulePath+'/'+imageHeight;
			}
		}else{
			modulePath = modulePath+'large/'+imageID;
		}
		if(type == 'image'){
			var template = '<img class="image-injected" src="'+modulePath+'" />';
		}else if(type == 'background'){
			var template = '<div class="image-injected" style="background-image:url('+modulePath+')"></div>';
		}
		
						
		$(location).append(template);
	}
}
imageInfo.prototype.imageWrite = imageWrite;