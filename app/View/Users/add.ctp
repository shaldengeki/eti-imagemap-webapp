<!-- File: /app/View/Users/add.ctp -->
<div class='page-header'>
  <h1>Sign Up</h1>
</div>
<div class='row'>
  <div class='col-md-6'>
    <?php echo $this->Form->create('User', [
                              'class' => 'form-horizontal',
                              'role' => 'form'
                             ]); ?>
      <div class='form-group'>
        <label for='UserId' class='col-lg-2 control-label'>ETI UserID</label>
        <?php echo $this->Form->input('id', [
                                      'type' => '',
                                      'class' => 'form-control',
                                      'placeholder' => 'e.g. 6731',
                                      'div' => [
                                        'class' => 'col-lg-10'
                                      ],
                                      'label' => False
                                      ]); ?>
      </div>
      <div class='form-group'>
        <label for='UserName' class='col-lg-2 control-label'>Username</label>
        <?php echo $this->Form->input('name', [
                                      'type' => '',
                                      'class' => 'form-control',
                                      'placeholder' => 'e.g. shaldengeki',
                                      'div' => [
                                        'class' => 'col-lg-10'
                                      ],
                                      'label' => False
                                      ]); ?>
      </div>
      <div class='form-group'>
        <div class='col-lg-offset-2 col-lg-10'>
          <?php echo $this->Form->button('Sign Up', [
                                          'type' => 'submit',
                                          'class' => 'btn btn-default'
                                         ]); ?>
        </div>
      </div>
    <?php echo $this->Form->end(); ?>
  </div>
</div>