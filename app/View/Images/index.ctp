<!-- File: /app/View/Images/index.ctp -->

<h1>Images</h1>
<table>
  <tr>
    <th>Id</th>
    <th>Hash</th>
    <th>Actions</th>
    <th>Added On</th>
  </tr>

  <?php foreach ($images as $image): ?>
  <tr>
    <td><?php echo $image['Image']['id']; ?></td>
    <td>
      <?php echo $this->Html->link($image['Image']['hash'],
array('controller' => 'images', 'action' => 'view', $image['Image']['id'])); ?>
    </td>
    <td>
      <?php echo $this->Form->postLink(
                                       'Delete',
                                       [
                                        'action' => 'delete',
                                        $image['Image']['id']
                                       ],
                                       [
                                        'confirm' => 'Are you sure?'
                                       ]
                                       );
      ?>
      <?php echo $this->Html->link('Edit', ['action' => 'edit', $image['Image']['id']]); ?>
    </td>
    <td><?php echo $image['Image']['added_on']; ?></td>
  </tr>
  <?php endforeach; ?>
  <?php unset($image); ?>
</table>
<?php
  echo $this->Html->link('Add Image', 
                         [
                           'controller' => 'images',
                           'action' => 'add'
                        ]);
?>