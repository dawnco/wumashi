<div class="container">
    <div class="page-header">
        <h1><?php echo $title ?></h1><span><?php echo substr($created_time, 0, 10) ?></span>
    </div>
    <div class="page-content">
        <?php echo $content ?>
    </div>
    <div >
        标签 :
        <?php foreach ($tag as $vo): ?>
            <a class="btn btn-default" href="<?php echo site_url('blog/tag/' . $vo['name']); ?>" role="button"><?php echo $vo['name'] ?></a>
        <?php endforeach; ?>

    </div>
</div>