<h3>Search</h3>
<?php echo $this->Form->create('Image', [
                               'class' => 'form-inline',
                               'role' => 'form',
                               'type' => 'get',
                               'action' => 'index'
]); ?>
  <div class='form-group'>
    <label class='sr-only' for='ImageTags'>Tags</label>
    <?php echo $this->Form->input('tags', [
                                    'type' => 'text',
                                    'class' => 'form-control',
                                    'placeholder' => 'tag_names'
                                  ]); ?>
  </div>
<?php echo $this->Form->end(); ?>