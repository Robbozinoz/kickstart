<!--Change to require -Robboz-->
<?php require_once('includes/temps/header.php'); ?>
<br/>
<!--This template uses the information extracted from the url to send $posts array content to $post variable-->
<a href="<?php echo $this->base->url; ?>" class="btn btn-primary">Return to Post List</a>
<?php foreach ($posts as $post): ?>
    <h3>Post #<?php echo htmlspecialchars($post['id']); ?></h3><?php echo htmlspecialchars($post['content']); ?>    
<hr/>
<?php endforeach; ?>
<!--Change to require -Robboz-->
<?php require_once('includes/temps/footer.php'); ?>