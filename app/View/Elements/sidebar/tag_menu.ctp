<?php
if (isset($tag)) {
?>
<div class='tag-menu'>
  <h3>Menu</h3>
  <ul>
    <li><?php echo $this->Html->link('Edit', [
                                     'action' => 'edit',
                                     $tag['id']
    ]); ?></li>
    <li><?php echo $this->Form->postLink('Delete', [
                                     'action' => 'delete',
                                     $tag['id']
    ], [
      'confirm' => "Are you sure you want to delete this tag?"
    ]); ?></li>
  </ul>
</div>
<?php
}
?>