<!-- File: /app/View/Images/view.ctp -->
<?php
  $imageURL = "http://i".$image['Image']['server'].".endoftheinter.net/i/n/".$image['Image']['hash']."/image.".$image['Image']['type'];
?>
<h1><?php echo h($image['Image']['hash']); ?></h1>
<hr />
<ul>
  <li>Added on: <?php echo $image['Image']['added_on']; ?></li>
  <li>Uploaded by: <?php echo $this->Html->link($image['User']['name'], [
                                                'controller' => 'users',
                                                'action' => 'view',
                                                $image['User']['id']
  ]); ?></li>
</ul>

<?php echo $this->Html->link(
                             $this->Html->image($imageURL),
                             $imageURL,
                             [
                              'target' => '_blank',
                              'escape' => False
                             ]
); ?>