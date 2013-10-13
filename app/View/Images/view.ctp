<!-- File: /app/View/Images/view.ctp -->
<?php
  $this->start('sidebar');
    echo $this->element('sidebar/tag_list');
    echo $this->element('sidebar/image_info');
    echo $this->element('sidebar/image_menu');
  $this->end();
?>
<?php echo $this->Html->link(
                             $this->Html->image($image['Image']['eti_url']),
                             $image['Image']['eti_url'],
                             [
                              'target' => '_blank',
                              'escape' => False
                             ]
                             ); ?>