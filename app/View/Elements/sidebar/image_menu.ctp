<?php
if (isset($image)) {
?>
<div class='image-menu'>
  <h3>Menu</h3>
  <ul>
    <li><?php echo $this->Html->link('Edit', [
                                     'action' => 'edit',
                                     $image['Image']['id']
    ]); ?></li>
    <li><?php echo $this->Form->postLink('Delete', [
                                     'action' => 'delete',
                                     $image['Image']['id']
    ], [
      'confirm' => "Are you sure you want to delete this image?"
    ]); ?></li>
  </ul>
</div>
<?php
}
?>