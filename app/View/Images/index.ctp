<!-- File: /app/View/Images/index.ctp -->
<?php
  $this->start('sidebar');
    echo $this->element('sidebar/tag_search');
    echo $this->element('sidebar/tag_list');
  $this->end();
?>
<div class='page-header'>
  <h1>Images</h1>
</div>
<?php echo $this->element('paginator'); ?>
<?php echo $this->element('image_grid', ["images" => $images]); ?>
<?php echo $this->element('paginator'); ?>