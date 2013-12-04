<!DOCTYPE html>
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <script type="text/javascript" src="http://src.7808.cn/jquery/jquery-1.10.2.min.js?v=2.0.2"></script>
        <script type="text/javascript" src="js/html5upload.js"></script>
        <style>
            .html5uploadul {
                height: 100px;
                width: 100px;
                margin: 0;
                padding: 0;
                display: inline-block;
                margin-right: 5px;
            }
            .html5uploadul li{
                list-style:none;
                float: left;
                position: relative;
                height: 100px;
                width: 100px;
                position: relative;
                border: 1px solid #ccc;
                margin-right: 5px;
            }

            .html5uploadul li progress{
                width: 98%;
                position: absolute;
                height: 14px;
                top:43px
            }

            .html5uploadul li img{
                max-width: 100px;
                max-height: 100px
            }

            .html5uploadul .close {
                margin: 0;
                position: absolute;
                right: 2px;
                top: 2px;
                background-position: 0 -85px;
                height: 17px;
                overflow: hidden;
                width: 17px;
              
                background-color: #fff;
                border: 1px solid #dcdcdc;
                cursor: pointer;
            }
            .html5filebtn{
                width: 80px;
                height: 22px;
                overflow: hidden;
                cursor: pointer;
                display: inline-block;
                height: 23px;
                margin-right: 5px;
                vertical-align: middle;
                border: 0;
                margin-bottom: 10px;
            }

            .html5filebtn input{
                width: 80px;
                height: 22px;
                overflow: hidden;
                background: url(upload-btn.jpg) ;
                padding-left: 500px;
            }
            .html5filebtn input:hover{
                background-position: 0 -22px
            }
            #item-albums .html5uploadul li{height: 109px}
            #item-albums {height: 120px}
            #item-albums .html5uploadul {float: left;width: auto}
            #item-albums li{float: left}
            #item-albums .album-title{width: 100px;border: 0;border-top: 1px solid #ccc;position: absolute;left: 0; bottom: 0}
        </style>
    </head>
    <body>
        <div id="item-albums"></div>
        <div id="file" class="html5filebtn"></div>


        <hr />
        <div id="one"></div>
        <hr />
        <div id="two"></div>
        <script type="text/javascript">
            //  html5upload.init("s");
            //  html5upload.create();

            /**
             * 保存相册
             */
            function saveAlbumChange() {
                $.post("http://romeo.7808.cn/item/image/album", $("#item-image-form").serialize(), function(r) {
                    // parent.$.fn.tips(r.level, r.message);
                }, 'json');

            }


            var dv = [{"id": "927", "item_id": "753", "title": "", "description": "nodescription", "min_src": "http:\/\/pic.7808.cn\/item_album\/20131202\/13859629901845.thumb.jpg", "max_src": "http:\/\/pic.7808.cn\/item_album\/20131202\/13859629901845.jpg", "created": "2013-12-02 13:43:11", "value": "http:\/\/pic.7808.cn\/item_album\/20131202\/13859629901845.jpg"}, {"id": "928", "item_id": "753", "title": "", "description": "nodescription", "min_src": "http:\/\/pic.7808.cn\/item_album\/20131202\/13859629905544.thumb.jpg", "max_src": "http:\/\/pic.7808.cn\/item_album\/20131202\/13859629905544.jpg", "created": "2013-12-02 13:43:11", "value": "http:\/\/pic.7808.cn\/item_album\/20131202\/13859629905544.jpg"}, {"id": "929", "item_id": "753", "title": "", "description": "nodescription", "min_src": "http:\/\/pic.7808.cn\/item_album\/20131202\/13859629909028.thumb.jpg", "max_src": "http:\/\/pic.7808.cn\/item_album\/20131202\/13859629909028.jpg", "created": "2013-12-02 13:43:11", "value": "http:\/\/pic.7808.cn\/item_album\/20131202\/13859629909028.jpg"}];

            new multipleUpload({
                input: "picture",
                placholderImg: "http://pic.7808.cn/static/wutupian.jpg",
                postUrl: "/upload/upload.php",
                defaultValue: dv,
                postParams: {
                    updir: 'test',
                },
                before: function(defaultValue, li) {

                    var html = "<input type='hidden' name='min_src[]' value='" + defaultValue.min_src + "'>" +
                            "<input type='hidden' name='max_src[]' value='" + defaultValue.max_src + "'>" +
                            "<input type='text' name='title[]' onchange='saveAlbumChange()'  value='" + defaultValue.title + "' placeholder='请填写说明' class='album-title'/>";

                    $(li).append(html);

                },
                wrap: "item-albums",
                btn: "file",
                multiple: true,
                onDelete: function(li) {
                    li.parentNode.removeChild(li);
                },
                callback: function(json, li) {
                    var html = '';
                    html += "<input type='hidden' name='min_src[]' value='" + json.thumbUrl + "'>" +
                            "<input type='hidden' name='max_src[]' value='" + json.url + "'>" +
                            "<div><input type='text' name='title[]' onblur='saveAlbumChange()' value='' placeholder='请填写说明' class='album-title'/></div>";
                    $(li).append(html);
                }
            });



            sampleOneUpload("one", "pic");
            sampleOneUpload("two", "pic",'http://pic.7808.cn/item_album/20131202/13859629909028.jpg');

        </script>
    </body>
</html>
