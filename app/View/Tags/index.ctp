<?php echo $this->element('paginator'); ?>
<table class='table table-hover'>
  <thead>
    <tr>
      <th>Name</th>
      <th>Images</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($tags as $tag): ?>
    <tr>
      <td>
        <?php echo $this->Html->link($tag['Tag']['name'],
array('controller' => 'tags', 'action' => 'view', $tag['Tag']['id'])); ?>
      </td>
      <td><?php echo $tag['Tag']['image_count']; ?></td>
    </tr>
  <?php endforeach; ?>
  <?php unset($tag); ?>
  </tbody>
</table>
<?php echo $this->element('paginator'); ?>