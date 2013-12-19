<!-- File: /app/View/Images/add.ctp -->
<div class='page-header'>
  <h1>Create List</h1>
</div>
<div class='row'>
  <div class='col-md-6'>
    <?php echo $this->Form->create('ImageList', [
                              'class' => 'form-horizontal',
                              'role' => 'form'
                             ]); ?>
      <div class='form-group'>
        <label for='ImageListName' class='col-lg-2 control-label'>Name</label>
        <?php echo $this->Form->input('name', [
                                      'class' => 'form-control',
                                      'placeholder' => 'must be unique to you',
                                      'div' => [
                                        'class' => 'col-lg-10'
                                      ],
                                      'label' => False
                                      ]); ?>
      </div>
      <div class='form-group'>
        <label for='ImageListDescription' class='col-lg-2 control-label'>Description</label>
        <?php echo $this->Form->input('description', [
                                      'type' => 'textarea',
                                      'class' => 'form-control',
                                      'placeholder' => 'description of list',
                                      'div' => [
                                        'class' => 'col-lg-10'
                                      ],
                                      'label' => False
                                      ]); ?>
      </div>
      <div class='form-group'>
        <label for='ImageListPrivate' class='col-lg-2 control-label'>Private</label>
        <?php echo $this->Form->input('private', [
                                      'class' => 'form-control',
                                      'type' => 'checkbox',
                                      'div' => [
                                        'class' => 'float-left col-lg-1'
                                      ],
                                      'label' => False
                                      ]); ?>
      </div>
      <div class='form-group'>
        <div class='col-lg-offset-2 col-lg-10'>
          <?php echo $this->Form->button('Create List', [
                                          'type' => 'submit',
                                          'class' => 'btn btn-default'
                                         ]); ?>
        </div>
      </div>
    <?php echo $this->Form->end(); ?>
  </div>
</div>