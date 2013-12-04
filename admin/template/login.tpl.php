<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="keywords" content="" >
        <meta name="description" content="" />
        <link rel="stylesheet" type="text/css" href="<?php echo static_url('css/bootstrap.css'); ?>'); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo static_url('css/app.css'); ?>" />
        <script type="text/javascript" src="<?php echo static_url('js/jquery.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo static_url('js/jquery.cookie.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo static_url('js/bootstrap.js'); ?>"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo static_url('css/datepicker.css'); ?>" />
        <script type="text/javascript" src="<?php echo static_url('js/bootstrap-datepicker.js'); ?>"></script>
        <link rel="stylesheet" type="text/css" href="<?php echo static_url('validate/validate.css'); ?>" />
        <script type="text/javascript" src="<?php echo static_url('validate/validform.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo static_url('validate/validform.datatype.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo static_url('js/app.js'); ?>"></script>
        <!--[if lte IE 6]>
        <link rel="stylesheet" type="text/css" href="<?php echo static_url('css/bootstrap-ie6.css'); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo static_url('css/ie.css'); ?>">
        <script type="text/javascript" src="<?php echo static_url('js/bootstrap-ie.js'); ?>"></script>
        <![endif]-->
    </head>
    <body>
        <script type="text/javascript">
            if ($.isFunction($.bootstrapIE6))
                $.bootstrapIE6(el);
            jQuery(function() {
//                $('button.btn').on('click', function() {
//                    $(this).button('loading')
//                })
            })
        </script>  



        <div class="container">


            <form class="form-horizontal validate-form" method="POST"  action="" id="signin">
                <h2 class="form-signin-heading">管理员登录</h2>
                <?php if(isset($__error)): ?>
                    <?php foreach($__error as $vo): ?>
                    <div class="alert">
                    <?php echo $vo ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                    
                    <?php endforeach; ?>
                <?php endif ?>

                
                

                <div class="control-group">
                    <label class="control-label" for="username">用户名</label>
                    <div class="controls">
                        <input name="username" type="text"  title="" value=""  datatype="s4-18" nullmsg="请输入您的用户名！" errormsg="用户名至少4个字符,最多18个字符！" />
                        <span class="tipinfo"><span>用户名至少4个字符,最多18个字符</span></span>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="password">密　码</label>
                    <div class="controls">
                        <input name="password" type="password" datatype="*"  nullmsg="请输入密码" value=""/>
                    </div>
                </div>

                <button class="btn btn-large btn-primary" type="submit" data-loading-text="登录中...">登录</button>
            </form>

        </div>

        <script type="text/javascript">
            $(function() {
                validateForm()
            });
        </script>
    </body>
</html>
