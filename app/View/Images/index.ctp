<!-- File: /app/View/Images/index.ctp -->
<div class='page-header'>
  <h1>Images</h1>
</div>
<?php echo $this->element('paginator'); ?>
<?php echo $this->element('image_grid', ["images" => $images]); ?>
<?php echo $this->element('paginator'); ?>