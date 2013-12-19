<!-- File: /app/View/ImageLists/index.ctp -->
<?php echo $this->element('paginator'); ?>
<div id='image_lists' class='media'>
<?php
  foreach ($imageLists as $imageList) {
?>
  <div class='media-body'>
    <h4 class='media-heading'><?php echo $this->Html->link(
      $imageList['ImageList']['name'],
      [
        'controller' => 'image_lists',
        'action' => 'view',
        $imageList['ImageList']['id']
      ]
    ); ?></h4>
    <p><?php echo h($imageList['ImageList']['description']); ?></p>
    <?php echo $this->element('image_row', ['images' => $imageList['Images']]); ?>
  </div>
  <div class='pull-right'>
<?php

?>
  </div>
<?php
  }
?>
</div>
<?php echo $this->element('paginator'); ?>