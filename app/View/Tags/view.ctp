<?php
  $this->start('sidebar');
    echo $this->element('sidebar/tag_list');
    echo $this->element('sidebar/tag_info');
    echo $this->element('sidebar/tag_menu');
    echo $this->element('sidebar/image_power_menu');
  $this->end();
?>
<!-- File: /app/View/Tags/view.ctp -->
<h2>Images</h2>
<?php echo $this->element('paginator'); ?>
<div id='images'>
  <?php echo $this->element('image_grid', ["images" => $images]); ?>
</div>
<?php echo $this->element('paginator'); ?>