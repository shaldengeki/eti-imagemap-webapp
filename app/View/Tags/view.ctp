<!-- File: /app/View/Tags/view.ctp -->
<?php
  $created = DateTime::createFromFormat('Y-m-d H:i:s', $tag['Tag']['created']);
  $tag['Tag']['created'] = $created->format('Y-m-d');
?>

<div class='page-header'>
  <h1>Tag: <?php echo h($tag['Tag']['name']); ?></h1>
</div>
<ul>
  <li>Created: <?php echo $tag['Tag']['created']; ?></li>
  <li>Images: <?php echo $tag['Tag']['image_count']; ?></li>
</ul>

<h2>Images</h2>
<?php echo $this->element('paginator'); ?>
<?php echo $this->element('image_grid', ["images" => $images]); ?>
<?php echo $this->element('paginator'); ?>