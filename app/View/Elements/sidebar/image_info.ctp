<?php
if (isset($image)) {
?>
<div class='image-info'>
  <h3>Information</h3>
  <ul>
    <li>ID: <?php echo $image['id']; ?></li>
    <li>Uploader: <?php echo $this->Html->link($user['name'], [
                                                'controller' => 'users',
                                                'action' => 'view',
                                                $user['id']
    ]); ?></li>
    <li>Date: <?php echo $image['created']; ?></li>
    <li>Hits: <?php echo $image['hits']; ?></li>
    <li>
      <span class='input-group input-group-sm eti-copy-fields'>
        <?php echo $this->Form->input('copy_url', [
                                                    'id' => 'eti-copy-field',
                                                    'class' => 'copy-field form-control',
                                                    'type' => 'text',
                                                    'value' => $this->Image->etiImageTag($image),
                                                    'data-clipboard-target' => $this->Image->etiImageTag($image),
                                                    'readonly' => 'readonly',
                                                    'div' => False,
                                                    'label' => False
                                                  ]); ?>
        <span class='copy-button input-group-addon glyphicon glyphicon-paperclip' data-clipboard-target='eti-copy-field'></span>
      </span>
    </li>
  </ul>
</div>
<?php
}
?>