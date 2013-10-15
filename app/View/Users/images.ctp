<?php
  $this->start('sidebar');
    echo $this->element('sidebar/tag_list');
    echo $this->element('sidebar/image_power_menu');
  $this->end();
?>
<!-- File: /app/View/Users/images.ctp -->
<div class='page-header'>
  <h1><?php echo h($user['User']['name']); ?>: Images</h1>
</div>
<?php echo $this->element('paginator'); ?>
<div id='images'>
  <?php echo $this->element('image_grid', ["images" => $images]); ?>
</div>
<?php echo $this->element('paginator'); ?>