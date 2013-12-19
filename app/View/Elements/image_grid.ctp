<ul class='image-grid'>
<?php
  foreach ($images as $image) {
?>
  <li>
    <?php echo $this->Html->link(
                                 $this->Html->image(
                                                    $this->Image->etiThumbUrl($image),
                                                    [
                                                      'alt' => 'sup'
                                                    ]
                                                    ),
                                 [
                                  'controller' => 'images',
                                  'action' => 'view',
                                  $image['id']
                                 ],
                                 [
                                  'escape' => False
                                 ]
                                 ); ?>
    <span class='copy-button glyphicon glyphicon-paperclip' data-clipboard-text='<?php echo h($this->Image->etiImageTag($image)); ?>'></span>
  </li>
<?php
  }
?>
</ul>