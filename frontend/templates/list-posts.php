<?php require_once('includes/temps/header.php'); ?>
<?php foreach ($posts as $post): ?>
    <h3>Post #<?php echo htmlspecialchars($post['id']); ?></h3>
    <!--Line below uses three inbuilt functions to remove unwanted characters split text into 10 chunks of text and re-join them-->
    <p><?php echo implode(' ', array_slice(explode(' ', strip_tags($post['content'])), 0, 10)); ?> [...]</p>
    <a href="<?php echo $this->base->url. "/?id=".$post['id']; ?>" class="btn btn-primary">Read more</a>
<hr/>
<?php endforeach; ?>
<?php require_once('includes/temps/footer.php'); ?>