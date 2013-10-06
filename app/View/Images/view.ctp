<!-- File: /app/View/Images/view.ctp -->
<h1><?php echo h($image['Image']['hash']); ?></h1>
<hr />
<ul>
  <li>Added on: <?php echo $image['Image']['created']; ?></li>
  <li>Uploaded by: <?php echo $this->Html->link($image['User']['name'], [
                                                'controller' => 'users',
                                                'action' => 'view',
                                                $image['User']['id']
  ]); ?></li>
  <li>Hits: <?php echo $image['Image']['hits']; ?></li>
  <li>
    <span class='input-group eti-copy-fields'>
      <?php echo $this->Form->input('copy_url', [
                                                  'id' => 'eti-copy-field',
                                                  'class' => 'copy-field form-control',
                                                  'type' => 'text',
                                                  'value' => $image['Image']['eti_image_tag'],
                                                  'data-clipboard-target' => $image['Image']['eti_image_tag'],
                                                  'readonly' => 'readonly',
                                                  'div' => False,
                                                  'label' => False
                                                ]); ?>
      <span class='copy-button input-group-addon glyphicon glyphicon-paperclip' data-clipboard-target='eti-copy-field'></span>
    </span>
  </li>
</ul>

<?php echo $this->Html->link(
                             $this->Html->image($image['Image']['eti_url']),
                             $image['Image']['eti_url'],
                             [
                              'target' => '_blank',
                              'escape' => False
                             ]
); ?>