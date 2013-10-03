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
  </li>
<?php
  }
?>
</ul>