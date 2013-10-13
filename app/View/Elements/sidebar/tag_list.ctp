<?php
  if (isset($tagListing) && is_array($tagListing)) {
    // sort tag listing by number of occurrences descending.
    $counts = [];
    foreach ($tagListing as $key=>$tag) {
      $counts[$key] = $tag['count'];
    }
    array_multisort($counts, SORT_DESC, $tagListing);
?>
<div class='tag-list'>
  <h3>Tags</h3>
<?php
    if ($tagListing) {
?>
  <ul>
<?php
      foreach ($tagListing as $tag) {
?>
      <li>
<?php 
        if (isset($tagQuery)) {
          echo $this->Html->link('+', [
                                  'controller' => 'images',
                                  'action' => 'index',
                                  '?' => [
                                    'tags' => $tag['addLink']
                                  ]
                                ]);
          echo $this->Html->link('-', [
                                  'controller' => 'images',
                                  'action' => 'index',
                                  '?' => [
                                    'tags' => $tag['removeLink']
                                  ]
                                ]);
        }
        ?>
        <?php echo $this->Html->link($tag['name'], [
                                       'controller' => 'tags',
                                       'action' => 'view',
                                       $tag['id']
      ]); ?>: <?php echo $tag['count']; ?></li>
<?php
      }
?>
  </ul>
<?php
    } else {
?>
  No tags!
<?php
    }
?>
</div>
<?php
  }
?>
