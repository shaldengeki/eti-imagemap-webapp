<!-- File: /app/View/Users/view.ctp -->
<?php
  $created = DateTime::createFromFormat('Y-m-d H:i:s', $user['User']['created']);
  $user['User']['created'] = $created->format('Y-m-d');
?>

<h1><?php echo h($user['User']['name']); ?></h1>
<hr />
<ul>
  <li>Join Date: <?php echo $user['User']['created']; ?></li>
  <li>Images: <?php echo $user['User']['image_count']; ?></li>
</ul>

<h2>Latest Uploads:</h2>
<?php echo $this->element('image_grid', ["images" => $images]); ?>