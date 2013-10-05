<!-- File: /app/View/Users/index.ctp -->

<h1>Users</h1>
<table>
  <tr>
    <th>Id</th>
    <th>Name</th>
  </tr>

  <?php foreach ($users as $user): ?>
  <tr>
    <td><?php echo $user['User']['id']; ?></td>
    <td>
      <?php echo $this->Html->link($user['User']['name'],
array('controller' => 'users', 'action' => 'view', $user['User']['id'])); ?>
    </td>
  </tr>
  <?php endforeach; ?>
  <?php unset($user); ?>
</table>
<?php
  echo $this->Html->link('Add User', 
                         [
                           'controller' => 'users',
                           'action' => 'add'
                        ]);
?>