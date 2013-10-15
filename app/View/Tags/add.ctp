<!-- File: /app/View/Tags/add.ctp -->
<div class='page-header'>
  <h1>Add Tag</h1>
</div>
<div class='row'>
  <div class='col-md-6'>
    <?php echo $this->Form->create('Tag', [
                              'class' => 'form-horizontal',
                              'role' => 'form'
                             ]); ?>
      <div class='form-group'>
        <label for='TagName' class='col-lg-2 control-label'>Name</label>
        <?php echo $this->Form->input('name', [
                                      'class' => 'form-control',
                                      'placeholder' => 'lowercase and underscores',
                                      'div' => [
                                        'class' => 'col-lg-10'
                                      ],
                                      'label' => False
                                      ]); ?>
      </div>
      <div class='form-group'>
        <div class='col-lg-offset-2 col-lg-10'>
          <?php echo $this->Form->button('Add Tag', [
                                          'type' => 'submit',
                                          'class' => 'btn btn-default'
                                         ]); ?>
        </div>
      </div>
    <?php echo $this->Form->end(); ?>
  </div>
</div>