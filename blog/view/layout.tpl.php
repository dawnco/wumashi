<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
    <title><?php echo $meta['title'] ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo static_url('bootstrap/css/bootstrap.css', "common") ?>" />
     <link rel="stylesheet" type="text/css" href="<?php echo static_url('style/style.css') ?>" />
    <script type="text/javascript" src="<?php echo static_url('script/jquery/jquery.min.js', "common") ?>"></script>
    <script type="text/javascript" src="<?php echo static_url('bootstrap/js/bootstrap.js', "common") ?>"></script>
</head>
<body>
    <header role="banner" class="navbar navbar-inverse navbar-fixed-top bs-docs-nav">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" target="_blank" href="http://blog.wumashi.com">五马石</a>
            </div>
            <nav role="navigation" class="collapse navbar-collapse bs-navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="<?php echo site_url(''); ?>">主页</a></li>
                    <li><a href="<?php echo site_url('about.html'); ?>">关于</a></li>
                    <li><a href="<?php echo site_url("contact.html") ?>">联系</a></li>
                </ul>

            </nav>
        </div>
    </header>
    <div class=" wms-header">
        <?php include APP_PATH . "view/$tpl.tpl.php" ?>
    </div>

</body>
</html>
