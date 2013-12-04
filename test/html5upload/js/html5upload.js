/**
 * HTML5 upload
 * @author Dawn
 * @date 2013-11-29
 */

var test = function(obj){
    var str = ''
    console.log("debug");
    if(typeof obj != 'string'){
        for(var i in obj){
            str += i + " " + obj[i] + "\r\n";
        }
        console.log(obj);
    }else{
        console.log(obj);
    }
    
};

var obj_copy = function(target, obj){
    for(var i in obj){
        target[i] = obj[i];
    }
};



var multipleUpload = function(opt) {
 
    var options = {
    
        wrap            : "", //容器
        progress        : null, //进度条Hoder ID
        btn             : null, //上传按钮 Holder ID
        filePostName    : "imgFile", //file 的 name,
        input           : "image",
        multiple        : false, //是否支持多文件上传
        defaultValue    : [], 
        allowExt        : ['jpg','jpeg','gif','png'],//允许上传文件类型,
        preview        :  function(json, li){ //图片预览
             var self = this;
             var img  = document.createElement("img");
             var span = document.createElement("span");
             img.setAttribute("src", json.url);
             img.className = "preview-img";
             li.appendChild(img);
            if (this.isShowDelete) {
                span.innerHTML = "×";
                span.className = "close";
                span.title     =  "删除";
                span.onclick   = (function(li) {
                    return function() {
                        self.onDelete(li);
                    };
                })(li);
                li.appendChild(span);
            }
             li.appendChild(img);
        },
        onDelete :function(li){
                li.parentNode.removeChild(li);
        },
        before          : function(defaultValue, li){
            var input = document.createElement("input");
            input.setAttribute("name", this.input);
            input.type = "hidden";
            input.setAttribute("value", defaultValue.value);
            li.appendChild(input);
        },
        callback           :function(json, li){
            var input = document.createElement("input");
            input.setAttribute("name", this.input);
            input.type = "hidden";
            input.setAttribute("value", json.url);
            li.appendChild(input);
        },
        isShowDelete    : true
    };
    obj_copy(options,opt);
    
    this.options = options;
    
     var wrap, ul, li, fileInput, progress, that = this ;
     
     
    wrap    = typeof options.wrap === 'string' ? document.getElementById(options.wrap) : options.wrap; 
    this.ul = ul = document.createElement("ul");
    ul.className = "html5uploadul";
    wrap.appendChild(ul);
    
 
    this.init(ul);
    
    
    fileInput           = document.createElement("input");
    fileInput.type      = "file";
    fileInput.name      = options.filePostName;
    fileInput.multiple  = options.multiple;
    fileInput.className = "html5filebtn";
    var fileInputHolder = typeof options.btn === 'string' ? document.getElementById(options.btn) : options.btn;
    
    fileInputHolder.appendChild(fileInput);
    
    fileInput.onchange = function() {
        var files = this.files;
        for(var i = 0; i < files.length ; i++ ){
            //开始上传
            that.startUpload(files[i]);
        }
    };
    
};

multipleUpload.prototype.startUpload = function(upfile) {
    var options  = this.options;
    var ul       = this.ul;
    var fileType = upfile.type;
    var fileSize = upfile.size;
    
    fileType = fileType.substr(fileType.indexOf("/") + 1);
    var allow = false;
    for (var ai = 0; ai < options.allowExt.length; ai++) {
        if (options.allowExt[ai] === fileType) {
            allow = true;
        }
    }
    if (fileSize > 2 * 1024 * 1024) {
        alert("文件太大");
        return;
    }
    if (!allow) {
        alert("文件类型不允许");
        return;
    }


    progress = document.createElement("progress");
    li = document.createElement("li");


    progress.value = 0;
    progress.max = 100;



    li.appendChild(progress);
   // li.setAttribute("name", "i-" + i);

    if (options.multiple) {
        ul.appendChild(li);
    } else {
        if (ul.lastChild) {
            ul.removeChild(ul.lastChild);
        }
        ul.appendChild(li);
    }
    var uploadSuccess = (function(li, progress) {
        return function(json) {
            options.preview(json, li);
            progress.style.display = "none";
            options.callback(json, li);
        };
    })(li, progress);

    var uploadFail = (function(li, progress) {
        return function(json) {
            li.parentNode.removeChild(li);
        };
    })(li);


    // console.log(upfile);
    options.postParams[options.filePostName] = upfile;
    //  console.log(options.postParams);
    new processFileUpload(options.postUrl, options.postParams, progress, uploadSuccess, uploadFail);


};

multipleUpload.prototype.init = function(ul){
  var li;
  var options = this.options;
  
  //console.log(options)
  
  //格式 [{input:'表单名空则使用 setting的input值',value:'值',url,'地址 地址为空则使用value值'}]
  var dv = options.defaultValue;
  
  defaultValue = [];

  if(typeof dv === 'string'){
      defaultValue.push({
          input : options.input,
          value : dv,
          url   : dv
      });
  }else{
      for(var i=0; i < dv.length; i++){
          
       dv[i].input = dv[i].input ? dv[i].input : options.input;  
       dv[i].value =  dv[i].value ? dv[i].value : dv[i].url;  
       dv[i].url   =  dv[i].url ? dv[i].url : dv[i].value; 
        defaultValue.push(dv[i]);
      }
  }
  
  
  for(var i=0; i < defaultValue.length; i++){
    dv = defaultValue[i];
    li = document.createElement("li");
    this.options.preview(dv, li);
    this.options.before(dv, li);
    ul.appendChild(li);
  }

};

/**
 * 处理上传
 * @param {type} file
 * @param {type} progress
 * @param {type} uploadSuccess
 * @returns {processFileUpload}
 */
var processFileUpload = function (postUrl, postParams, progress, uploadSuccess, uploadFail){
    
    var self = this;
    self.uploadSuccess = uploadSuccess;
    self.progress = progress;
    self.uploadFail = uploadFail;
    
     //     file  input file实例
    var formData = new FormData();

    for(var i in postParams){
       formData.append(i, postParams[i]); 
    }
    
    
    var xhr = new XMLHttpRequest();
    xhr.upload.addEventListener("progress", function(){ self.process.apply(self, arguments);}, false);
    xhr.addEventListener("load", function(){ self.complete.apply(self, arguments);}, false);
    xhr.addEventListener("error", function(){ self.failed.apply(self, arguments);}, false);
    xhr.addEventListener("abort", function(){ self.canceled.apply(self, arguments);}, false);
    xhr.open("POST", postUrl);
    xhr.send(formData);
};

processFileUpload.prototype.process = function(e){
  // console.log("instance " + (this instanceof processFileUpload))
   //this 为 XMLHttpRequestUpload
    if (e.lengthComputable) {
        this.progress.value = Math.round(e.loaded * 100 / e.total);
    }else {
        console.log('nable to compute progress');
    }
};
processFileUpload.prototype.complete = function(e){
    var json = eval("(" + e.target.responseText + ")");
    if(json.error == ''){
        this.uploadSuccess(json);
    }else{
        alert(json.error);
        this.uploadFail();
    }

};
processFileUpload.prototype.failed = function(e){
    this.uploadFail();
};
processFileUpload.prototype.canceled = function(e){
        
};


/**
 * 上传一个图片
 * @param string id 容器id
 * @param {type} input 保存图片的input名
 * @param {type} url 图片url
 * @param {type} updir 上传目录
 * @returns {undefined}
 */
function sampleOneUpload(id, input, url, updir){
    
    var defaultPicHolder = "http://pic.7808.cn/static/wutupian.jpg";
    
    var btn = document.createElement("span");
    var wrap = document.getElementById(id);
    btn.className = "html5filebtn";
//    btn.onclick = function(e){
//        var ev = document.createEvent('HTMLEvents');
//        ev.initEvent('click', false, true);
//        btn.firstChild.dispatchEvent(ev);
//    };
    new multipleUpload({
            input :input,
            defaultValue: url ? url : defaultPicHolder,
            postUrl : "/upload/upload.php",
            postParams: {
                updir     : updir || "temp"
            },
            wrap : id,
            btn : btn,
            multiple : false,
            before : function(defaultValue){
                  wrap.appendChild(btn);
                  
                if(wrap.lastChild.tagName === 'INPUT'){
                     wrap.removeChild(wrap.lastChild);
                 }
                if (defaultValue.url !== defaultPicHolder) {
                    var input = document.createElement("input");
                    input.setAttribute("name", defaultValue.input);
                    input.type = "hidden";
                    input.setAttribute("value", defaultValue.url);
                    wrap.appendChild(input);
                }
                
            },
            onDelete :function(li){
                li.parentNode.removeChild(li);
            },
            callback : function(json, li){
                
                 if(wrap.lastChild.tagName === 'INPUT'){
                     wrap.removeChild(wrap.lastChild);
                 }
                 
                var input = document.createElement("input");
                input.setAttribute("name", this.input);
                input.type = "hidden";
                input.setAttribute("value", json.url);
                wrap.appendChild(input);
                
            }
        });
      
}