/*
 ---------------------------------------------------------------
  Instagram Grid - ERM - Wetumka Interactive LLC - wetumka.net
 ---------------------------------------------------------------
 Build for JASC Gallery
 
 Requires - jQuery, windowManager
 
 */ 

// define WindowManager Class
function InstagramGrid(dataObj,element){
    this.gridSize = 306; // low resolution instagram image
    this.imgRes = 'low_resolution';
    this.imgArray = [];
    this.gridWidth = null;
    this.gridHeight = null;
    this.gridArray = [];
    this.$element = $(element);
    
    for (var key in dataObj){
        var img,url
        img = new Image();
        url = dataObj[key].images[this.imgRes].url;
        img.src = url;
        this.imgArray.push(url);
    }

    windowManager.$window.bind('windowResize',this,this.resize);
}
InstagramGrid.prototype.resize = function(event,obj){
    var width, height, total, gridSize, imgArray, gridArray, length; 
        
    if(!event){
        gridSize = this.gridSize;
        gridWidth = this.gridWidth;
        gridHeight = this.gridHeight;
        imgArray = this.imgArray;
    }else{
        gridSize = event.data.gridSize;
        gridWidth = event.data.gridWidth;
        gridHeight = event.data.gridHeight;
        imgArray = event.data.imgArray;
    }
    
    width = Math.ceil(windowManager.width / gridSize);
    height = Math.ceil(windowManager.height / gridSize);
    
    total = width * height;
    length = imgArray.length;
    gridArray = [];
    
    if(width != gridWidth || height != gridHeight){ // only change grid when needed
        if(length > total){
            var arrayLength;
            gridArray = imgArray.slice(0,total);
        }else{
            var x;
            x = total - length;
            for(x > 0; x--;){
                var random = Math.floor(Math.random()*length);
                gridArray.push(imgArray[random]);
            }
            gridArray = imgArray.concat(gridArray);
        }
        // reset obj values
        if(!event){
            this.gridWidth = width;
            this.gridHeight = height;
            this.gridArray = gridArray;
            this.setGrid();
        }else{
            event.data.gridWidth = width;
            event.data.gridHeight = height;
            event.data.gridArray = gridArray;
            event.data.setGrid();
        }
    }
}

InstagramGrid.prototype.setGrid = function(){

    var HTMLstring,x,element,length;
    length = this.gridArray.length;
    HTMLstring = '<div class="grid-mask"><div class="grid" style="height:'+windowManager.height+'">';
    element = this.$element.children('.grid-mask');
    for(x = 0;x < length; x++){
        var clear = (x % (this.gridWidth) === 0)? 'clear' : 'not-clear';
        HTMLstring += '<div class="grid-item '+clear+'" style="background-image:url('+this.gridArray[x]+')"></div>';
    }
    HTMLstring += '</div></div>';
    
    if(element.length){
        element.remove();
    }
    this.$element.prepend(HTMLstring);
}