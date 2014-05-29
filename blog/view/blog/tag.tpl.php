<div class="container">
    <div class="page-header">
        <h1>关于标签 <?php echo $tag_name ?> 的内容</h1><span></span>
    </div>
    <div class="page-content">
        <ul>
            <?php foreach ($lists as $vo): ?>
                <li>
                    <a href="<?php echo site_url("blog/archives/{$vo['id']}.html"); ?>"><?php echo $vo['title'] ?></a>
                </li>
            <?php endforeach; ?>

        </ul>
    </div>

</div>