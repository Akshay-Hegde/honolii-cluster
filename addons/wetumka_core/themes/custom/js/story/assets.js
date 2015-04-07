define (['jquery','snapsvg'], function ($,Snap) {
  // http://www.html5rocks.com/en/tutorials/games/assetmanager/
  var AssetManager = function () {
    this.downloadQueue = [];
    this.cache = {};
    this.successCount = 0;
    this.errorCount = 0;
  };

  AssetManager.prototype.queueDownload = function(path) {
    if (typeof path === 'string') {
      this.downloadQueue.push(path);
    }else if(Array.isArray(path)){
      this.downloadQueue = this.downloadQueue.concat(path);
    }else{
      console.error('AssetManager.queueDownload(string || array)');
    }
  };

  AssetManager.prototype.downloadAll = function(downloadCallback) {
    
    var i, that, file, path, regex, img, imgLoad, imgError;
    that = this; // change scope of this
    regex = /(?:\.([^.]+))?$/;

    imgLoad = function(data, status, xhr) {
      if (status === "success"){
        that.cache[this] = data;
      }
      that.successCount += 1;
      if (that.isDone() || that.downloadQueue.length === 0) {
        downloadCallback();
      }
    };
    imgError = function(data, status, xhr) {
      that.errorCount += 1;
      if (that.isDone() || that.downloadQueue.length === 0) {
        downloadCallback();
      }
    };

    if (this.isDone()) { //check if called already
      downloadCallback();
      return;
    } else { //cache files if not
      for (i = 0; i < this.downloadQueue.length; i++) {
        file = this.downloadQueue[i]; // used as key for cache object
        path = themePath + '/img/' + file; // full file path

        if (regex.exec(path)[1] !== 'svg') {
          // load all images not SVG
          img = new Image();
          img.addEventListener("load", imgLoad, false);
          img.addEventListener("error", imgError, false);
          img.src = path;
          this.cache[file] = img;

        } else {
          //load SVG as XML
          $.ajax(path, {
            dataType : 'xml',
            success : imgLoad.bind(file),
            error : imgError
          });
        }
      }
    }
  };

  AssetManager.prototype.isDone = function() {
    return (this.downloadQueue.length == this.successCount + this.errorCount);
  };

  AssetManager.prototype.getAsset = function(path) {
    return this.cache[path];
  };

  return AssetManager;

});