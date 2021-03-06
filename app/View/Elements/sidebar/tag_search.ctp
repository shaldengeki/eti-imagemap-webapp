<?php
  $tagQuery = isset($this->request->query['tags']) ? $this->request->query['tags'] : "";
  $this->set('tagQuery', $tagQuery);
?>
<div class='tag-search'>
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
                                      'class' => 'form-control autocomplete',
                                      'placeholder' => 'tag_names',
                                      'value' => $tagQuery,
                                      'data-url' => $this->Html->url([
                                                                     'controller' => 'tags',
                                                                     'action' => 'autocomplete',
                                                                     '?' => [
                                                                      'query' => ''
                                                                     ]
                                                                     ]),
                                      'label' => False,
                                      'div' => [
                                        'class' => 'col-lg-12'
                                      ]
                                    ]); ?>
    </div>
  <?php echo $this->Form->end(); ?>
</div>