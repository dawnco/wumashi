
<div class="container wms-header">

    <div class="starter-template">
        <h1>Wumashi PHP</h1>
        <p class="lead">a simple php framework</p>
    </div>

    <ul>
        <?php foreach ($lists as $vo): ?>
            <li>
                <a href="<?php echo site_url("blog/archives/{$vo['id']}.html"); ?>"><?php echo $vo['title'] ?></a>
            </li>
        <?php endforeach; ?>

    </ul>

    <div class="pagination">
        <?php echo $pagination ?>
    </div>
</div>