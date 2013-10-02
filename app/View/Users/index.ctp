<!-- File: /app/View/Users/index.ctp -->

<h1>Users</h1>
<table>
  <tr>
    <th>Id</th>
    <th>Name</th>
    <th>Actions</th>
    <th>Last IP</th>
  </tr>

  <?php foreach ($users as $user): ?>
  <tr>
    <td><?php echo $user['User']['id']; ?></td>
    <td>
      <?php echo $this->Html->link($user['User']['name'],
array('controller' => 'users', 'action' => 'view', $user['User']['id'])); ?>
    </td>
    <td>
      <?php echo $this->Form->postLink(
                                       'Delete',
                                       [
                                        'action' => 'delete',
                                        $user['User']['id']
                                       ],
                                       [
                                        'confirm' => 'Are you sure?'
                                       ]
                                       );
      ?>
      <?php echo $this->Html->link('Edit', ['action' => 'edit', $user['User']['id']]); ?>
    </td>
    <td><?php echo $user['User']['last_ip']; ?></td>
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