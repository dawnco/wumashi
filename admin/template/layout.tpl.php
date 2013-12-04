<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=7" />
        <title></title>
        <link href="<?php echo static_url('dwz/themes/default/style.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo static_url('dwz/themes/css/core.css'); ?>" rel="stylesheet" type="text/css" />
        <!--[if IE]><link href="<?php echo static_url('dwz/themes/css/ieHack.css'); ?>" rel="stylesheet" type="text/css" /><![endif]-->
        <style type="text/css">  
            #header{ height:50px}
            #leftside, #container, #splitBar, #splitBarProxy{ top:50px}
        </style>

        <script src="<?php echo static_url('script/jquery.js'); ?>" type="text/javascript"></script>

        <script src="<?php echo static_url('script/jquery.cookie.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo static_url('script/jquery.validate.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo static_url('script/jquery.bgiframe.js'); ?>" type="text/javascript"></script>


        <script src="<?php echo static_url('dwz/js/dwz.js'); ?>" type="text/javascript"></script>


        <script src="<?php echo static_url('dwz/xheditor/xheditor-1.2.1.min.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo static_url('dwz/xheditor/xheditor_lang/zh-cn.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo static_url('dwz/js/dwz.regional.zh.js'); ?>" type="text/javascript"></script>
        <script src="<?php echo static_url('script/debug.js'); ?>" type="text/javascript"></script>
        <script type="text/javascript">

                    $(function() {
                    DWZ.init("<?php echo static_url('dwz/dwz.frag.xml'); ?>", {
                    loginUrl: "", //跳到登录页面
                            loginTitle: "登录", // 弹出登录对话框
                            debug: false, // 调试模式 【true|false】
                            pageInfo: {
                            pageNum: "_page", numPerPage: "_size", orderField: "_order", orderDirection: "_direct"
                            },
                            callback: function() {
                            initEnv();
                                    $("#themeList").theme({
                            themeBase: "<?php echo static_url('dwz/themes'); ?>"
                            });
                            }
                    });
                            DWZ.ajaxDone = function(json) {
                            if (json.statusCode == DWZ.statusCode.ok) {
                            alertMsg.correct(json.message);
                                    if (json.reloadNavTab){
                            // navTab.getCurrentPanel()
                            navTab.reload()
                            }

                            if (json.closeDialog) {
                            $.pdialog.closeCurrent();
                            }
                            } else {
                            alertMsg.error(json.message);
                            }
                            }
                    });
                    /**
                     * 初始化form里的input数值
                     * @param {type} id  form的 id
                     * @param {json} json 数据内容
                     * @returns {undefined}     
                     */
                            function initFormValue(id, json) {
                            var obj = $("#" + id);
                                    var tmp = null;
                                    for (var k in json) {
                            v = json[k];
                                    tmp = obj.find("select[name='" + k + "']");
                                    if (tmp)
                                    tmp.val(v);
                                    tmp = obj.find("input[name='" + k + "']");
                                    if (tmp)
                                    tmp.val(v);
                            }
                            tmp = $("#select-numPerPage");
                                    if (tmp)
                                    tmp.val(json['numPerPage']);
                            }

        </script></head>


    <body scroll="no">
        <div id="layout">
            <div id="header">
                <div class="headerNav">
                    <ul class="nav">
                        <li><a href="{url 'home/admin/editPass'); ?>" target="dialog" mask="true">修改密码</a></li>
                        <li><a href="{url 'home/admin/logout'); ?>">退出</a></li>
                    </ul>
                    <!--
                    <ul class="themeList" id="themeList">
                        <li theme="default"><div class="selected">蓝色</div></li>
                        <li theme="green"><div>绿色</div></li>
                        <li theme="purple"><div>紫色</div></li>
                        <li theme="silver"><div>银色</div></li>
                        <li theme="azure"><div>天蓝</div></li>
                    </ul>
                    -->
                </div>
                <div id="navMenu">
                    <ul>
                        <li></li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="footer"></div>
    </body>
</html>