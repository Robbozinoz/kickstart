<?php require_once('_inc/header.php'); ?>
    <!--Form extracts database instance and concatenates the save action to the route-->
    <form action="<?php echo $this->base->url.'posts/.php?action=save'; ?>" method="post">
        <h3>New Post</h3>
        <div class="control-group">
            <label for="content" class="control-label">Content</label>
            <div class="controls">
                <textarea name="post[content" id="content"></textarea>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <button class="btn" type="submit">Save Post</button>
            </div>
        </div>
    </form>
<?php require_once('_inc/footer.php'); ?>