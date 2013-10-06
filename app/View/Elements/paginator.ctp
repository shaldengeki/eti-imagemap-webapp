<div class='center-horizontal'>
  <?php echo $this->Paginator->numbers([
    'before' => '<ul class="pagination">',
    'separator' => '',
    'currentClass' => 'active',
    'currentTag' => 'a',
    'tag' => 'li',
    'after' => '</ul>'
  ]); ?>
</div>