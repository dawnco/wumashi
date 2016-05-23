<?php if (substr(get_client_ip(), 0, 7) == "192.168"): ?>
    <?php if (php_sapi_name() == "cli"): ?>
        <?php
        echo $message;
        echo "\n\n";
        echo $trace;
        ?>
    <?php else: ?>
        <!DOCTYPE html>
        <html>
            <head>
                <meta charset="UTF-8"/>
                <title>系统发生错误</title>
                <style type="text/css">
                    *{ padding: 0; margin: 0; }
                    html{ overflow-y: scroll; }
                    body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
                    img{ border: 0; }
                    .error{ padding: 24px 48px; }
                    h1{ font-size: 32px; line-height: 48px; }
                    .error .content{ padding-top: 10px}
                    .error .info{ margin-bottom: 12px; }
                    .error .info .title{ margin-bottom: 3px; }
                    .error .info .title h3{ color: #000; font-weight: 700; font-size: 16px; }
                    .error .info .text{ line-height: 24px; }
                    .copyright{ padding: 12px 48px; color: #999; }
                    .copyright a{ color: #000; text-decoration: none; }
                </style>
            </head>
            <body>
                <div class="error">
                    <h1><?php echo strip_tags($message); ?></h1>
                    <div class="content">
                        <?php if (isset($file)) { ?>
                            <div class="info">
                                <div class="title">
                                    <h3>错误位置</h3>
                                </div>
                                <div class="text">
                                    <p>FILE: <?php echo $file; ?> &#12288 LINE: <?php echo $line; ?></p>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (!empty($trace)) { ?>
                            <div class="info">
                                <div class="title">
                                    <h3>TRACE</h3>
                                </div>
                                <div class="text">
                                    <p><?php echo nl2br($trace); ?></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="copyright">
                </div>
            </body>
        </html>
    <?php endif ?>
<?php else: ?>
    error
<?php endif  ?>