<!-- File: /app/View/Images/index.ctp -->

<h1>Images</h1>
<hr />
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
<?php echo $this->element('image_grid', ["images" => $images]); ?>
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