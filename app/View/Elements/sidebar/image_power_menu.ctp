<?php
if (isset($images) && isset($authUser)) {
?>
<div class='image-power-menu'>
  <h3>Power Menu</h3>
  <ul>
    <li>
      <?php echo $this->Form->button('Add to List', [
                                        'class' => 'btn btn-success btn-item-select',
                                        'data-target' => '#images',
                                        'data-inject-url' => $this->Html->url([
                                                                                'controller' => 'users',
                                                                                'action' => 'power_list',
                                                                                $authUser['id']
                                                                              ]),
                                        'data-inject-target' => '#power-list-form',
                                        'data-url' => $this->Html->url([
                                                                        'controller' => 'lists',
                                                                        'action' => 'power_add'
                                                                       ])
      ]); ?>
      <span id='power-list-form'></span>
    </li>
    <li>
      <?php echo $this->Form->button('Add Tags', [
                                        'class' => 'btn btn-info btn-item-select',
                                        'data-target' => '#images',
                                        'data-inject-url' => $this->Html->url([
                                                                                'controller' => 'tags',
                                                                                'action' => 'power_tag'
                                                                              ]),
                                        'data-inject-target' => '#power-tag-form',
                                        'data-url' => $this->Html->url([
                                                                        'controller' => 'tags',
                                                                        'action' => 'power_tag'
                                                                       ])
      ]); ?>
      <span id='power-tag-form'></span>
    </li>
    <li>
      <?php echo $this->Form->button('Save', [
                                       'class' => 'btn btn-primary btn-item-select-save',
                                       'data-url' => '',
                                       'data-form' => '',
                                       'data-target' => ''
      ]); ?>
  </ul>
</div>
<?php
}
?>