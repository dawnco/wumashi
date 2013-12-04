var getHost = function(url) {
        var js = document.scripts || $('script'), jsPath = js[js.length - 1].src;
        return jsPath.substring(0, jsPath.lastIndexOf("/") + 1);
  }
  //document.domain=getHost();
  alert(getHost())