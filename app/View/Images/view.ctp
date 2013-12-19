<!-- File: /app/View/Images/view.ctp -->
<?php
  $this->start('sidebar');
    echo $this->element('sidebar/tag_list');
    echo $this->element('sidebar/image_info');
    echo $this->element('sidebar/image_menu');
  $this->end();
?>
<div id='image'>
  <?php echo $this->Html->link(
                               $this->Html->image($this->Image->etiUrl($image),
                                                  [
                                                    'class' => 'scale',
                                                  ]),
                               $this->Image->etiUrl($image),
                               [
                                'target' => '_blank',
                                'escape' => False
                               ]
                               ); ?>
</div>