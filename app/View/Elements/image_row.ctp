<ul class='image-row'>
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
                                 );
    ?>
  </li>
<?php
  }
?>
</ul>