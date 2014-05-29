<form enctype="multipart/form-data" action="<?php echo site_url("upload") ?>" method="POST">
    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
    Send this file: <input name="file" type="file" />
    <input type="submit" value="Send File" />
</form>