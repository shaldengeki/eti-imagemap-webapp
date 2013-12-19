<?php
  if (isset($imageList)) {
    $created = DateTime::createFromFormat('Y-m-d H:i:s', $imageList['ImageList']['created']);
    $imageList['ImageList']['created'] = $created->format('Y-m-d');
    $updated = DateTime::createFromFormat('Y-m-d H:i:s', $imageList['ImageList']['updated']);
    $imageList['ImageList']['updated'] = $updated->format('Y-m-d');
?>
<div class='image-list-info'>
  <h3>list:<?php echo h($imageList['ImageList']['name']); ?></h3>
  <ul>
    <li>Created: <?php echo $imageList['ImageList']['created']; ?></li>
    <li>Updated: <?php echo $imageList['ImageList']['updated']; ?></li>
    <li>Hits: <?php echo $imageList['ImageList']['hits']; ?></li>
    <li>Images: <?php echo $imageList['ImageList']['image_count']; ?></li>
    <li>Follows: <?php echo $imageList['ImageList']['follow_count']; ?></li>
  </ul>
</div>
<?php
  }
?>