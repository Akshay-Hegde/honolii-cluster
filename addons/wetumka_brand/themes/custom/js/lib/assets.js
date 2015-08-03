define (['lib/ajax'], function (Ajax) {
  "use strict";

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
    
    var i, that, fileLoc, regex, img, imgLoad, imgError, svgAsset;
    that = this; // change scope of this
    regex = /(?:\.([^.]+))?$/;

    imgLoad = function(data, status) {
      //debugger;
      that.cache[that.getFileNameFromPath(this.src)] = this;
      that.successCount += 1;
      if (that.isDone() || that.downloadQueue.length === 0) {
        downloadCallback(that);
      }
    };
    imgError = function(data, status) {
      //debugger;
      that.errorCount += 1;
      if (that.isDone() || that.downloadQueue.length === 0) {
        downloadCallback(that);
      }
    };
    svgAsset = function(xhr,error) {
      //debugger;
      if(error){
        that.errorCount += 1;
        if (that.isDone() || that.downloadQueue.length === 0) {
          downloadCallback(that);
        }
      }else{
        //debugger;
        that.cache[that.getFileNameFromPath(xhr.responseXML.URL)] = xhr.responseXML;
        that.successCount += 1;
        if (that.isDone() || that.downloadQueue.length === 0) {
          downloadCallback(that);
        }
      }
    };

    if (this.isDone()) { //check if called already
      downloadCallback();
      return;
    } else { //cache files if not
      for (i = 0; i < this.downloadQueue.length; i++) {
        fileLoc = this.downloadQueue[i]; // full file path

        if (regex.exec(fileLoc)[1] !== 'svg') {
          // load all images not SVG
          img = new Image();
          img.addEventListener("load", imgLoad, false);
          img.addEventListener("error", imgError, false);
          img.src = fileLoc;

        } else {
          //load SVG as XML
          Ajax.get(fileLoc, svgAsset);
        }
      }
    }
  };

  AssetManager.prototype.isDone = function() {
    return (this.downloadQueue.length == this.successCount + this.errorCount);
  };

  AssetManager.prototype.getCachedAsset = function(fileName) {
    // can be an image object or svg (xml node)
    if(fileName.substring(fileName.lastIndexOf('.') + 1) === 'svg'){
      return this.cache[fileName].getElementsByTagName('svg')[0];
    }else{
      // add code for img object later
    }
    //return this.cache[path];
  };

  AssetManager.prototype.getFileNameFromPath = function(pathString) {
    //debugger;
    return pathString.substring(pathString.lastIndexOf('/') + 1);
  };

  return AssetManager;

});