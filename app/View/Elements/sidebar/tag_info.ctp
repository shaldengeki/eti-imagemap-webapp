<?php
  if (isset($tag)) {
    $created = DateTime::createFromFormat('Y-m-d H:i:s', $tag['Tag']['created']);
    $tag['Tag']['created'] = $created->format('Y-m-d');
?>
<div class='tag-info'>
  <h3>Tag: <?php echo h($tag['Tag']['name']); ?></h3>
  <ul>
    <li>Created: <?php echo $tag['Tag']['created']; ?></li>
    <li>Images: <?php echo $tag['Tag']['image_count']; ?></li>
  </ul>
</div>
<?php
  }
?>