<ul class='image-grid'>
<?php
  foreach ($images as $image) {
?>
  <li>
    <?php echo $this->Html->link(
                                 $this->Html->image(
                                                    $image['eti_thumb_url'],
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
    <span class='copy-button glyphicon glyphicon-paperclip' data-clipboard-text='<?php echo h($image['eti_image_tag']); ?>'></span>
  </li>
<?php
  }
?>
</ul>