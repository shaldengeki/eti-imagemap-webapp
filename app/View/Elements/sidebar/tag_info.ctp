<?php
  if (isset($tag)) {
    $created = DateTime::createFromFormat('Y-m-d H:i:s', $tag['created']);
    $tag['created'] = $created->format('Y-m-d');
?>
<div class='tag-info'>
  <h3>tag:<?php echo h($tag['name']); ?></h3>
  <ul>
    <li>Created: <?php echo $tag['created']; ?></li>
    <li>Images: <?php echo $tag['image_count']; ?></li>
  </ul>
</div>
<?php
  }
?>