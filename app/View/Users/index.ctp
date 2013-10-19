<div class='page-header'>
  <h1>Users</h1>
</div>
<?php echo $this->element('paginator'); ?>
<table class='table table-hover'>
  <thead>
    <tr>
      <th>Username</th>
      <th>Joined on</th>
      <th>Type</th>
      <th>Images</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($users as $user): ?>
    <tr>
      <td>
        <?php echo $this->Html->link($user['User']['name'],
array('controller' => 'users', 'action' => 'view', $user['User']['id'])); ?>
      </td>
      <td><?php echo $user['User']['created']; ?></td>
      <td><?php echo h($user['User']['role']); ?></td>
      <td><?php echo $user['User']['image_count']; ?></td>
    </tr>
  <?php endforeach; ?>
  <?php unset($user); ?>
  </tbody>
</table>