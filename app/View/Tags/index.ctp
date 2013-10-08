<!-- File: /app/View/Tags/index.ctp -->

<h1>Tags</h1>
<table>
  <tr>
    <th>Id</th>
    <th>Name</th>
  </tr>

  <?php foreach ($tags as $tag): ?>
  <tr>
    <td><?php echo $tag['Tag']['id']; ?></td>
    <td>
      <?php echo $this->Html->link($tag['Tag']['name'],
array('controller' => 'tags', 'action' => 'view', $tag['Tag']['id'])); ?>
    </td>
  </tr>
  <?php endforeach; ?>
  <?php unset($tag); ?>
</table>
<?php
  echo $this->Html->link('Add Tag', 
                         [
                           'controller' => 'tags',
                           'action' => 'add'
                        ]);
?>