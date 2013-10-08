<?php
  if (isset($tagCounts) && is_array($tagCounts)) {
    $counts = [];
    foreach ($tagCounts as $key=>$tag) {
      $counts[$key] = $tag['count'];
    }
    array_multisort($counts, SORT_DESC, $tagCounts);
?>
<h3>Tags</h3>
<ul class='tag-list'>
<?php
  foreach ($tagCounts as $tag) {
?>
    <li><?php echo $this->Html->link($tag['name'], [
                                     'controller' => 'tags',
                                     'action' => 'view',
                                     $tag['id']
    ]); ?>: <?php echo $tag['count']; ?></li>
<?php
  }
?>
</ul>
<?php
  }
?>
