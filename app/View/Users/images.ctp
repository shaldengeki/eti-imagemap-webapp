<!-- File: /app/View/Users/images.ctp -->
<div class='page-header'>
  <h1><?php echo h($user['User']['name']); ?>: Images</h1>
</div>
<?php echo $this->element('paginator'); ?>
<?php echo $this->element('image_grid', ["images" => $images]); ?>
<?php echo $this->element('paginator'); ?>